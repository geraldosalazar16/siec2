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
		$mailerror->send("COTIZACION_SITIOS_HM", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 

$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");

$ALCANCE = $objeto->ALCANCE;
valida_parametro_and_die($ALCANCE,"Falta el ALCANCE");

$OPCION_8H_O_4H = $objeto->OPCION_8H_O_4H;
valida_parametro_and_die($OPCION_8H_O_4H,"Debe seleccionar si la visita es de 4 u 8 horas");



$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO;
valida_parametro_and_die($TEMPORAL_O_FIJO,"Falta seleccionar si es temporal o fijo");
$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
valida_parametro_and_die($MATRIZ_PRINCIPAL,"Falta seleccionar si es matriz principal");

$SELECCIONADO = 0; 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
//$HORA_CREACION = date("His");


$id = $database->insert("COTIZACION_SITIOS_HM", [ 
	"ID_COTIZACION" => $ID_COTIZACION, 
	"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
	"ALCANCE" =>	$ALCANCE,
	"OPCION_8H_O_4H" => $OPCION_8H_O_4H,
	"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO,
	"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL,
	"SELECCIONADO" => $SELECCIONADO, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
