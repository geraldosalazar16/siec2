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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id_servicio = $_REQUEST["id"]; 
$nombre_etapa = $_REQUEST["nombre_etapa"]; 
$nombre_seccion = $_REQUEST["nombre_seccion"]; 
$ciclo = $_REQUEST["nombre_ciclo"]; 

$estado_seccion = $database->get("ESTADO_SECCIONES", "ESTADO_SECCION", ["AND"=>["ETAPA"=>$nombre_etapa,"SECCION"=>$nombre_seccion,"ID_SERVICIO"=>$id_servicio,"CICLO"=>$ciclo]]);

valida_error_medoo_and_die(); 
print_r(json_encode($estado_seccion)); 
?> 
