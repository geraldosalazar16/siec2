<?php
include  '../../common/conn-apiserver.php';

include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';




function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		die();
	}
}

$respuesta=array();
$json = file_get_contents("php://input");

$objeto = json_decode($json);

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesario un ID para poder editar");

$MODULO= $objeto->MODULO;
valida_parametro_and_die($MODULO, "Es necesario introducir un módulo");
$MODULO = strtoupper($MODULO);
$count = $database->count("MODULOS", ["AND" => ["MODULO" => $MODULO,"ID[!]"=>$ID]]);
if ($count > 0) {
	valida_parametro_and_die("","El Módulo que estás intentando capturar ya existe.");
}
$FECHA_MODIFICACION = date('Y/m/d H:i:s');

$id = $database->update("MODULOS", [
	"MODULO" => $MODULO,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
],["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
?> 
