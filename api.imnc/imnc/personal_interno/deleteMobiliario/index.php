<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta));
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$NO_EMPLEADO = $objeto->NO_EMPLEADO;
valida_parametro_and_die($NO_EMPLEADO, "Es necesario un ID para poder eliminar");



$id_cp = $database->delete("PERSONAL_INTERNO", ["NO_EMPLEADO"=>$NO_EMPLEADO]);
valida_error_medoo_and_die();



$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
