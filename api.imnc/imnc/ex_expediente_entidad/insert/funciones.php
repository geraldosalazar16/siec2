<?php

function creacion_expediente($expediente, $entidad,$rutaExpediente, $database){
	
	if($entidad != 4 && $entidad != 5){
		
		
		$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS Tabla_Consultar FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die("EX_EXPEDIENTE_ENTIDAD", "lqc347@gmail.com");
		$consulta_tabla=$tipo_tabla[0]["Tabla_Consultar"];
		$consulta_nombre=$tipo_tabla[0]["Tipo_Entidad"];

		$tipo_clientes = $database->query("SELECT $consulta_tabla.RFC AS NOMBRE_RFC, $consulta_tabla.ID from $consulta_tabla")->fetchAll(PDO::FETCH_ASSOC);

		$expediente_documento = $database->query("SELECT EX_TIPO_DOCUMENTO.NOMBRE AS NOMBRE_DOCUMENTO,
		EX_TIPO_EXPEDIENTE.NOMBRE AS NOMBRE_EXPEDIENTE, 
		EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE, EX_TIPO_EXPEDIENTE.NOMBRE 
		FROM EX_TIPO_DOCUMENTO, EX_EXPEDIENTE_DOCUMENTO, EX_TIPO_EXPEDIENTE, 
		EX_EXPEDIENTE_ENTIDAD 
		WHERE EX_TIPO_DOCUMENTO.ID = EX_EXPEDIENTE_DOCUMENTO.ID_DOCUMENTO AND  
		EX_TIPO_EXPEDIENTE.ID = EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE AND 
		EX_EXPEDIENTE_ENTIDAD.TIPO=1  AND 
		EX_EXPEDIENTE_DOCUMENTO.ID_EXPEDIENTE = EX_TIPO_EXPEDIENTE.ID AND
		EX_EXPEDIENTE_ENTIDAD.ID_ENTIDAD=".$database->quote($entidad)." and 
		EX_EXPEDIENTE_DOCUMENTO.ID_EXPEDIENTE =".$database->quote($expediente))->fetchAll(PDO::FETCH_ASSOC);
		

		valida_error_medoo_and_die("EX_TIPO_DOCUMENTO", "lqc347@gmail.com");
		for($k = 0 ; $k < count($tipo_clientes); $k++){
			$cliente=$tipo_clientes[$k]["ID"];

			for($l =0 ; $l < count($expediente_documento); $l++){
				$documento=$expediente_documento[$l]["NOMBRE_DOCUMENTO"];
				$expediente=$expediente_documento[$l]["NOMBRE_EXPEDIENTE"];

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
	}else{
		$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS Tabla_Consultar FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die("EX_EXPEDIENTE_ENTIDAD", "lqc347@gmail.com");
		$consulta_tabla=$tipo_tabla[0]["Tabla_Consultar"];
		$consulta_nombre=$tipo_tabla[0]["Tipo_Entidad"];

		$tipo_archivero = $database->query("SELECT $consulta_tabla.ID from $consulta_tabla")->fetchAll(PDO::FETCH_ASSOC);

		$expediente_documento = $database->query("SELECT EX_TIPO_DOCUMENTO.NOMBRE AS NOMBRE_DOCUMENTO,
		EX_TIPO_EXPEDIENTE.NOMBRE AS NOMBRE_EXPEDIENTE, 
		EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE, EX_TIPO_EXPEDIENTE.NOMBRE 
		FROM EX_TIPO_DOCUMENTO, EX_EXPEDIENTE_DOCUMENTO, EX_TIPO_EXPEDIENTE, 
		EX_EXPEDIENTE_ENTIDAD 
		WHERE EX_TIPO_DOCUMENTO.ID = EX_EXPEDIENTE_DOCUMENTO.ID_DOCUMENTO AND  
		EX_TIPO_EXPEDIENTE.ID = EX_EXPEDIENTE_ENTIDAD.ID_TIPO_EXPEDIENTE AND 
		EX_EXPEDIENTE_ENTIDAD.TIPO=1  AND 
		EX_EXPEDIENTE_DOCUMENTO.ID_EXPEDIENTE = EX_TIPO_EXPEDIENTE.ID AND
		EX_EXPEDIENTE_ENTIDAD.ID_ENTIDAD=".$database->quote($entidad)." and 
		EX_EXPEDIENTE_DOCUMENTO.ID_EXPEDIENTE =".$database->quote($expediente))->fetchAll(PDO::FETCH_ASSOC);
		
		
		valida_error_medoo_and_die("EX_TIPO_DOCUMENTO", "lqc347@gmail.com");
		for($k = 0 ; $k < count($tipo_archivero); $k++){
			
			$archivero=$tipo_archivero[$k]["ID"];

			for($l =0 ; $l < count($expediente_documento); $l++){
				$documento=$expediente_documento[$l]["NOMBRE_DOCUMENTO"];
				$expediente=$expediente_documento[$l]["NOMBRE_EXPEDIENTE"];

				$direc = $rutaExpediente."/".$consulta_nombre."/".$archivero."/".$expediente."/".$documento."/";
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
}

function crea_instancias_expedientes($id_expediente_entidad,$entidad,$ID_TIPO_EXPEDIENTE,$database){

	$tipo_tabla = $database->query("SELECT EX_TABLA_ENTIDADES.ID, EX_TABLA_ENTIDADES.DESCRIPCION AS Tipo_Entidad, EX_TABLA_ENTIDADES.TABLA AS TABLA_CONSULTAR FROM EX_TABLA_ENTIDADES where EX_TABLA_ENTIDADES.ID=".$database->quote($entidad))->fetchAll(PDO::FETCH_ASSOC);
	$consulta_tabla=$tipo_tabla[0]["TABLA_CONSULTAR"];
	$tipo_clientes = $database->query("SELECT $consulta_tabla.ID from $consulta_tabla")->fetchAll(PDO::FETCH_ASSOC);
	$tipo_documentos =$database->query("SELECT ID from EX_EXPEDIENTE_DOCUMENTO
	WHERE ID_EXPEDIENTE = ".$database->quote($ID_TIPO_EXPEDIENTE))->fetchAll(PDO::FETCH_ASSOC);
	for($k = 0 ; $k < count($tipo_clientes); $k++){
		$ultimo_id = $database->max("EX_REGISTRO_EXPEDIENTE","ID");
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
		
		
		for($j = 0 ; $j < count($tipo_documentos);$j++){
			$ultimo_id_archivo = $database->max("EX_ARCHIVO_EXPEDIENTE","ID");		
			
			$ultimo_inserted = $database->insert("EX_ARCHIVO_EXPEDIENTE", 
			["ID" => $ultimo_id_archivo + 1,
			"ID_EXPEDIENTE_DOCUMENTO" => $tipo_documentos[$j]["ID"],
			"ID_REGISTRO_EXPEDIENTE" => $ultimo_id + 1,
			"FECHA_CREACION" => date("Y-m-d H:i:s"), 
			"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
			"USUARIO_CREACION" => 0,
			"USUARIO_MODIFICACION" => 0
			]);
			valida_error_medoo_and_die("EX_ARCHIVO_EXPEDIENTE", "lqc347@gmail.com");
		}
		
			
	}
}

?>