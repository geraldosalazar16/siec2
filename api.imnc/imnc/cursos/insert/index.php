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

$NOMBRE = $objeto->NOMBRE; 
valida_parametro_and_die($NOMBRE, "Es necesario introducir un nombre de curso");

$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un módulo");

$ID_NORMA	= $objeto->ID_NORMA; 
valida_parametro_and_die($ID_NORMA, "Es necesario seleccionar una norma");

$ISACTIVO = $objeto->ISACTIVO;



$id_sce = $database->insert("CURSOS", [ 
	"NOMBRE" => $NOMBRE, 
	"ID_TIPO_SERVICIO"=>	$ID_TIPO_SERVICIO,
	"ID_NORMA" => $ID_NORMA ,
	"ISACTIVO" => $ISACTIVO
//	
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
