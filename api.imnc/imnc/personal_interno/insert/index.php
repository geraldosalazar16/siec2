<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include '../../ex_common/archivos.php';

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

$NO = $objeto->NO;
valida_parametro_and_die($NO, "Es necesario capturar un No. empleado");
if ($NO != '') {
    $count_curp = $database->count("PERSONAL_INTERNO", ["NO_EMPLEADO" => $NO]);
    if ($count_curp > 0) {
        imprime_error_and_die("El No. de empleado que estás intentando capturar ya existe.");
    }
}

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario capturar un nombre");

$APELLIDO_PATERNO = $objeto->APELLIDO_PATERNO;
valida_parametro_and_die($APELLIDO_PATERNO, "Es necesario capturar un apellido paterno");

$APELLIDO_MATERNO = $objeto->APELLIDO_MATERNO;
valida_parametro_and_die($APELLIDO_MATERNO, "Es necesario capturar un apellido materno");

$CURP = $objeto->CURP;
valida_parametro_and_die($CURP, "Es necesario capturar el CURP");
if ($CURP != '') {
    $count_curp = $database->count("PERSONAL_INTERNO", ["CURP" => $CURP]);
    if ($count_curp > 0) {
        imprime_error_and_die("El CURP que estás intentando capturar ya existe.");
    }
}

$FECHA_NACIMIENTO = $objeto->FECHA;
valida_parametro_and_die($FECHA_NACIMIENTO, "Es necesario capturar una fecha de nacimiento");

$SEXO = $objeto->SEXO;
valida_parametro_and_die($SEXO, "Es necesario capturar el sexo");

$ESTADO_CIVIL = $objeto->ESTADO_CIVIL;
valida_parametro_and_die($ESTADO_CIVIL, "Es necesario capturar el estado civil");

$NO_SEGURIDAD = $objeto->NO_SEGURIDAD;
valida_parametro_and_die($NO_SEGURIDAD, "Es necesario capturar el No. de seguridad social");
if ($NO_SEGURIDAD != '') {
    $count_curp = $database->count("PERSONAL_INTERNO", ["NO_SEGURO_SOCIAL" => $NO_SEGURIDAD]);
    if ($count_curp > 0) {
        imprime_error_and_die("El No. de seguridad social que estás intentando capturar ya existe.");
    }
}


$TELEFONO = $objeto->TELEFONO;
valida_parametro_and_die($TELEFONO, "Es necesario capturar un teléfono ");
if (!is_numeric($TELEFONO) || intval($TELEFONO) < 0) {
	imprime_error_and_die("Verifica que el teléfono fijo sea un número y sea mayor o igual a cero");
}

$EMAIL = $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario capturar un email");
if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
  imprime_error_and_die("El email debe tener un formato válido (e.j. tu.nombre@tuempresa.com)");
}

$DIRECCION = $objeto->DIRECCION;
valida_parametro_and_die($DIRECCION, "Es necesario capturar la dirección");

$ESTADO = $objeto->ESTADO;
valida_parametro_and_die($ESTADO,"Falta si es alta o baja");

$FECHA_NACIMIENTO = explode("/",$FECHA_NACIMIENTO);
$FECHA_NACIMIENTO = date("Y-m-d", strtotime($FECHA_NACIMIENTO[2].$FECHA_NACIMIENTO[1].$FECHA_NACIMIENTO[0]));

$id = $database->insert("PERSONAL_INTERNO", [
    "NO_EMPLEADO" => $NO,
	"NOMBRE"=>$NOMBRE,
	"APELLIDO_MATERNO"=>$APELLIDO_MATERNO,
	"APELLIDO_PATERNO"=>$APELLIDO_PATERNO,
    "FECHA_NACIMIENTO"=>$FECHA_NACIMIENTO,
    "SEXO"=>$SEXO,
    "ESTADO_CIVIL"=>$ESTADO_CIVIL,
    "CURP"=>$CURP,
	"NO_SEGURO_SOCIAL"=>$NO_SEGURIDAD,
	"TELEFONO" => $TELEFONO,
	"DIRECCION" => $DIRECCION,
	"EMAIL" => $EMAIL,
    "ISACTIVO"=>$ESTADO

]);

valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$id;

print_r(json_encode($respuesta));


?>