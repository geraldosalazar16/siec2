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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("TAREAS_DOCUMENTO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

//EjecuciÃ³n de consultas SQL
$respuesta=array();

$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 
/*
$ID_TAREA = $objeto->id_tarea; 
valida_parametro_and_die($ID_TAREA, "Es necesario seleccionar un tipo de tarea");
*/
//////////////////////////////////////////////////////////////////////////////////////////////
$ID_CATAG_DOCUM = $objeto->id_catag_docum; 
valida_parametro_and_die($ID_CATAG_DOCUM, "Es necesario que exista un id de documento");

$CICLO = $objeto->ciclo; 
valida_parametro_and_die($CICLO, "Es necesario que exista un ciclo");


$NOMBRE_TAREA = $objeto->nombre_tarea; 
valida_parametro_and_die($NOMBRE_TAREA, "Es necesario dar un nombre a la tarea");


//////////////////////////////////////////////////////////////////////////////////////////////
$ID_SERVICIO = $objeto->id_servicio; 
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");


$FECHA_INICIO = $objeto->fecha_inicio;
valida_parametro_and_die($FECHA_INICIO, "Es necesario seleccionar una fecha de inicio");

$FECHA_FIN = $objeto->fecha_fin;
valida_parametro_and_die($FECHA_FIN, "Es necesario seleccionar una fecha de finalizaci¨®n");

$HORA_INICIO = $objeto->hora_inicio;
valida_parametro_and_die($HORA_INICIO, "Es necesario seleccionar una hora de inicio");

$HORA_FIN = $objeto->hora_fin;
valida_parametro_and_die($HORA_FIN, "Es necesario seleccionar una hora de finalizaci¨®n");

$USUARIO = $objeto->id_usuario_modificacion;
valida_parametro_and_die($USUARIO, "Es necesario seleccionar un usuario");

$OBSERVACIONES = $objeto->observaciones;
$OBSERVACIONES = $OBSERVACIONES." CREACI??N DE LA TAREA";

$id = $database->insert("TAREAS_DOCUMENTO", [
	"ID_CATALOGO_DOCUMENTOS" => $ID_CATAG_DOCUM,
	"ID_SERVICIO" => $ID_SERVICIO,
	"CICLO"	=>	$CICLO,	
	"NOMBRE_TAREA" => $NOMBRE_TAREA,
	"FECHA_INICIO" => $FECHA_INICIO, 
	"HORA_INICIO" => $HORA_INICIO, 
	"FECHA_FIN" => $FECHA_FIN,
	"HORA_FIN" => $HORA_FIN
]); 

//Necesito obtener el id de la tarea para insertarlo en la tabla historicos
$tarea_id = $database->get("TAREAS_DOCUMENTO","ID", [
	"AND" => ["ID_SERVICIO" => $ID_SERVICIO,"NOMBRE_TAREA" => $NOMBRE_TAREA,"CICLO"=>$CICLO,"ID_CATALOGO_DOCUMENTOS"=>$ID_CATAG_DOCUM]]);
valida_error_medoo_and_die(); 

$respuesta["resultado_tareas"]="ok"; 
$respuesta["id_tareas"]=$id;

//obtengo fecha y hora ctuales
$FECHA = date("Ymd");
$HORA = date("His");

$id = $database->insert("TAREAS_DOCUMENTO_HISTORICO", [ 
	"ID_TAREA" => $tarea_id,
	"USUARIO_MODIFICACION" => $USUARIO, 
	"FECHA_MODIFICACION" => $FECHA, 
	"HORA_MODIFICACION" => $HORA,
	"DESCRIPCION_MODIFICACION" => $OBSERVACIONES,
	"FECHA_INICIO" => $FECHA_INICIO,
	"FECHA_FIN" => $FECHA_FIN,
	"HORA_INICIO" => $HORA_INICIO,
	"HORA_FIN" => $HORA_FIN
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado_hist"]="ok"; 
$respuesta["id_hist"]=$id;

print_r(json_encode($respuesta));
?> 
