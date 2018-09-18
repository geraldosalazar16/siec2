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
		$mailerror->send("TAREAS_SERVICIOS_CONTRATADOS", getcwd(), $database->error()[2], $database->last_query(), "geraldosalazar16@gmail.com"); 
		die(); 
	} 
} 
$strQuery = "select T.ID AS ID,T.FECHA_INICIO AS FECHA_INICIO, T.FECHA_FIN AS FECHA_FIN,T.HORA_INICIO AS HORA_INICIO, T.HORA_FIN AS HORA_FIN,CT.NOMBRE_TAREA AS NOMBRE_TAREA,C.NOMBRE AS CLIENTE,SCE.ID_SERVICIO AS ID_SERVICIO FROM TAREAS_SERVICIOS_CONTRATADOS T INNER JOIN CAT_TAREAS_SERVICIOS_CONTRATADOS CT ON T.ID_TAREA = CT.ID INNER JOIN SERVICIO_CLIENTE_ETAPA SCE ON T.ID_SERVICIO = SCE.ID INNER JOIN CLIENTES C ON SCE.ID_CLIENTE = C.ID";

$arreglo_tareas = $database->query($strQuery)->fetchAll();

valida_error_medoo_and_die(); 
print_r(json_encode($arreglo_tareas)); 
?> 
