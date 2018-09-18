<?php
// error_reporting(E_ALL);
// ini_set("display_errors",1);
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=personal_tecnico.csv");


require_once('../../../common/apiserver.php'); //$global_apiserver
require_once('../../../diff/selector.php'); //$global_diffname
require_once('../../../diff/'.$global_diffname.'/strings.php'); 

$respuesta = "ID,NOMBRE,APELLIDO_MATERNO,APELLIDO_PATERNO,INICIALES,FECHA_NACIMIENTO,CURP,RFC,TELEFONO_FIJO,TELEFONO_CELULAR,EMAIL,STATUS,FECHA_CREACION,HORA_CREACION,USUARIO_CREACION,FECHA_MODIFICACION,HORA_MODIFICACION,USUARIO_MODIFICACION\r\n"; 

$personal_tecnico = json_decode(file_get_contents($global_apiserver . "/personal_tecnico/getAll/"), true);


for ($i=0; $i < count($personal_tecnico) ; $i++) { 
	foreach ($personal_tecnico[$i] as $key => $value) {
	    if (is_null($value)) {
	         $personal_tecnico[$i][$key] = "";
	    }
	}
	$usuario_creacion = json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $personal_tecnico[$i]["ID_USUARIO_CREACION"]), true);
	$usuario_modificacion= json_decode(file_get_contents($global_apiserver . "/usuarios/getById/?id=" . $personal_tecnico[$i]["ID_USUARIO_MODIFICACION"]), true);

	$respuesta .= utf8_decode($personal_tecnico[$i]["ID"]).",";
	$respuesta .= utf8_decode($personal_tecnico[$i]["NOMBRE"]).",";
	$respuesta .= utf8_decode($personal_tecnico[$i]["APELLIDO_MATERNO"]).",";
	$respuesta .= utf8_decode($personal_tecnico[$i]["APELLIDO_PATERNO"]).",";
	$respuesta .= utf8_decode($personal_tecnico[$i]["INICIALES"]).",";
	$respuesta .= $personal_tecnico[$i]["FECHA_NACIMIENTO"].",";
	$respuesta .= $personal_tecnico[$i]["CURP"].",";
	$respuesta .= $personal_tecnico[$i]["RFC"].",";
	$respuesta .= $personal_tecnico[$i]["TELEFONO_FIJO"].",";
	$respuesta .= $personal_tecnico[$i]["TELEFONO_CELULAR"].",";
	$respuesta .= $personal_tecnico[$i]["EMAIL"].",";
	$respuesta .= $personal_tecnico[$i]["STATUS"].",";
	$respuesta .= $personal_tecnico[$i]["FECHA_CREACION"].",";
	$respuesta .= $personal_tecnico[$i]["HORA_CREACION"].",";
	$respuesta .= $usuario_creacion["NOMBRE"] . ",";
	$respuesta .= $personal_tecnico[$i]["FECHA_MODIFICACION"].",";
	$respuesta .= $personal_tecnico[$i]["HORA_MODIFICACION"].",";
	$respuesta .= $usuario_modificacion["NOMBRE"] . "\r\n";
}

print_r($respuesta);

?>