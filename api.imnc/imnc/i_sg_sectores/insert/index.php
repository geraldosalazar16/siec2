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
		$mailerror->send("I_SG_SECTORES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SECTOR = $objeto->ID_SECTOR; 
valida_parametro_and_die($ID_SECTOR, "Falta el ID_SECTOR ");
$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$PRINCIPAL = $objeto->PRINCIPAL; 
valida_parametro_and_die($PRINCIPAL, "Falta el PRINCIPAL");
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
$idd = $database->insert("I_SG_SECTORES",
											
											[
												"ID_SECTOR"=>$ID_SECTOR,
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"PRINCIPAL"=>$PRINCIPAL,
												"FECHA_CREACION" => $FECHA_CREACION,
												"HORA_CREACION" => $HORA_CREACION,
												"FECHA_MODIFICACION" => "",
												"HORA_MODIFICACION" => "",
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												
												
											]); 
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 
?> 
