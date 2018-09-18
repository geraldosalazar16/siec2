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
		$mailerror->send("SG_TIPOS_SERVICIO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$id = $_REQUEST["id"]; 

$sg_tipos_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 

if ($_REQUEST["domicilios"] == "true") {
	$id_cliente = $database->get("SERVICIO_CLIENTE_ETAPA", "ID_CLIENTE", ["ID"=>$sg_tipos_servicio["ID_SERVICIO_CLIENTE_ETAPA"]]); 
	valida_error_medoo_and_die(); 

	$domicilios_cliente = $database->select("CLIENTES_DOMICILIOS", "*", ["ID_CLIENTE"=>$id_cliente]); 
	valida_error_medoo_and_die(); 

	$sg_tipos_servicio["ID_CLIENTE"] = $id_cliente;
	$sg_tipos_servicio["DOMICILIOS"] = $domicilios_cliente;
}


print_r(json_encode($sg_tipos_servicio)); 
?> 
