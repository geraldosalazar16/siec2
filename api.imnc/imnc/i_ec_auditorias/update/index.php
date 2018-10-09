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
		$mailerror->send("I_EC_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$DURACION_DIAS = $objeto->DURACION_DIAS; 
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
$CICLO =  $objeto->CICLO;
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$STATUS_AUDITORIA = $objeto->STATUS_AUDITORIA; 
valida_parametro_and_die($STATUS_AUDITORIA, "Falta el STATUS_AUDITORIA");
//$NO_USA_METODO = $objeto->NO_USA_METODO; 
//$SITIOS_AUDITAR = $objeto->SITIOS_AUDITAR; 
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");


$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");


$id1 = $database->update("I_EC_AUDITORIAS",
											
											[
												"DURACION_DIAS"=>$DURACION_DIAS,
												"STATUS_AUDITORIA" => $STATUS_AUDITORIA,
												//"NO_USA_METODO" => $NO_USA_METODO,
												//"SITIOS_AUDITAR" => $SITIOS_AUDITAR,
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												
												
											], 
	["AND"=>["TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"CICLO"=>$CICLO]]
); 
valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta));
?> 