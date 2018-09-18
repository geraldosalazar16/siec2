<?php 

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=Reporte_tareas_programadas.csv");

include  '../../../../api.imnc/imnc/common/conn-apiserver.php'; 
include  '../../../../api.imnc/imnc/common/conn-medoo.php'; 
include  '../../../../api.imnc/imnc/common/conn-sendgrid.php'; 
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
$strQuery = "select T.ID AS ID,T.FECHA_INICIO AS FECHA_INICIO, T.FECHA_FIN AS FECHA_FIN,T.HORA_INICIO AS HORA_INICIO, T.HORA_FIN AS HORA_FIN,CT.NOMBRE_TAREA AS NOMBRE_TAREA,C.NOMBRE AS CLIENTE,SCE.ID_SERVICIO AS ID_SERVICIO FROM TAREAS_SERVICIOS_CONTRATADOS T INNER JOIN CAT_TAREAS_SERVICIOS_CONTRATADOS CT ON T.ID_TAREA = CT.ID INNER JOIN SERVICIO_CLIENTE_ETAPA SCE ON T.ID_SERVICIO = SCE.ID INNER JOIN CLIENTES C ON SCE.ID_CLIENTE = C.ID AND T.ID_AUDITORIA=0";

$tareas = $database->query($strQuery)->fetchAll();

valida_error_medoo_and_die(); 
/////////////////////////////////////////////////////////////////////////////////////////

$csv	=	"Nombre Tarea;Nombre Cliente;Fecha Inicio;Fecha Fin;Id Servicio\r\n";
for($i = 0 ; $i < sizeof($tareas); $i++){
	$csv	.=	$tareas[$i]["NOMBRE_TAREA"].";";
	$csv	.=	$tareas[$i]["CLIENTE"].";";
	$csv	.=	$tareas[$i]["FECHA_INICIO"]." ".$tareas[$i]["HORA_INICIO"].";";
	$csv	.=	$tareas[$i]["FECHA_FIN"]." ".$tareas[$i]["HORA_FIN"].";";
	$csv	.=	$tareas[$i]["ID_SERVICIO"]."\r\n";	
	
}

print_r($csv);
/////////////////////////////////////////////////////////////////////////////////////////
 
?> 
