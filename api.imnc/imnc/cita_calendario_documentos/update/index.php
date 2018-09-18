<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error\n"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado_tarea"]="error"; 
		$respuesta["resultado_hist"]="error";
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("TAREAS_DOCUMENTO", getcwd(), $database->error()[2], $database->last_query(), "geraldosalazar16@gmail.com"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

//////////////////////////////////////////////////////////////////////////////////////////////
$ID_TAREA = $objeto->id; 
valida_parametro_and_die($ID_TAREA, "Es necesario seleccionar una tarea");
$ID_CATAG_DOCUM = $objeto->id_catag_docum; 
valida_parametro_and_die($ID_CATAG_DOCUM, "Es necesario que exista un id de documento");

$CICLO = $objeto->ciclo; 
valida_parametro_and_die($CICLO, "Es necesario que exista un ciclo");


$NOMBRE_TAREA = $objeto->nombre_tarea; 
valida_parametro_and_die($NOMBRE_TAREA, "Es necesario dar un nombre a la tarea");


$ESTADO_TAREA = $objeto->estado; 
//valida_parametro_and_die($ESTADO_TAREA, "Es necesario dar un estado a la tarea");

//////////////////////////////////////////////////////////////////////////////////////////////

$ID_SERVICIO = $objeto->id_servicio;
valida_parametro_and_die($ID_SERVICIO, "Falta ID"); 
/*
$ID_TAREA = $objeto->id_tarea; 
valida_parametro_and_die($ID_TAREA, "Es necesario seleccionar una tarea");
*/
if(isset($objeto->observaciones))
	$OBSERVACIONES = $objeto->observaciones; 
else	
	$OBSERVACIONES = "";

$FECHA_INICIO = $objeto->fecha_inicio;
valida_parametro_and_die($FECHA_INICIO, "Es necesario seleccionar una fecha de inicio");

$FECHA_FIN = $objeto->fecha_fin; 
valida_parametro_and_die($FECHA_FIN, "Es neceario seleccionar una fecha fin");

$HORA_INICIO = $objeto->hora_inicio;
valida_parametro_and_die($HORA_INICIO, "Es necesario seleccionar una hora de inicio");

$HORA_FIN = $objeto->hora_fin; 
valida_parametro_and_die($HORA_FIN, "Es neceario seleccionar una hora fin");

$USUARIO = $objeto->id_usuario_modificacion; 
valida_parametro_and_die($USUARIO, "Es neceario seleccionar una usuario");

$aux_estado	=	$database->get("TAREAS_DOCUMENTO", "ESTADO",["ID"=>$ID_TAREA]);
	$id = $database->update("TAREAS_DOCUMENTO", [ 
		"ESTADO"	=>	$ESTADO_TAREA,
		"FECHA_INICIO" => $FECHA_INICIO, 
		"HORA_INICIO" => $HORA_INICIO,
		"FECHA_FIN" => $FECHA_FIN,
		"HORA_FIN" => $HORA_FIN
	], 
	["ID"=>$ID_TAREA]); 	

valida_error_medoo_and_die(); 
$respuesta["resultado_tareas"]="ok"; 
$respuesta["id_tareas"]=$id;
//Hay que insertar la informacion en historicos
//obtengo fecha y hora ctuales
$FECHA = date("Ymd");
$HORA = date("His");

if($aux_estado != 1){
$id = $database->insert("TAREAS_DOCUMENTO_HISTORICO", [ 
	"ID_TAREA" => $ID_TAREA,
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
}
$respuesta["resultado_hist"]="ok";
$respuesta["id_hist"]=$id;
print_r(json_encode($respuesta)); 
?> 