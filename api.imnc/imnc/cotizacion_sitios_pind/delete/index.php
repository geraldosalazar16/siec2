<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("COTIZACION_SITIOS_PIND", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$ID = $_REQUEST["id"]; 
$datos =$database->get("COTIZACION_SITIOS_PIND", "*", ["ID"=>$ID]);
valida_error_medoo_and_die();
//Borro primero los productos de la tabla asociada PROD_IND_SITIO
$id = $database->delete("PROD_IND_SITIO", ["AND"=>["ID_SITIO_PIND"=>$ID,"ID_TRAMITE"=>$datos["ID_COTIZACION"]]]); 
valida_error_medoo_and_die(); 
$id = $database->delete("COTIZACION_SITIOS_PIND", ["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
