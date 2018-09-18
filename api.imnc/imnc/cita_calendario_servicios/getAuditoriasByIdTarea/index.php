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
		$mailerror->send("TAREAS_SERVICIOS_CONTRATADOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$ID_SERV_CLI_ET = $database->get("TAREAS_SERVICIOS_CONTRATADOS","ID_SERVICIO",["ID" => $id]);
$ID_TAREA = $database->get("TAREAS_SERVICIOS_CONTRATADOS","ID_TAREA",["ID" => $id]);
 
$TIPO_AUDITORIA = $database->get("CAT_TAREAS_SERVICIOS_CONTRATADOS","ID_AUDITORIA",["ID" => $ID_TAREA]);

valida_error_medoo_and_die(); 
//Tomar id de sg_tipos_servicio
$ID_SG_TIPOS_SERVICIO = $database->get("SG_TIPOS_SERVICIO","ID",["ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERV_CLI_ET]);
valida_error_medoo_and_die(); 
//TOMAR AUDITORIAS CON ID_SG_TIPOS_SERVICIO
/*
$AUDITORIAS = $database->select("SG_AUDITORIAS","*",["AND"=>["TIPO_AUDITORIA" => $TIPO_AUDITORIA,"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPOS_SERVICIO]]);
//BUSCAR NOMBRE DE AUDITORIA EN SG_AUDITORIA_TIPOS
$NOMBRE_AUDITORIA = $database->get("SG_AUDITORIA_TIPOS","TIPO",["ID" => $TIPO_AUDITORIA]);
valida_error_medoo_and_die(); 
*/
$AUDITORIAS = $database->query("SELECT SG_AUDITORIAS.ID,SG_AUDITORIAS_TIPOS.TIPO FROM SG_AUDITORIAS INNER JOIN SG_AUDITORIAS_TIPOS ON SG_AUDITORIAS.TIPO_AUDITORIA = SG_AUDITORIAS_TIPOS.ID WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = ".$ID_SG_TIPOS_SERVICIO." AND SG_AUDITORIAS.TIPO_AUDITORIA = '".$TIPO_AUDITORIA."'")->fetchAll();

print_r(json_encode($AUDITORIAS)); 
?> 
