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
		$mailerror->send("TIPOS_PERFILES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id = $_REQUEST["id"]; 
$tipos_perfiles = $database->get("TIPOS_PERFILES", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 
$permisos = explode(",", $tipos_perfiles["PERMISOS"]);

$tipos_permisos = $database->select("TIPOS_PERMISOS", "*", ["ORDER"=>"MODULO"]);
for ($i=0; $i < count($tipos_permisos); $i++) { 
	if (in_array($tipos_permisos[$i]["PERMISO"], $permisos)) {
		$tipos_permisos[$i]["SELECCIONADO"] = 1;
	}
	else{
	$tipos_permisos[$i]["SELECCIONADO"] = 0;		
	}
} 
$tipos_perfiles["PERMISOS"] = $tipos_permisos;

print_r(json_encode($tipos_perfiles)); 
?> 
