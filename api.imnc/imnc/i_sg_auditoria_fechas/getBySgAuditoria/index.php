<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
//
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
		$mailerror->send("I_SG_AUDITORIA_FECHAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$ID_SERVICIO_CLIENTE_ETAPA = $_REQUEST["id"]; 
$ID_TIPO_AUDITORIA = $_REQUEST["id_tipo_auditoria"]; 

$SG_AUDITORIA_FECHAS = $database->query("SELECT `I_SG_AUDITORIA_FECHAS`.`ID`,`I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_FECHAS`.`FECHA` FROM `I_SG_AUDITORIA_FECHAS` WHERE `I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$ID_SERVICIO_CLIENTE_ETAPA. " AND `I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`=".$ID_TIPO_AUDITORIA." ORDER BY `I_SG_AUDITORIA_FECHAS`.`FECHA`")->fetchAll(PDO::FETCH_ASSOC);


print_r(json_encode($SG_AUDITORIA_FECHAS)); 
?> 
