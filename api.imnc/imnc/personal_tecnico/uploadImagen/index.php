<?php 
include  '../../common/conn-apiserver.php'; 

include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 




function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$output_dir = "./";
$id_personal_tecnico = $_POST['id_personal_tecnico'];

if(isset($_FILES["myfile"]))
{
	$ret = array();
	
//	This is for custom errors;	
/*	$custom_error= array();
	$custom_error['jquery-upload-file-error']="File already exists";
	echo json_encode($custom_error);
	die();
*/
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 	 	$newFileName = hash("md5", $fileName.date("Ymd").date("His"));
 		//$moved = move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$newFileName);
 		$ret["resultado"]= "ok";
 		$ret['mensaje']="Carga exitosa";
    	//$ret["filename"]= $newFileName;
    	$path = $_FILES["myfile"]["tmp_name"];
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/jpg;base64,' . base64_encode($data);
		$id = $database->update("PERSONAL_TECNICO", ["IMAGEN_BASE64" => $base64], ["ID"=>$id_personal_tecnico]);
		valida_error_medoo_and_die();
		unlink($path);
		//$id = $database->update("PERSONAL_TECNICO", ["RUTA_IMAGEN" => $newFileName], ["ID"=>$id_personal_tecnico]);
		//valida_error_medoo_and_die();
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


	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    
 }



?>