<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  
include '../../ex_common/archivos.php';
include 'funciones.php';
function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_PROSPECTO = $objeto->id_prospecto; 
valida_parametro_and_die($ID_PROSPECTO, "Es necesario seleccionar un prospecto");

$FECHA_INICIO = $objeto->fecha_inicio; 
valida_parametro_and_die($FECHA_INICIO, "Es necesario seleccionar una fecha de inicio");

$HORA_INICIO = $objeto->hora_inicio; 
valida_parametro_and_die($HORA_INICIO, "Es neceario seleccionar una hora de inicio");

$FECHA_FIN= $objeto->fecha_fin;
valida_parametro_and_die($FECHA_FIN, "Es necesario capturar una fecha fin");

$HORA_FIN = $objeto->hora_fin;
valida_parametro_and_die($HORA_FIN,"Es necesario capturar la hora fin");

$TIPO_ASUNTO = $objeto->tipo_asunto;
valida_parametro_and_die($TIPO_ASUNTO,"Es necesario capturar un tipo de asunto");
$DESCRIPCION = $objeto->descripcion;
$ESTADO = $objeto->estado;

$id = $database->insert("PROSPECTO_TAREAS", [ 
	"ID_PROSPECTO" => $ID_PROSPECTO, 
	"FECHA_INICIO" => $FECHA_INICIO, 
	"HORA_INICIO" => $HORA_INICIO, 
	"FECHA_FIN" => $FECHA_FIN, 
	"HORA_FIN" => $HORA_FIN,
	"ID_TIPO_ASUNTO" => $TIPO_ASUNTO,
	"DESCRIPCION" => $DESCRIPCION,
	"ESTADO"=>$ESTADO
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 

print_r(json_encode($respuesta)); 
?> 
