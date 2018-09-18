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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 
valida_parametro_and_die($ID_COTIZACION,"Falta ID de USUARIO");
$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO;
valida_parametro_and_die($ID_ETAPA_PROCESO,"Falta ID de ETAPA");
$VIATICOS = $objeto->VIATICOS; 
$DESCUENTO = $objeto->DESCUENTO; 
$FACTOR_INTEGRACION = $objeto->FACTOR_INTEGRACION; 
$JUSTIFICACION = $objeto->JUSTIFICACION; 
$CAMBIO = $objeto->CAMBIO; 
valida_parametro_and_die($CAMBIO,"Falta indicar si hay cambio");

if ($DESCUENTO != "" && ($DESCUENTO < 0 || $DESCUENTO > 100)) { 
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El Descuento no puede ser menor al 0% ni mayor al 100%";  
	print_r(json_encode($respuesta)); 
	die(); 
} 

if($objeto->SG_INTEGRAL == "si"){
	valida_parametro_and_die($FACTOR_INTEGRACION,"Falta factor de integración");
	if(!is_numeric($FACTOR_INTEGRACION)){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de integración debe ser un número"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}
	if($FACTOR_INTEGRACION < 0 || $FACTOR_INTEGRACION > 20){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de integración no puede ser menor al 0% ni mayor al 20%"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}
	valida_parametro_and_die($JUSTIFICACION,"Falta justificación");
}
$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
$id = $database->insert("COTIZACIONES_TRAMITES", [ 
	"ID_COTIZACION" => $ID_COTIZACION,
	"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,
	"VIATICOS" => $VIATICOS,
	"DESCUENTO" => $DESCUENTO,
	"FACTOR_INTEGRACION" => $FACTOR_INTEGRACION,
	"JUSTIFICACION" => $JUSTIFICACION,
	"CAMBIO" => $CAMBIO,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
