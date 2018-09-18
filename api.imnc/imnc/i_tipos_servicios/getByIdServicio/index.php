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
		$mailerror->send("I_TIPOS_SERVICIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$meta_sce = $database->select("I_TIPOS_SERVICIOS",
											["[><]I_META_SCE"=>["I_TIPOS_SERVICIOS.ID_META_SCE"=>"ID"]],
										
											["I_TIPOS_SERVICIOS.ID_SERVICIO_CLIENTE_ETAPA","I_TIPOS_SERVICIOS.ID_META_SCE","I_TIPOS_SERVICIOS.VALOR","I_META_SCE.NOMBRE(NOMBRE_META_SCE)","I_META_SCE.TIPO(TIPO_META_SCE)"],
											["ID_SERVICIO_CLIENTE_ETAPA"=>$id]); 
valida_error_medoo_and_die(); 
print_r(json_encode($meta_sce)); 
?> 
