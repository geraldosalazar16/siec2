<?php  
//include  '../../common/conn-apiserver.php';  
//include  '../../common/conn-medoo.php';  
//include  '../../common/conn-sendgrid.php'; 



include  '../../ex_common/query.php';
include 'funciones.php';
include '../../ex_common/archivos.php';

/*
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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
*/
$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_PROSPECTO = $objeto->ID_PROSPECTO; 
valida_parametro_and_die($ID_PROSPECTO,"Falta ID de USUARIO");
$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO,"Falta ID de SERVICIO");
$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO,"Falta ID de TIPO DE SERVICIO");
$ESTADO_COTIZACION = $objeto->ESTADO_COTIZACION; 
valida_parametro_and_die($ESTADO_COTIZACION,"Falta ESTADO COTIZACION");
$FOLIO_SERVICIO = $objeto->FOLIO_SERVICIO; 
valida_parametro_and_die($FOLIO_SERVICIO,"Falta FOLIO SERVICIO");
$FOLIO_INICIALES = $objeto->FOLIO_INICIALES; 
valida_parametro_and_die($FOLIO_INICIALES,"Falta FOLIO INICIALES");
$TARIFA = $objeto->TARIFA;
valida_parametro_and_die($TARIFA,"Falta seleccionar la Tarifa");
$DESCUENTO = $objeto->DESCUENTO;
$SG_INTEGRAL = $objeto->SG_INTEGRAL;
valida_parametro_and_die($SG_INTEGRAL,"Falta INTEGRAL");
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
valida_parametro_and_die($COMPLEJIDAD,"Falta COMPLEJIDAD");
$BANDERA = $objeto->BANDERA; 

if ($DESCUENTO != "" && ($DESCUENTO < 0 || $DESCUENTO > 100)) { 
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El Descuento no puede ser menor al 0% ni mayor al 100%";  
	print_r(json_encode($respuesta)); 
	die(); 
} 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FOLIO_MES = date("m");
$FOLIO_YEAR = date("y");

$FOLIO_CONSECUTIVO = $database->max("COTIZACIONES", "FOLIO_CONSECUTIVO", ["FOLIO_YEAR" => $FOLIO_YEAR]);
if(is_null($FOLIO_CONSECUTIVO))
	$FOLIO_CONSECUTIVO = 0;
else
	$FOLIO_CONSECUTIVO += 1;

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
$id = $database->insert("COTIZACIONES", [ 
	"ID_PROSPECTO" => $ID_PROSPECTO, 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO, 
	"ESTADO_COTIZACION" => $ESTADO_COTIZACION, 
	"FOLIO_SERVICIO" => $FOLIO_SERVICIO, 
	"FOLIO_INICIALES" => $FOLIO_INICIALES, 
	"FOLIO_CONSECUTIVO" => $FOLIO_CONSECUTIVO, 
	"FOLIO_MES" => $FOLIO_MES, 
	"FOLIO_YEAR" => $FOLIO_YEAR,
	"TARIFA" => $TARIFA,
	"DESCUENTO" => $DESCUENTO,
	"SG_INTEGRAL" => $SG_INTEGRAL,
	"BANDERA" => $BANDERA,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 

creacion_expediente_registro($id,4,$rutaExpediente, $database);
crea_instancia_expedientes_registro($id,4,$database);


print_r(json_encode($respuesta)); 
?> 
