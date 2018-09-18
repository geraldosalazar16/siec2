<?php

	include  '../common/conn-apiserver.php'; 
	include  '../common/conn-medoo.php'; 
	include  '../common/conn-sendgrid.php'; 
	include  'ex_valida.php'; 

	
	function encriptar($cadena){
		$key='EXPEDIENTEDHT2016CMLJJJAI';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		return $encrypted; //Devuelve el string encriptado
	}
 
	function desencriptar($cadena){
		 $key='EXPEDIENTEDHT2016CMLJJJAI';	 
		 $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		return $decrypted;  //Devuelve el string desencriptado
	}

	function getDirectoryByIdArchivo($id_archivo_expediente,$database){
		$query = "SELECT EEE.ID_ENTIDAD,EEE.TIPO,ERE.ID_REGISTRO,
		EAD.NOMBRE_ARCHIVO AS NOMBREARCHIVO,ETE.NOMBRE AS NOMBREEXPEDIENTE,
		ETD.NOMBRE AS NOMBREDOCUMENTO 
		FROM EX_ARCHIVO_DOCUMENTO as EAD, EX_ARCHIVO_EXPEDIENTE AS EAE,
		EX_EXPEDIENTE_DOCUMENTO as EED, EX_TIPO_DOCUMENTO as ETD,
		EX_TIPO_EXPEDIENTE as ETE, EX_EXPEDIENTE_ENTIDAD as EEE,
		EX_REGISTRO_EXPEDIENTE as ERE,  EX_TABLA_ENTIDADES AS ETENT
		WHERE EAD.ID_ARCHIVO_EXPEDIENTE = EAE.ID AND 
		EAE.ID_EXPEDIENTE_DOCUMENTO = EED.ID AND 
		EED.ID_DOCUMENTO = ETD.ID AND EED.ID_EXPEDIENTE = ETE.ID AND 
		EAE.ID_REGISTRO_EXPEDIENTE = ERE.ID AND 
		ERE.ID_EXPEDIENTE_ENTIDAD = EEE.ID AND
		EEE.ID_ENTIDAD = ETENT.ID AND EAD.ID = ".$database->quote($id_archivo_expediente);
		$res = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die("EX_ARCHIVO_DOCUMENTO", "lqc347@gmail.com");
		$tipo = $res[0]["TIPO"];
		$id_entidad = $res[0]["ID_ENTIDAD"];
		$id_registro = $res[0]["ID_REGISTRO"];
		$expediente = $res[0]["NOMBREEXPEDIENTE"];
		$documento = $res[0]["NOMBREDOCUMENTO"];
		$archivo = $res[0]["NOMBREARCHIVO"];
		
		if($tipo == 1){
			$query2= "SELECT * FROM EX_TABLA_ENTIDADES WHERE ID = ".$database->quote($id_entidad);
		}else{
			//esto cambiara para los trámites
			$query2= "SELECT * FROM EX_TABLA_ENTIDADES WHERE ID = ".$database->quote($id_entidad);
		}
		$res2 = $database->query($query2)->fetchAll(PDO::FETCH_ASSOC);
		$tabla_entidad = $res2[0]["TABLA"];
		$entidad = $res2[0]["DESCRIPCION"];
		if(!is_numeric($id_registro))
			return false;
		
		$query3 = "SELECT * FROM $tabla_entidad WHERE ID = ".$id_registro;
		$res3 = $database->query($query3)->fetchAll(PDO::FETCH_ASSOC);
		//$rfc = $res3[0]["RFC"];
		$direc = "/ArchivoExpediente/".$entidad."/".$id_registro."/".$expediente."/".$documento."/".$archivo;

		$direc = str_replace(" ","_",$direc);
		return $direc;
	}


	$codigo = $_REQUEST['codigo'];
	$dese = desencriptar($codigo);
	$archivo = getDirectoryByIdArchivo($dese,$database);
	echo($archivo);
?>