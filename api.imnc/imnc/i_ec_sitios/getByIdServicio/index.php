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
		$mailerror->send("I_EC_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 
											
$meta_sce = $database->query("SELECT DISTINCT `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO`,`CLIENTES_DOMICILIOS`.`NOMBRE_DOMICILIO`,`TIPOS_SERVICIO`.`ACRONIMO` FROM `I_EC_SITIOS` INNER JOIN `CLIENTES_DOMICILIOS` ON `CLIENTES_DOMICILIOS`.`ID`= `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO` INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_EC_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` WHERE `ID_SERVICIO_CLIENTE_ETAPA` = ".$id)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die(); 

print_r(json_encode($meta_sce)); 
?> 
