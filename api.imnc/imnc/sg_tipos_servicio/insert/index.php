<?php  
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';


function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}

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
		$mailerror->send("SG_TIPOS_SERVICIO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA;
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");

$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar el tipo de servicio");

$ID_NORMA = $objeto->ID_NORMA; 
valida_parametro_and_die($ID_NORMA, "Es necesario seleccionar la norma");

$TOTAL_EMPLEADOS = $objeto->TOTAL_EMPLEADOS; 
valida_parametro_and_die($TOTAL_EMPLEADOS, "Es necesario capturar el total de empleados");
if (!is_numeric($TOTAL_EMPLEADOS) || intval($TOTAL_EMPLEADOS) < 0) {
	imprime_error_and_die("Verifica que el total de empleados sea un número y sea mayor o igual a cero");
}

$TOTAL_EMPLEADOS_PARA_CERTIFICACION = $objeto->TOTAL_EMPLEADOS_PARA_CERTIFICACION; 
valida_parametro_and_die($TOTAL_EMPLEADOS_PARA_CERTIFICACION, "Es necesario capturar el total de empleados para certificación");
if (!is_numeric($TOTAL_EMPLEADOS_PARA_CERTIFICACION) || intval($TOTAL_EMPLEADOS_PARA_CERTIFICACION) < 0) {
	imprime_error_and_die("Verifica que el total de empleados para certificación sea un número y sea mayor o igual a cero");
}

$TURNOS = $objeto->TURNOS; 
valida_parametro_and_die($TURNOS, "Es necesario capturar los turnos");
if (!is_numeric($TURNOS) || intval($TURNOS) < 0) {
	imprime_error_and_die("Verifica que la cantidad de turnos sea un número y sea mayor o igual a cero");
}

$MULTISITIOS = $objeto->MULTISITIOS; 
valida_parametro_and_die($MULTISITIOS, "Es necesario espacificar si es multisitios");

$CONDICIONES_SEGURIDAD = $objeto->CONDICIONES_SEGURIDAD; 
valida_parametro_and_die($CONDICIONES_SEGURIDAD, "Es necesario capturar las condiciones de seguridad");

$ALCANCE = $objeto->ALCANCE; 
valida_parametro_and_die($ALCANCE, "Es necesario capturar el alcance");

$COMPLEJIDAD = $objeto->COMPLEJIDAD; 
if($ID_TIPO_SERVICIO == "CSGA"){
	valida_parametro_and_die($COMPLEJIDAD, "Es necesario capturar la complejidad");
}


$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id = $database->insert("SG_TIPOS_SERVICIO", [ 
	"ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA, 
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO, 
	"ID_NORMA" => $ID_NORMA, 
	"TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS, 
	"TOTAL_EMPLEADOS_PARA_CERTIFICACION" => $TOTAL_EMPLEADOS_PARA_CERTIFICACION, 
	"TURNOS" => $TURNOS, 
	"MULTISITIOS" => $MULTISITIOS, 
	"CONDICIONES_SEGURIDAD" => $CONDICIONES_SEGURIDAD, 
	"ALCANCE" => $ALCANCE, 
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 
valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
