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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("TIPOS_PERFILES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$tipos_perfiles = $database->select("TIPOS_PERFILES", "*"); 
valida_error_medoo_and_die(); 

for ($i=0; $i < count($tipos_perfiles) ; $i++) { 
	$tipos_perfiles[$i]["PERMISOS_DESPLIGUE_WEB"] = str_replace(",", ", ", $tipos_perfiles[$i]["PERMISOS"]);
}

print_r(json_encode($tipos_perfiles)); 

?> 
