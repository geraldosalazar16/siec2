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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

											
$valores = $database->query("SELECT `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIAS`.`DURACION_DIAS`, `I_SG_AUDITORIAS_TIPOS`.`TIPO`,`I_SG_AUDITORIA_STATUS`.`STATUS`,`I_SG_AUDITORIAS`.`NO_USA_METODO`,`I_SG_AUDITORIAS`.`SITIOS_AUDITAR`,`I_SG_AUDITORIAS`.`ID_USUARIO_CREACION`,`I_SG_AUDITORIAS`.`ID_USUARIO_MODIFICACION`,`I_SG_AUDITORIAS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIAS`.`STATUS_AUDITORIA` FROM `I_SG_AUDITORIAS` INNER JOIN `I_SG_AUDITORIAS_TIPOS` ON `I_SG_AUDITORIAS_TIPOS`.`ID` = `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` INNER JOIN `I_SG_AUDITORIA_STATUS` ON `I_SG_AUDITORIA_STATUS`.`ID` = `I_SG_AUDITORIAS`.`STATUS_AUDITORIA` WHERE `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id)->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($valores) ; $i++) { 
	$valores[$i]["SITIOS_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_SITIOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"]]]); 
	valida_error_medoo_and_die(); 
	$valores[$i]["AUDITORES_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"]]]); 
	//valida_error_medoo_and_die(); 
}
valida_error_medoo_and_die(); 

print_r(json_encode($valores)); 
?> 
