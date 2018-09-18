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
		$mailerror->send("COTIZACION_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta = array();

$ID = $_REQUEST["id"]; 
$SELECCIONADO = intval($database->get("COTIZACION_SITIOS", "SELECCIONADO", ["ID"=>$ID]));
$SELECCIONADO = abs($SELECCIONADO-1);

$database->update("COTIZACION_SITIOS", ["SELECCIONADO"=>$SELECCIONADO], ["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"] = "ok";
$respuesta["seleccionado"] = $SELECCIONADO;
print_r(json_encode($respuesta)); 
?> 
