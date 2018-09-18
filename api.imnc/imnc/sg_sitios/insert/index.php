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
		$mailerror->send("SG_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SG_TIPO_SERVICIO = $objeto->ID_SG_TIPO_SERVICIO; 
$ID_CLIENTE_DOMICILIO = $objeto->ID_CLIENTE_DOMICILIO; 
$CANTIDAD_PERSONAS = $objeto->CANTIDAD_PERSONAS; 
$CANTIDAD_TURNOS = $objeto->CANTIDAD_TURNOS; 
$NUMERO_TOTAL_EMPLEADOS = $objeto->NUMERO_TOTAL_EMPLEADOS; 
$NUMERO_EMPLEADOS_CERTIFICACION = $objeto->NUMERO_EMPLEADOS_CERTIFICACION; 
$CANTIDAD_DE_PROCESOS = $objeto->CANTIDAD_DE_PROCESOS; 
$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO; 
$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
$NOMBRE_PROCESOS=$objeto->NOMBRE_PROCESOS; 

$ID_ACTIVIDAD = $objeto->ID_ACTIVIDAD;
valida_parametro_and_die($ID_ACTIVIDAD,"Falta Actividad");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$validacion = $database->count("SG_SITIOS", ["AND" => ["ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, "ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO]]); 
if($validacion > 0 ){
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]= "El sitio ya existe en este servicio.";
	print_r(json_encode($respuesta)); 
	die(); 
}

$id = $database->insert("SG_SITIOS", [ 
	"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, 
	"ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO, 
	"CANTIDAD_PERSONAS" => $CANTIDAD_PERSONAS, 
	"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS, 
	"NUMERO_TOTAL_EMPLEADOS" => $NUMERO_TOTAL_EMPLEADOS, 
	"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION, 
	"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS, 
	"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO, 
	"ID_ACTIVIDAD" => $ID_ACTIVIDAD, 
	"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION,
	"NOMBRE_PROCESOS"=>$NOMBRE_PROCESOS
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
