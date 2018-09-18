<?php
//error_reporting(E_ALL);
//ini_set("display_errors",1);
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=clientes.csv");


require_once('../../../common/apiserver.php'); //$global_apiserver
require_once('../../../diff/selector.php'); //$global_diffname
require_once('../../../diff/'.$global_diffname.'/strings.php'); 

$respuesta = "ID,TIPO_PERSONA,TIPO_ENTIDAD,CLINETE_FACTURARIO,NOMBRE,RFC,TIENE_FACTURARIO,ES_FACTURARIO,FECHA_CREACION,HORA_CREACION,USUARIO_CREACION,FECHA_MODIFICACION,HORA_MODIFICACION,USUARIO_MODIFICACION\r\n"; 

$clientes = json_decode(file_get_contents($global_apiserver . "/clientes/getAll/"), true);


for ($i=0; $i < count($clientes) ; $i++) { 
	foreach ($clientes[$i] as $key => $value) {
	    if (is_null($value)) {
	         $clientes[$i][$key] = "";
	    }
	}
	$usuario_creacion = json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $clientes[$i]["ID_USUARIO_CREACION"]), true);
	$usuario_modificacion= json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $clientes[$i]["ID_USUARIO_MODIFICACION"]), true);

	$respuesta .= utf8_decode($clientes[$i]["ID"]).",";
	$respuesta .= $clientes[$i]["ID_TIPO_PERSONA"].",";
	$respuesta .= $clientes[$i]["ID_TIPO_ENTIDAD"].",";
	$respuesta .= $clientes[$i]["ID_CLIENTE_FACTURARIO"].",";
	$respuesta .= '"' . utf8_decode($clientes[$i]["NOMBRE"]).'"'.",";
	$respuesta .= $clientes[$i]["RFC"].",";
	$respuesta .= $clientes[$i]["TIENE_FACTURARIO"].",";
	$respuesta .= $clientes[$i]["ES_FACTURARIO"].",";
	$respuesta .= $clientes[$i]["FECHA_CREACION"].",";
	$respuesta .= $clientes[$i]["HORA_CREACION"].",";
	$respuesta .= $usuario_creacion["NOMBRE"] . ",";
	$respuesta .= $clientes[$i]["FECHA_MODIFICACION"].",";
	$respuesta .= $clientes[$i]["HORA_MODIFICACION"].",";
	$respuesta .= $usuario_modificacion["NOMBRE"] . "\r\n";
}

print_r($respuesta);

?>