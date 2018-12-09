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

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario introducir un nombre de curso");

$FECHAS = $objeto->FECHAS;
valida_parametro_and_die($FECHAS, "Es necesario seleccionar las fechas");

$ID_INSTRUCTOR	= $objeto->ID_INSTRUCTOR;
valida_parametro_and_die($ID_INSTRUCTOR, "Es necesario seleccionar un instructor");

$PERSONAS_MINIMO	= $objeto->PERSONAS_MINIMO;
valida_parametro_and_die($PERSONAS_MINIMO, "Es introducir un número mínimo de personas");


$id_sce = $database->insert("CURSOS_PROGRAMADOS", [
	"ID_CURSO" => $ID_CURSO,
	"FECHAS"=>	$FECHAS,
	"ID_INSTRUCTOR" => $ID_INSTRUCTOR,
	"PERSONAS_MINIMO" => $PERSONAS_MINIMO
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
