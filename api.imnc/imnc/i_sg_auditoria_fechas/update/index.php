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
		$mailerror->send("I_SG_AUDITORIA_FECHAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ID = $objeto->ID; 
valida_parametro_and_die($ID, "Falta el ID");


$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");

$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");

$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");

$FECHA = $objeto->FECHA; 
valida_parametro_and_die($FECHA, "Falta la FECHA");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");


$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

if($database->count("I_SG_AUDITORIA_FECHAS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"FECHA" => $FECHA]])==0){

$datos = $database->get("I_SG_AUDITORIA_FECHAS","*", ["ID" => $ID]);
//Aqui actualizo la fecha sino existe otra posibilidad
$id1 = $database->update("I_SG_AUDITORIA_FECHAS",
											
											[
												
												"FECHA"=>$FECHA,
												"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
												"HORA_MODIFICACION" => $HORA_MODIFICACION,
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												
												
											], 
	["ID"=>$ID]
); 
valida_error_medoo_and_die(); 
//Ahora actualizo esta fecha si esta cargada a algun auditor
$id2=$database->update("I_SG_AUDITORIA_GRUPO_FECHAS",["FECHA"=>$FECHA],["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$datos['ID_SERVICIO_CLIENTE_ETAPA'],"TIPO_AUDITORIA"=>$datos['TIPO_AUDITORIA'],"CICLO"=>$datos['CICLO'],"FECHA"=>$datos['FECHA']]]);
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
}
else{
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]="La FECHA ".$FECHA." ya ha sido insertada para esta auditoria"; 
	
}
print_r(json_encode($respuesta));
?> 