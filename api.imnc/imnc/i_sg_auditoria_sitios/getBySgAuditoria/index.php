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
		$mailerror->send("I_SG_AUDITORIA_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$ID_SERVICIO_CLIENTE_ETAPA = $_REQUEST["id"]; 
$ID_TIPO_AUDITORIA = $_REQUEST["id_tipo_auditoria"]; 

$SG_AUDITORIA_SITIOS = $database->query("SELECT `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`,`CLIENTES_DOMICILIOS`.`NOMBRE_DOMICILIO`, `TIPOS_SERVICIO`.`NOMBRE`,`I_SG_AUDITORIAS`.`DURACION_DIAS`,  `I_SG_SITIOS`.`CANTIDAD_PERSONAS`,`I_SG_SITIOS`.`CANTIDAD_TURNOS`,`I_SG_SITIOS`.`NUMERO_TOTAL_EMPLEADOS`,`I_SG_SITIOS`.`NUMERO_EMPLEADOS_CERTIFICACION`,`I_SG_SITIOS`.`CANTIDAD_DE_PROCESOS`,`I_SG_SITIOS`.`ID_ACTIVIDAD`,`I_SG_SITIOS`.`TEMPORAL_O_FIJO`,`I_SG_SITIOS`.`MATRIZ_PRINCIPAL` FROM `I_SG_AUDITORIA_SITIOS` INNER JOIN `I_SG_SITIOS` ON `I_SG_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_SG_SITIOS`.`ID_CLIENTE_DOMICILIO`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO` INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` INNER JOIN `CLIENTES_DOMICILIOS` ON `CLIENTES_DOMICILIOS`.`ID`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`INNER JOIN `I_SG_AUDITORIAS` ON `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=`I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` = `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA` WHERE `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$ID_SERVICIO_CLIENTE_ETAPA. " AND `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`=".$ID_TIPO_AUDITORIA)->fetchAll(PDO::FETCH_ASSOC);


print_r(json_encode($SG_AUDITORIA_SITIOS)); 
?> 
