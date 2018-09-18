<?php
include  '../../ex_common/query.php'; 
include  '../../ex_common/archivos.php';
	function getDirectoryByIdArchivo($id_archivo_expediente,$database){
		global $rutaExpediente;
		$query = "SELECT EEE.ID_ENTIDAD,EEE.TIPO,ERE.ID_REGISTRO,
		ETE.NOMBRE AS NOMBREEXPEDIENTE,ETD.NOMBRE AS NOMBREDOCUMENTO 
		FROM EX_ARCHIVO_DOCUMENTO as EAD, EX_ARCHIVO_EXPEDIENTE AS EAE,
		EX_EXPEDIENTE_DOCUMENTO as EED, EX_TIPO_DOCUMENTO as ETD,
		EX_TIPO_EXPEDIENTE as ETE, EX_EXPEDIENTE_ENTIDAD as EEE,
		EX_REGISTRO_EXPEDIENTE as ERE,  EX_TABLA_ENTIDADES AS ETENT
		WHERE EAD.ID_ARCHIVO_EXPEDIENTE = EAE.ID AND 
		EAE.ID_EXPEDIENTE_DOCUMENTO = EED.ID AND 
		EED.ID_DOCUMENTO = ETD.ID AND EED.ID_EXPEDIENTE = ETE.ID AND 
		EAE.ID_REGISTRO_EXPEDIENTE = ERE.ID AND 
		ERE.ID_EXPEDIENTE_ENTIDAD = EEE.ID AND
		EEE.ID_ENTIDAD = ETENT.ID AND EAE.ID = ".$database->quote($id_archivo_expediente);
		$res = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
		$tipo = $res[0]["TIPO"];
		$id_entidad = $res[0]["ID_ENTIDAD"];
		$id_registro = $res[0]["ID_REGISTRO"];
		$expediente = $res[0]["NOMBREEXPEDIENTE"];
		$documento = $res[0]["NOMBREDOCUMENTO"];
		if($tipo == 1){
			
			$query2= "SELECT * FROM EX_TABLA_ENTIDADES WHERE ID = ".$database->quote($id_entidad);
		}else{
			//esto cambiara para los trámites
			$query2= "SELECT * FROM EX_TABLA_ENTIDADES WHERE ID = ".$database->quote($id_entidad);
		}
		$res2 = $database->query($query2)->fetchAll(PDO::FETCH_ASSOC);
		$tabla_entidad = $res2[0]["TABLA"];
		$entidad = $res2[0]["DESCRIPCION"];
		if($tipo == 1 && ($id_entidad == 4 || $id_entidad == 5)){
			$direc = $rutaExpediente."/".$entidad."/".$id_registro."/".$expediente."/".$documento;
		}else{
			$query3 = "SELECT * FROM $tabla_entidad WHERE ID = ".$id_registro;
			$res3 = $database->query($query3)->fetchAll(PDO::FETCH_ASSOC);
			$rfc = $res3[0]["RFC"];
			$direc = $rutaExpediente."/".$entidad."/".$id_registro."/".$expediente."/".$documento;
		}
		$direc = str_replace(" ","_",$direc);
		return $direc;
	}
	
	
	
	foreach ($_FILES as $key => $value) {
		$output_dir = getDirectoryByIdArchivo($key,$database)."/";
		$fileName = $value["name"];
		$fileName = str_replace(" ","_",$fileName);
		move_uploaded_file($value["tmp_name"], $output_dir.$fileName);
	}
	
?>