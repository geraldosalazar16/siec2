<?php
function creacion_expediente_registro($id_registro, $entidad, $rutaExpediente, $database){
	
	
	
	$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS Tabla_Consultar FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
	
	
	$consulta_tabla=$tipo_tabla[0]["Tabla_Consultar"];
	$consulta_nombre=$tipo_tabla[0]["Tipo_Entidad"];
	
	
	
	$tipo_clientes = $database->query("SELECT $consulta_tabla.ID from $consulta_tabla WHERE ID = $id_registro ")->fetchAll(PDO::FETCH_ASSOC);

	
	$expediente_documento = $database->query("SELECT EX_TIPO_DOCUMENTO.NOMBRE AS Nombre_documento,
	EX_TIPO_EXPEDIENTE.NOMBRE AS Nombre_expediente, 
	EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE, EX_TIPO_EXPEDIENTE.NOMBRE 
	FROM EX_TIPO_DOCUMENTO, EX_EXPEDIENTE_DOCUMENTO, EX_TIPO_EXPEDIENTE, 
	EX_EXPEDIENTE_ENTIDAD 
	WHERE EX_TIPO_DOCUMENTO.ID = EX_EXPEDIENTE_DOCUMENTO.ID_DOCUMENTO AND  
	EX_TIPO_EXPEDIENTE.ID = EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE AND 
	EX_EXPEDIENTE_DOCUMENTO.ID_EXPEDIENTE = EX_TIPO_EXPEDIENTE.ID AND
	EX_EXPEDIENTE_ENTIDAD.TIPO=1  AND 
	EX_EXPEDIENTE_ENTIDAD.ID_ENTIDAD=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
	
	$entidad=$tipo_tabla[0]["Tipo_Entidad"];
	
	for($k = 0 ; $k < count($tipo_clientes); $k++){
		$cliente=$tipo_clientes[$k]["ID"];
		
		for($l =0 ; $l < count($expediente_documento); $l++){
			$documento=$expediente_documento[$l]["Nombre_documento"];
			$expediente=$expediente_documento[$l]["Nombre_expediente"];
				
			$direc = $rutaExpediente."/".$consulta_nombre."/".$cliente."/".$expediente."/".$documento."/";
			$direc = str_replace(" ","_",$direc);
			if (file_exists($direc)) {
    				//echo "El fichero $direc existe";
			}
			else{			
				mkdir($direc, 0777, true);					
			}	
		}
	}
}


function crea_instancia_expedientes_registro($id_registro,$entidad,$database){
	$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS Tabla_Consultar FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
	$consulta_tabla=$tipo_tabla[0]["Tabla_Consultar"];
	
	$tipo_clientes = $database->query("SELECT $consulta_tabla.ID from $consulta_tabla WHERE ID = $id_registro ")->fetchAll(PDO::FETCH_ASSOC);
	
	$query_expedientes = "SELECT EEE.ID AS ID_EXP_ENTIDAD, ETE.NOMBRE,ETE.ID AS ID_EXPEDIENTE
	FROM EX_EXPEDIENTE_ENTIDAD AS EEE, EX_TIPO_EXPEDIENTE AS ETE
	WHERE EEE.ID_TIPO_EXPEDIENTE = ETE.ID AND
	EEE.ID_ENTIDAD = ".$database->quote($entidad);
	$expedientes = $database->query($query_expedientes)->fetchAll(PDO::FETCH_ASSOC);
	
	for($k = 0 ; $k < count($expedientes); $k++){
		$expediente = $expedientes[$k]["NOMBRE"]; 
		$id_expediente = $expedientes[$k]["ID_EXPEDIENTE"];
		$id_expediente_entidad = $expedientes[$k]["ID_EXP_ENTIDAD"];
		
		$ultimo_id = $database->count("EX_REGISTRO_EXPEDIENTE","ID");
		$cliente=$tipo_clientes[$k]["ID"];	
			
		$ultimo_inserted = $database->insert("EX_REGISTRO_EXPEDIENTE", 
			["ID" => $ultimo_id + 1,
			"ID_REGISTRO" => $cliente,
			"ID_EXPEDIENTE_ENTIDAD" => $id_expediente_entidad,
			"FECHA_CREACION" => date("Y-m-d H:i:s"), 
			"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
			"USUARIO_CREACION" => 0,
			"USUARIO_MODIFICACION" => 0
			]);
		$tipo_documentos =$database->query("SELECT ID from EX_EXPEDIENTE_DOCUMENTO
	WHERE ID_EXPEDIENTE = ".$database->quote($id_expediente))->fetchAll(PDO::FETCH_ASSOC);

		for($j = 0 ; $j < count($tipo_documentos);$j++){
			$ultimo_id_archivo = $database->count("EX_ARCHIVO_EXPEDIENTE","ID");

			$ultimo_inserted = $database->insert("EX_ARCHIVO_EXPEDIENTE", 
			["ID" => $ultimo_id_archivo + 1,
			"ID_EXPEDIENTE_DOCUMENTO" => $tipo_documentos[$j]["ID"],
			"ID_REGISTRO_EXPEDIENTE" => $ultimo_id + 1,
			"FECHA_CREACION" => date("Y-m-d H:i:s"), 
			"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
			"USUARIO_CREACION" => 0,
			"USUARIO_MODIFICACION" => 0
			]);
		}
		
	}
	
	
	
	
	
}

?>