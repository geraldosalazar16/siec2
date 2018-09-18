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
		$mailerror->send("SG_SECTORES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
valida_parametro_and_die($ID, "Falta ID");

$ID_SG_TIPO_SERVICIO = $objeto->ID_SG_TIPO_SERVICIO; 
valida_parametro_and_die($ID_SG_TIPO_SERVICIO, "Falta ID_SG_TIPO_SERVICIO");

$ID_SECTOR = $objeto->ID_SECTOR; 
valida_parametro_and_die($ID_SECTOR, "Es necesario selccionar un sector");
$ID_SECTOR_ACTUAL = $database->get("SG_SECTORES","ID_SECTOR",["ID"=>$ID]);
if (trim($ID_SECTOR) != trim($ID_SECTOR_ACTUAL)){
	$count_sector = $database->count("SG_SECTORES", ["AND"=>["ID_SECTOR"=>$ID_SECTOR, "ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO]]);
	if ($count_sector > 0) {
		imprime_error_and_die("El sector que está intentando capturar ya existe.");
	}	
}

$PRINCIPAL = $objeto->PRINCIPAL; 
valida_parametro_and_die($PRINCIPAL, "Es necesario definir si es principal o no");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("SG_SECTORES", [ 
	"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, 
	"ID_SECTOR" => $ID_SECTOR, 
	"PRINCIPAL" => $PRINCIPAL, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
