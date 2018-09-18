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
		$mailerror->send("SG_CERTIFICADO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 


$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$CLAVE = $objeto->CLAVE;
valida_parametro_and_die($CLAVE, "Es necesario capturar la clave del certificado");

$ID_SG_TIPOS_SERVICIO = $objeto->ID_SG_TIPOS_SERVICIO; 
valida_parametro_and_die($ID_SG_TIPOS_SERVICIO, "Falta ID_SG_TIPOS_SERVICIO");

$FECHA_INICIO = $objeto->FECHA_INICIO;
valida_parametro_and_die($FECHA_INICIO, "Es necesario capturar una fecha de inicio");
if (strlen($FECHA_INICIO) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de inicio");
}
$anhio = intval(substr($FECHA_INICIO,0,4));
$mes = intval(substr($FECHA_INICIO,4,2));
$dia = intval(substr($FECHA_INICIO,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de inicio no es válida");
}

$FECHA_FIN = $objeto->FECHA_FIN;
valida_parametro_and_die($FECHA_FIN, "Es necesario capturar una fecha de fin");
if (strlen($FECHA_FIN) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de fin");
}
$anhio = intval(substr($FECHA_FIN,0,4));
$mes = intval(substr($FECHA_FIN,4,2));
$dia = intval(substr($FECHA_FIN,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de fin no es válida");
}
if ($FECHA_INICIO > $FECHA_FIN) {
	imprime_error_and_die("La fecha de inicio no puede ser después de la fecha final");
}

$FECHA_RENOVACION = $objeto->FECHA_RENOVACION; 
valida_parametro_and_die($FECHA_RENOVACION, "Es necesario capturar una fecha de renovación");
if (strlen($FECHA_RENOVACION) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de renovación");
}
$anhio = intval(substr($FECHA_RENOVACION,0,4));
$mes = intval(substr($FECHA_RENOVACION,4,2));
$dia = intval(substr($FECHA_RENOVACION,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de renovación no es válida");
}
if ($FECHA_INICIO > $FECHA_RENOVACION || $FECHA_FIN < $FECHA_RENOVACION) {
	imprime_error_and_die("La fecha de renovación debe estar entre la fecha de inicio y la fecha final");
}

$PERIODICIDAD = $objeto->PERIODICIDAD; 
valida_parametro_and_die($PERIODICIDAD, "Es necesario capturar la periodicidad del certificado");

$NOMBRE_ARCHIVO = $objeto->NOMBRE_ARCHIVO; // OPCIONAL
valida_parametro_and_die($NOMBRE_ARCHIVO, "Es necesario subir un archivo");

$ACREDITACION = $objeto->ACREDITACION; 
valida_parametro_and_die($ACREDITACION, "Es necesario capturar la acreditación del certificado");


$FECHA_INICIO_ACREDITACION = $objeto->FECHA_INICIO_ACREDITACION;
valida_parametro_and_die($FECHA_INICIO_ACREDITACION, "Es necesario capturar una fecha de inicio de acreditación");
if (strlen($FECHA_INICIO_ACREDITACION) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de inicio de acreditación");
}
$anhio = intval(substr($FECHA_INICIO_ACREDITACION,0,4));
$mes = intval(substr($FECHA_INICIO_ACREDITACION,4,2));
$dia = intval(substr($FECHA_INICIO_ACREDITACION,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de inicio de acreditación no es válida");
}

$FECHA_FIN_ACREDITACION = $objeto->FECHA_FIN_ACREDITACION;
valida_parametro_and_die($FECHA_FIN_ACREDITACION, "Es necesario capturar una fecha de fin de acreditación");
if (strlen($FECHA_FIN_ACREDITACION) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de fin de acreditación");
}
$anhio = intval(substr($FECHA_FIN_ACREDITACION,0,4));
$mes = intval(substr($FECHA_FIN_ACREDITACION,4,2));
$dia = intval(substr($FECHA_FIN_ACREDITACION,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de fin de acreditación no es válida");
}
if ($FECHA_INICIO_ACREDITACION > $FECHA_FIN_ACREDITACION) {
	imprime_error_and_die("La fecha de inicio no puede ser después de la fecha final");
}


$STATUS = $objeto->STATUS; 
valida_parametro_and_die($STATUS, "Es necesario seleccionar el status del certificado");

$FECHA_SUSPENSION = $objeto->FECHA_SUSPENSION; //Opcional
if ($FECHA_SUSPENSION != "" ) {
	if (strlen($FECHA_SUSPENSION) != 8) {
		imprime_error_and_die("Verifica el formato de la fecha de suspensión");
	}
	$anhio = intval(substr($FECHA_SUSPENSION,0,4));
	$mes = intval(substr($FECHA_SUSPENSION,4,2));
	$dia = intval(substr($FECHA_SUSPENSION,6,2));
	if (!checkdate($mes , $dia, $anhio)){
		imprime_error_and_die("La fecha de suspensión no es válida");
	}
}

$MOTIVO_SUSPENSION = $objeto->MOTIVO_SUSPENSION;  //Opcional

$FECHA_CANCELACION = $objeto->FECHA_CANCELACION; //Opcional
if ($FECHA_CANCELACION != "" ) {
	if (strlen($FECHA_CANCELACION) != 8) {
		imprime_error_and_die("Verifica el formato de la fecha de cancelación");
	}
	$anhio = intval(substr($FECHA_CANCELACION,0,4));
	$mes = intval(substr($FECHA_CANCELACION,4,2));
	$dia = intval(substr($FECHA_CANCELACION,6,2));
	if (!checkdate($mes , $dia, $anhio)){
		imprime_error_and_die("La fecha de cancelación no es válida");
	}
}

$MOTIVO_CANCELACION = $objeto->MOTIVO_CANCELACION; //Opcional

$RUTA_ARCHIVO = "/repositorio/archivos/" . $NOMBRE_ARCHIVO;

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id = $database->insert("SG_CERTIFICADO", [ 
	"CLAVE" => $CLAVE, 
	"ID_SG_TIPOS_SERVICIO" => $ID_SG_TIPOS_SERVICIO, 
	"FECHA_INICIO" => $FECHA_INICIO, 
	"FECHA_FIN" => $FECHA_FIN, 
	"FECHA_RENOVACION" => $FECHA_RENOVACION, 
	"PERIODICIDAD" => $PERIODICIDAD, 
	"RUTA_ARCHIVO" => $RUTA_ARCHIVO, 
	"ACREDITACION" => $ACREDITACION, 
	"FECHA_INICIO_ACREDITACION" => $FECHA_INICIO_ACREDITACION, 
	"FECHA_FIN_ACREDITACION" => $FECHA_FIN_ACREDITACION, 
	"STATUS" => $STATUS, 
	"FECHA_SUSPENSION" => $FECHA_SUSPENSION, 
	"MOTIVO_SUSPENSION" => $MOTIVO_SUSPENSION, 
	"FECHA_CANCELACION" => $FECHA_CANCELACION, 
	"MOTIVO_CANCELACION" => $MOTIVO_CANCELACION, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 

?> 
