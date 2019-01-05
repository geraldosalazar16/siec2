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
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");
$ID_PERSONAL_TECNICO_CALIF = $objeto->ID_PERSONAL_TECNICO_CALIF; 
valida_parametro_and_die($ID_PERSONAL_TECNICO_CALIF, "Falta la ID_PERSONAL_TECNICO_CALIF");
$ID_ROL = $objeto->ID_ROL; 
valida_parametro_and_die($ID_ROL, "Falta la ID_ROL");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

if($database->count("I_SG_AUDITORIA_GRUPOS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF]])==0){
$idd = $database->insert("I_SG_AUDITORIA_GRUPOS",
											
											[
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
												"CICLO"=>$CICLO,
												"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF,
												"ID_ROL" => $ID_ROL,
												"FECHA_CREACION" => $FECHA_CREACION,
												"HORA_CREACION" => $HORA_CREACION,
												"FECHA_MODIFICACION" => "",
												"HORA_MODIFICACION" => "",
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												
												
											]); 
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
}
else{
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]="Este auditor ya ha sido agregado a esta auditoria"; 
}
print_r(json_encode($respuesta)); 
?> 
