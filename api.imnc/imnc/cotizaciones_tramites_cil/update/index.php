<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("COTIZACIONES_TRAMITES_CIL", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
$ID_COTIZACION = $objeto->ID_COTIZACION; 
valida_parametro_and_die($ID_COTIZACION,"Falta ID de USUARIO");
$ID_TIPO_AUDITORIA = $objeto->ID_TIPO_AUDITORIA;
valida_parametro_and_die($ID_TIPO_AUDITORIA,"Falta ID de ETAPA");
$VIATICOS = $objeto->VIATICOS; 
$DESCUENTO = $objeto->DESCUENTO; 
$AUMENTO = $objeto->AUMENTO; 
//$REDUCCION = $objeto->REDUCCION; 
$DIAS_MULTISITIO = $objeto->DIAS_MULTISITIO; 
//valida_parametro_and_die($CAMBIO,"Falta indicar si hay cambio");


if($DIAS_MULTISITIO!="" && !is_numeric($DIAS_MULTISITIO)){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "Los DIAS_MULTISITIO debe ser un número"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}
if($VIATICOS!="" && !is_numeric($VIATICOS)){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "VIATICOS debe ser un número"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}

if ($DESCUENTO != "" && ($DESCUENTO < 0 || $DESCUENTO > 100)) { 
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El Descuento no puede ser menor al 0% ni mayor al 100%";  
	print_r(json_encode($respuesta)); 
	die(); 
}
if ($AUMENTO != "" && ($AUMENTO < 0 || $AUMENTO > 100)) { 
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El AUMENTO no puede ser menor al 0% ni mayor al 100%";  
	print_r(json_encode($respuesta)); 
	die(); 
} /*
if ($REDUCCION != "" && ($REDUCCION < 0 || $REDUCCION > 100)) { 
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "La REDUCCION no puede ser menor al 0% ni mayor al 100%";  
	print_r(json_encode($respuesta)); 
	die(); 
}*/


$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");

$id = $database->update("COTIZACIONES_TRAMITES_CIL", [ 
	"ID_COTIZACION" => $ID_COTIZACION,
	"ID_TIPO_AUDITORIA" => $ID_TIPO_AUDITORIA,
	"VIATICOS" => $VIATICOS,
	"DESCUENTO" => $DESCUENTO,
	"AUMENTO" => $AUMENTO,
//	"REDUCCION" => $REDUCCION,
	"DIAS_MULTISITIO" => $DIAS_MULTISITIO,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
