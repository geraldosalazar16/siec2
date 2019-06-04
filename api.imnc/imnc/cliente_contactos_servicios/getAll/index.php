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
		
		die(); 
	} 
} 

$respuesta=array(); 
$query="SELECT CC.ID AS ID_CLIENTE_CONTACTO,CC.ID_CLIENTE_DOMICILIO AS ID_CLIENTE_DOMICILIO,
CC.ES_PRINCIPAL AS ES_PRINCIPAL,CC.NOMBRE_CONTACTO AS NOMBRE_CONTACTO,CC.CARGO AS CARGO,
CC.TELEFONO_MOVIL AS TELEFONO_MOVIL,CC.TELEFONO_FIJO AS TELEFONO_FIJO,CC.EXTENSION AS EXTENSION,
CC.EMAIL AS EMAIL,CC.DATOS_ADICIONALES AS DATOS_ADICIONALES,CC.FECHA_CREACION AS FECHA_CREACION,
CC.FECHA_FIN AS FECHA_FIN,CC.FECHA_INICIO AS FECHA_INICIO,CC.FECHA_MODIFICACION AS FECHA_MODIFICACION,
CC.HORA_CREACION AS HORA_CREACION,CCS.ID_SERVICIO AS ID_SERVICIO,S.NOMBRE AS NOMBRE_SERVICIO FROM CLIENTES_CONTACTOS_SERVICIOS CCS
INNER JOIN CLIENTES_CONTACTOS CC ON CCS.ID_CONTACTO=CC.ID
INNER JOIN SERVICIOS S ON CCS.ID_SERVICIO=S.ID ORDER BY CC.ID";
$clientes_contactos_servicios = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();


print_r(json_encode($clientes_contactos_servicios)); 
?> 
