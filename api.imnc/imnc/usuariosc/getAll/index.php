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
		$mailerror->send("USUARIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$usuarios = $database->select("USUARIOS", "*"); 
valida_error_medoo_and_die(); 
foreach(array_keys($usuarios) as $key) {
   unset($usuarios[$key]["PASSWORD"]);

   $perfil = $database->get("TIPOS_PERFILES", "PERFIL", ["ID"=>$usuarios[$key]["ID_PERFIL"]]); 
   $usuarios[$key]["PERFIL"] = $perfil;
}

print_r(json_encode($usuarios)); 
?> 
