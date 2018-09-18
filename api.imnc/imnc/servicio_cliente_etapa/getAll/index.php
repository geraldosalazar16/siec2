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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$respuesta=array(); 
$servicio_cliente_etapa = $database->select("SERVICIO_CLIENTE_ETAPA", "*"); 
for ($i=0; $i < count($servicio_cliente_etapa) ; $i++) { 
	$servicio_nombre = $database->get("SERVICIOS", "NOMBRE", ["ID"=>$servicio_cliente_etapa[$i]["ID_SERVICIO"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_SERVICIO"] = $servicio_nombre;

	$cliente_nombre = $database->get("CLIENTES", "NOMBRE", ["ID"=>$servicio_cliente_etapa[$i]["ID_CLIENTE"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_CLIENTE"] = $cliente_nombre;

	$etapa_nombre = $database->get("ETAPAS_PROCESO", "ETAPA", ["ID_ETAPA"=>$servicio_cliente_etapa[$i]["ID_ETAPA_PROCESO"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_ETAPA"] = $etapa_nombre;
}
valida_error_medoo_and_die(); 
print_r(json_encode($servicio_cliente_etapa)); 
?> 
