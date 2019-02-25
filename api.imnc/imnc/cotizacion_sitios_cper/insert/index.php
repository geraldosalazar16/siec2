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
		$mailerror->send("COTIZACION_SITIOS_CPER", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 

$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");
$CANTIDAD_PERSONAS = $objeto->CANTIDAD_PERSONAS;
valida_parametro_and_die($CANTIDAD_PERSONAS,"Falta la cantidad de personas");

$SELECCIONADO = 0; 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
//$HORA_CREACION = date("His");


$id = $database->insert("COTIZACION_SITIOS_CPER", [ 
	"ID_COTIZACION" => $ID_COTIZACION, 
	"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
	"CANTIDAD_PERSONAS" => $CANTIDAD_PERSONAS,
	"SELECCIONADO" => $SELECCIONADO, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
