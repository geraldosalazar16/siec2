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

$output_dir = "./uploads/";

$paso_proceso = $_POST['paso_proceso'];
$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];

if(isset($_FILES["myfile"]))
{
	$respuesta = array();
	
	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
		// Guardar archivo 

 	 	$fileName = $_FILES["myfile"]["name"];
 	 	$fileType = pathinfo($fileName,PATHINFO_EXTENSION);
 	 	if ($fileType != "pdf") {
    		$respuesta["resultado"]= "error";
 			$respuesta['mensaje']="El archivo debe ser PDF";
 			echo json_encode($respuesta);
    		die();
    	}
 	 	$newFileName = hash("md5", $fileName.date("Ymd").date("His")) . ".pdf";
 		$moved = move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$newFileName);
 		$respuesta["resultado"]= "ok";
 		$respuesta['mensaje']="Carga exitosa";
    	$respuesta["filename"]= $newFileName;
    	$respuesta["moved"]= $moved;
    	$respuesta["paso_proceso"]= $paso_proceso;
    	$respuesta["fileType"]= $fileType;
    	if (!$moved) {
    		$respuesta["resultado"]= "error";
 			$respuesta['mensaje']="No se pudo guardar el archivo";
 			echo json_encode($respuesta);
    		die();
    	}
    	echo json_encode($respuesta);
    	die();
    	

		
		// Conexión a BD
		//$id = $database->update("PERSONAL_TECNICO", ["IMAGEN_BASE64" => $base64], ["ID"=>$id_personal_tecnico]);
		//valida_error_medoo_and_die();


    	
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
	  	$respuesta[]= $fileName;
	  }
	
	}
    
 }



?>