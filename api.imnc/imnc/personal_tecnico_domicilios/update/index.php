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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$ID = $objeto->ID;

$NOMBRE_DOMICILIO = $objeto->NOMBRE_DOMICILIO;
valida_parametro_and_die($NOMBRE_DOMICILIO, "Es necesario capturar un nombre del domicilio");

$CALLE = $objeto->CALLE;
valida_parametro_and_die($CALLE, "Es necesario capturar la calle");

$NUMERO_EXTERIOR = $objeto->NUMERO_EXTERIOR;
valida_parametro_and_die($NUMERO_EXTERIOR, "Es necesario capturar un No. exterior");

$NUMERO_INTERIOR = $objeto->NUMERO_INTERIOR; // opcional

$COLONIA_BARRIO = $objeto->COLONIA_BARRIO;
valida_parametro_and_die($COLONIA_BARRIO, "Es necesario capturar la colonia o barrio");

$DELEGACION_MUNICIPIO = $objeto->DELEGACION_MUNICIPIO;
valida_parametro_and_die($DELEGACION_MUNICIPIO, "Es necesario capturar la delegación o municipio");

$ENTIDAD_FEDERATIVA = $objeto->ENTIDAD_FEDERATIVA;
valida_parametro_and_die($ENTIDAD_FEDERATIVA, "Es necesario capturar la entidad federativa");

$CP = $objeto->CP;
valida_parametro_and_die($CP, "Es necesario capturar el código postal");
if (!is_numeric($CP) || intval($CP) < 0) {
	imprime_error_and_die("Verifica que el código postal sea un número y sea mayor o igual a cero");
}

$PAIS = $objeto->PAIS;
valida_parametro_and_die($PAIS, "Es necesario capturar el país");

$FECHA_INICIO = $objeto->FECHA_INICIO;
valida_parametro_and_die($FECHA_INICIO, "Es necesario capturar una fecha de inicio");
if (strlen($FECHA_INICIO) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de inicio");
}
$anhio = intval(substr($FECHA_INICIO,0,4));
$mes = intval(substr($FECHA_INICIO,4,2));
$dia = intval(substr($FECHA_INICIO,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de inicio no es válida");
}

$FECHA_FIN = $objeto->FECHA_FIN;
valida_parametro_and_die($FECHA_FIN, "Es necesario capturar una fecha de fin");
if (strlen($FECHA_FIN) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de fin");
}
$anhio = intval(substr($FECHA_FIN,0,4));
$mes = intval(substr($FECHA_FIN,4,2));
$dia = intval(substr($FECHA_FIN,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de fin no es válida");
}
if ($FECHA_INICIO > $FECHA_FIN) {
	imprime_error_and_die("La fecha de inicio no puede ser después de la fecha final");
}

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("PERSONAL_TECNICO_DOMICILIOS", [
	"NOMBRE_DOMICILIO" => $NOMBRE_DOMICILIO,
	"CALLE" => $CALLE,
	"NUMERO_EXTERIOR" => $NUMERO_EXTERIOR,
	"NUMERO_INTERIOR" => $NUMERO_INTERIOR,
	"COLONIA_BARRIO" => $COLONIA_BARRIO,
	"DELEGACION_MUNICIPIO" => $DELEGACION_MUNICIPIO,
	"ENTIDAD_FEDERATIVA" => $ENTIDAD_FEDERATIVA,
	"CP" => $CP,
	"PAIS" => $PAIS,
	"FECHA_INICIO" => $FECHA_INICIO,
	"FECHA_FIN" => $FECHA_FIN,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
print_r(json_encode($respuesta));


//-------- FIN --------------
?>