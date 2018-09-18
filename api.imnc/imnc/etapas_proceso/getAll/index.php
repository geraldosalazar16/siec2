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
		$mailerror->send("ETAPAS_PROCESO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 

$etapas_proceso = $database->select("ETAPAS_PROCESO", "*", ["ORDER" => ["ID_SERVICIO", "ID"]]); 
valida_error_medoo_and_die();
for ($i=0; $i < count($etapas_proceso) ; $i++) {
	$etapas_proceso[$i]["NOMBRE_SERVICIO"] = $database->get("SERVICIOS", "NOMBRE", ["ID"=>$etapas_proceso[$i]["ID_SERVICIO"]]);
}

print_r(json_encode($etapas_proceso)); 
?> 
