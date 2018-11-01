<?php 
include  '../../common/conn-apiserver.php'; 

include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 


function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}

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
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Es necesario capturar un id de evento");

$EVENTO = $objeto->EVENTO;
valida_parametro_and_die($EVENTO, "Es necesario capturar un evento");

$FECHA_INICIO = $objeto->FECHA_INICIO;
valida_parametro_and_die($FECHA_INICIO, "Es necesario capturar una fecha de inicio");

$FECHA_FIN = $objeto->FECHA_FIN;
valida_parametro_and_die($FECHA_FIN, "Es necesario capturar una fecha de fin");
$anhio = intval(substr($FECHA_INICIO,0,4));
$mes = intval(substr($FECHA_INICIO,5,2));
$dia = intval(substr($FECHA_INICIO,8,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de inicio no es válida");
}
$anhio = intval(substr($FECHA_FIN,0,4));
$mes = intval(substr($FECHA_FIN,5,2));
$dia = intval(substr($FECHA_FIN,8,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de fin no es válida");
}
if ($FECHA_INICIO > $FECHA_FIN) {
	imprime_error_and_die("La fecha de inicio no puede ser después de la fecha final");
}

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id = $database->update("PERSONAL_TECNICO_EVENTOS", [
	"EVENTO" => $EVENTO,
    "FECHA_INICIO" => $FECHA_INICIO,
    "HORA_INICIO" => "7:00",
    "FECHA_FIN" => $FECHA_FIN,
    "HORA_FIN" => "18:30",
	"FECHA_MODIFICACION" => $FECHA_CREACION,
	"HORA_MODIFICACION" => $HORA_CREACION,
	"USUARIO_MODIFICACION" => $ID_USUARIO_CREACION
], ["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
print_r(json_encode($respuesta));


//-------- FIN --------------
?>