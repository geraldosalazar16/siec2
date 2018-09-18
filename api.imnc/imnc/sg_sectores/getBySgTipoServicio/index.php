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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("SG_SECTORES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$respuesta=array(); 

$id_sg_tipo_servicio = $_REQUEST["id_sg_tipo_servicio"];

$sg_sector = $database->select("SG_SECTORES", "*", ["ID_SG_TIPO_SERVICIO" => $id_sg_tipo_servicio]); 
for ($i=0; $i < count($sg_sector) ; $i++) { 
	$sector = $database->get("SECTORES", "*", ["ID_SECTOR"=>$sg_sector[$i]["ID_SECTOR"]]);
	$sg_sector[$i]["CLAVE_SECTOR"] = $sector["ID"];
	$sg_sector[$i]["ID_TIPO_SERVICIO"] = $sector["ID_TIPO_SERVICIO"];
	$sg_sector[$i]["NOMBRE_SECTOR"] = $sector["NOMBRE"];
	$sg_sector[$i]["ANHIO"] = $sector["ANHIO"];
	$sg_sector[$i]["CLAVE_COMPUESTA"] = $sector["ID"] . "-" . $sector["ID_TIPO_SERVICIO"] . "-" .  $sector["ANHIO"];;
}
valida_error_medoo_and_die(); 
print_r(json_encode($sg_sector)); 
?> 
