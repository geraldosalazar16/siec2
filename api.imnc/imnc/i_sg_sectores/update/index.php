<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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


$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");
/****************************************************/
$ID_ETAPA_ANTERIOR	=	$database->get("SERVICIO_CLIENTE_ETAPA","ID_ETAPA_PROCESO",["ID"=>$ID]);
/****************************************************/

$id1 = $database->update("I_SG_SECTORES",
											
											[
												"ID_SECTOR"=>$ID_SECTOR,
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"PRINCIPAL"=>$PRINCIPAL,
												
												"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
												"HORA_MODIFICACION" => $HORA_MODIFICACION,
												
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												
												
											], 
	["AND"=>["ID_SECTOR"=>$ID_SECTOR,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA]]
); 
valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta));
?> 