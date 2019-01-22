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
		$mailerror->send("COTIZACIONES_TRAMITES_DH", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 
valida_parametro_and_die($ID_COTIZACION,"Falta ID de USUARIO");
$ID_TIPO_AUDITORIA = $objeto->ID_TIPO_AUDITORIA;
valida_parametro_and_die($ID_TIPO_AUDITORIA,"Falta ID de TIPO AUDITORIA");
$VIATICOS = $objeto->VIATICOS; 
$DESCUENTO = $objeto->DESCUENTO; 
$AUMENTO = $objeto->AUMENTO; 



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
} 


$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$id = $database->insert("COTIZACIONES_TRAMITES_DH", [ 
	"ID_COTIZACION" => $ID_COTIZACION,
	"ID_TIPO_AUDITORIA" => $ID_TIPO_AUDITORIA,
	"VIATICOS" => $VIATICOS,
	"DESCUENTO" => $DESCUENTO,
	"AUMENTO" => $AUMENTO,
	"FECHA_CREACION" => $FECHA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
