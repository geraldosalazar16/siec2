<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


$output_dir = "../";
//$id_personal_tecnico = $_POST['id_personal_tecnico'];

if(isset($_FILES["archivo_certificado"]))
{
	$ret = array();
	

	$error = $_FILES["archivo_certificado"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["archivo_certificado"]["name"])) //single file
	{
		$path = $_FILES["archivo_certificado"]["tmp_name"];
 	 	$fileName = $_FILES["archivo_certificado"]["name"];
 	 	$newFileName = hash("md5", $fileName.date("Ymd").date("His")) . "." . pathinfo($fileName, PATHINFO_EXTENSION);
 		$moved = move_uploaded_file($path, $output_dir.$newFileName);

 		$ret["resultado"]= "ok";
 		$ret['mensaje']="Carga exitosa";
    	$ret["nombre_archivo"]= $newFileName;

    	echo json_encode($ret);
    	die();
	}
	else  //Multiple files, file[]
	{
		$custom_error= array();
		$custom_error['resultado']="error";
		$custom_error['mensaje']="Por el momento no se acepta la carga de multiples archivos";
		echo json_encode($custom_error);
		die(); // Por el momento no se acepta la carga de multiples archivos
	}
    
 }



?>