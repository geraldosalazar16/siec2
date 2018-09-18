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
		$mailerror->send("TAREAS_DOCUMENTO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"];
$id_documento =  $_REQUEST["id_documento"];
$ciclo	=	$_REQUEST["ciclo"];
/*
$consulta = "SELECT T.ID AS ID,CT.NOMBRE_TAREA AS NOMBRE_TAREA,CT.ID AS ID_TAREA,T.FECHA_INICIO AS FECHA_INICIO,T.FECHA_FIN AS FECHA_FIN FROM TAREAS_SERVICIOS_CONTRATADOS T INNER JOIN CAT_TAREAS_SERVICIOS_CONTRATADOS CT ON T.ID_TAREA = CT.ID WHERE T.ID_SERVICIO = ".$id;
$tareas = $database->query($consulta)->fetchAll();*/

$tareas = $database->select("TAREAS_DOCUMENTO","*",["AND"=>["ID_CATALOGO_DOCUMENTOS"=>$id_documento,"ID_SERVICIO"=>$id,"CICLO"=>$ciclo]]);

valida_error_medoo_and_die(); 
print_r(json_encode($tareas)); 
?> 
