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
		$mailerror->send("I_EC_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$meta_sce = $database->select("I_EC_SITIOS",
											["[><]I_META_SITIOS"=>["I_EC_SITIOS.ID_META_SITIOS"=>"ID"]],
										
											["I_EC_SITIOS.ID_SERVICIO_CLIENTE_ETAPA","I_EC_SITIOS.ID_META_SITIOS","I_EC_SITIOS.ID_CLIENTE_DOMICILIO","I_EC_SITIOS.VALOR","I_META_SITIOS.NOMBRE(NOMBRE_META_SITIOS)","I_META_SITIOS.TIPO(TIPO_META_SITIOS)"],
											["ID_SERVICIO_CLIENTE_ETAPA"=>$id]);
											


valida_error_medoo_and_die(); 

print_r(json_encode($meta_sce)); 
?> 
