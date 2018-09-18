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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$ID_CLIENTE_DOMICILIO = $objeto->ID_CLIENTE_DOMICILIO; 
valida_parametro_and_die($ID_CLIENTE_DOMICILIO, "Falta el ID_CLIENTE_DOMICILIO");
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
$id = $database->insert("I_SG_AUDITORIA_SITIOS",								[
	"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
	"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
	"CICLO"=>$CICLO,
	"ID_CLIENTE_DOMICILIO"=>$ID_CLIENTE_DOMICILIO,
	"ID_USUARIO_CREACION"=>$ID_USUARIO,
	"FECHA_CREACION"=>$FECHA_CREACION,
	"HORA_CREACION"=>$HORA_CREACION
	]); 
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 
?> 
