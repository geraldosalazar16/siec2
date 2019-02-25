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
		$mailerror->send("COTIZACION_SITIOS_DH", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 

$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");

$UNI_REF_CONG = $objeto->UNI_REF_CONG;
valida_parametro_and_die($UNI_REF_CONG,"Falta las unidades de refrigeracion y congelacion");

$CENTRO_CONSUMO = $objeto->CENTRO_CONSUMO;
valida_parametro_and_die($CENTRO_CONSUMO,"Falta los centros de consumo");

$NUM_COCINAS = $objeto->NUM_COCINAS;
valida_parametro_and_die($NUM_COCINAS,"Falta el numero de cocinas");

$CANTIDAD_DIAS = $objeto->CANTIDAD_DIAS;
valida_parametro_and_die($CANTIDAD_DIAS,"Falta la cantidad de dias");

$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO;
valida_parametro_and_die($TEMPORAL_O_FIJO,"Falta seleccionar si es temporal o fijo");
$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
valida_parametro_and_die($MATRIZ_PRINCIPAL,"Falta seleccionar si es matriz principal");

$SELECCIONADO = 0; 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
//$HORA_CREACION = date("His");


$id = $database->insert("COTIZACION_SITIOS_DH", [ 
	"ID_COTIZACION" => $ID_COTIZACION, 
	"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
	"UNI_REF_CONG" =>	$UNI_REF_CONG,
	"CENTRO_CONSUMO" => $CENTRO_CONSUMO,
	"NUM_COCINAS" => $NUM_COCINAS,
	"CANTIDAD_DIAS" => $CANTIDAD_DIAS,
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
