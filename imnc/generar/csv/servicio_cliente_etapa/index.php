<?php
// error_reporting(E_ALL);
// ini_set("display_errors",1);
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=servicio_cliente_etapa.csv");


require_once('../../../common/apiserver.php'); //$global_apiserver
require_once('../../../diff/selector.php'); //$global_diffname
require_once('../../../diff/'.$global_diffname.'/strings.php'); 

$respuesta = "ID,REFERENCIA,SG_INTEGRAL,NOMBRE_SERVICIO,NOMBRE_CLIENTE,NOMBRE_ETAPA,FECHA_CREACION,HORA_CREACION,USUARIO_CREACION,FECHA_MODIFICACION,HORA_MODIFICACION,USUARIO_MODIFICACION\r\n"; 

$servicio_cliente_etapa = json_decode(file_get_contents($global_apiserver . "/servicio_cliente_etapa/getAll/"), true);


for ($i=0; $i < count($servicio_cliente_etapa) ; $i++) { 
	foreach ($servicio_cliente_etapa[$i] as $key => $value) {
	    if (is_null($value)) {
	         $servicio_cliente_etapa[$i][$key] = "";
	    }
	}
	$usuario_creacion = json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $servicio_cliente_etapa[$i]["ID_USUARIO_CREACION"]), true);
	$usuario_modificacion= json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $servicio_cliente_etapa[$i]["ID_USUARIO_MODIFICACION"]), true);

	$respuesta .= utf8_decode($servicio_cliente_etapa[$i]["ID"]).",";
	$respuesta .= utf8_decode($servicio_cliente_etapa[$i]["REFERENCIA"]).",";
	$respuesta .= utf8_decode($servicio_cliente_etapa[$i]["SG_INTEGRAL"]).",";
	$respuesta .= utf8_decode($servicio_cliente_etapa[$i]["NOMBRE_SERVICIO"]).",";
	$respuesta .= '"' . utf8_decode($servicio_cliente_etapa[$i]["NOMBRE_CLIENTE"]) . '"' .",";
	$respuesta .= utf8_decode($servicio_cliente_etapa[$i]["NOMBRE_ETAPA"]).",";

	$respuesta .= $servicio_cliente_etapa[$i]["FECHA_CREACION"].",";
	$respuesta .= $servicio_cliente_etapa[$i]["HORA_CREACION"].",";
	$respuesta .= $usuario_creacion["NOMBRE"] . ",";
	$respuesta .= $servicio_cliente_etapa[$i]["FECHA_MODIFICACION"].",";
	$respuesta .= $servicio_cliente_etapa[$i]["HORA_MODIFICACION"].",";
	$respuesta .= $usuario_modificacion["NOMBRE"] . "\r\n";
}

print_r($respuesta);

?>