<?php

	include  '../common/conn-apiserver.php'; 
	include  '../common/conn-medoo.php'; 
	include  '../common/conn-sendgrid.php'; 
	include  'ex_valida.php'; 
	include  'archivos.php';
	/*$file = "cabios.xlsx";
if($file == "cabios.xlsx"){

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        
readfile($file);
exit();
}*/
	
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

	function getDirectoryByIdArchivo($id_archivo_cita,$database){
		$query = "SELECT PCA.ID_CITA_HISTORIAL as ID_ARCHIVO,PC.ID as ID_CITA,PC.ID_PROSPECTO,PCA.ID,PCA.NOMBRE_ARCHIVO FROM PROSPECTO_CITAS AS PC,PROSPECTO_CITA_HISTORIAL AS PCH,PROSPECTO_CITAS_ARCHIVOS AS PCA WHERE PC.ID = PCH.ID_CITA AND PCH.ID = PCA.ID_CITA_HISTORIAL AND PCA.ID = ".$database ->quote ($id_archivo_cita)." ORDER BY PC.ID DESC";
		$res = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);

		valida_error_medoo_and_die("PROSPECTO_CITAS_ARCHIVOS", "lqc347@gmail.com");
		$archivo = $res[0]["NOMBRE_ARCHIVO"];
		$id_cita = $res[0]["ID_CITA"];
		$id_cita_archivo = $res[0]["ID_ARCHIVO"];

		$res2 = $database->get("PROSPECTO_CITAS", "*", ["ID"=> $id_cita] );
		$id_prospecto = $res2["ID_PROSPECTO"];
		if(!is_numeric($id_prospecto))
			return false;

		$direc = "/ArchivoExpediente/Prospecto/".$id_prospecto."/Citas/cita_".$id_cita_archivo."/".$archivo;
		
		$direc = str_replace(" ","_",$direc);
		
		return $direc;
	}
	
	$codigo = $_REQUEST['codigo'];
	//echo $codigo;
	$dese = desencriptar($codigo);
	//echo $dese;
	$archivo = getDirectoryByIdArchivo($dese,$database);
	echo($archivo);
?>