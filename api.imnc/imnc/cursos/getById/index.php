<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
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
		$mailerror->send("CURSO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 
$curso = $database->get("CURSOS", "*", ["ID_CURSO"=>$id]); 


$norma = $database->get("NORMAS", "NOMBRE", ["ID"=>$curso["ID_NORMA"]]);
		valida_error_medoo_and_die();
		$curso["NOMBRE_NORMA"] = $norma;

$tipo= $database->get("TIPOS_SERVICIO", "NOMBRE", ["ID"=>$curso["ID_TIPO_SERVICIO"]]);
		valida_error_medoo_and_die();
		$curso["NOMBRE_TIPO_SEVICIO"] = $tipo;		


print_r(json_encode($curso, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); 
?> 
