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
valida_parametro_and_die($ID, "Falta ID de personal técnico");

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario capturar un nombre");

$APELLIDO_MATERNO = $objeto->APELLIDO_MATERNO;
valida_parametro_and_die($APELLIDO_MATERNO, "Es necesario capturar un apellido materno");

$APELLIDO_PATERNO = $objeto->APELLIDO_PATERNO;
valida_parametro_and_die($APELLIDO_PATERNO, "Es necesario capturar un apellido paterno");

$INICIALES = $objeto->INICIALES;
valida_parametro_and_die($INICIALES, "Es necesario capturar las iniciales");
$INICIALES_ACTUALES = $database->get("PERSONAL_TECNICO","INICIALES",["ID"=>$ID]);
if (trim($INICIALES) != trim($INICIALES_ACTUALES)){
	$count_iniciales = $database->count("PERSONAL_TECNICO", ["INICIALES" => $INICIALES]);
	if ($count_iniciales > 0) {
	imprime_error_and_die("Las iniciales que estás intentando capturar ya existen, por favor captura unas iniciales distintas");
	}	
}

$FECHA_NACIMIENTO = $objeto->FECHA_NACIMIENTO;
valida_parametro_and_die($FECHA_NACIMIENTO, "Es necesario capturar una fecha de nacimiento");
if (strlen($FECHA_NACIMIENTO) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de nacimiento");
}
$anhio_nacimiento = intval(substr($FECHA_NACIMIENTO,0,4));
$mes_nacimiento = intval(substr($FECHA_NACIMIENTO,4,2));
$dia_nacimiento = intval(substr($FECHA_NACIMIENTO,6,2));
if (!checkdate($mes_nacimiento , $dia_nacimiento, $anhio_nacimiento)){
	imprime_error_and_die("La fecha de nacimiento no es válida");
}
if ($FECHA_NACIMIENTO >= date("Ymd")) {
	imprime_error_and_die("La fecha de nacimiento no puede ser una fecha futura");
}

$CURP = $objeto->CURP;
valida_parametro_and_die($CURP, "Es necesario capturar el CURP");

$RFC = $objeto->RFC;
valida_parametro_and_die($RFC, "Es necesario capturar el RFC");
$RFC_ACTUAL = $database->get("PERSONAL_TECNICO","RFC",["ID"=>$ID]);
if (trim($RFC) != trim($RFC_ACTUAL)){
	$count_rfc = $database->count("PERSONAL_TECNICO", ["RFC" => $RFC]);
	if ($count_rfc > 0) {
		imprime_error_and_die("El RFC que estás intentando capturar ya existe.");
	}	
}

$TELEFONO_FIJO = $objeto->TELEFONO_FIJO;
valida_parametro_and_die($TELEFONO_FIJO, "Es necesario capturar un teléfono fijo");
if (!is_numeric($TELEFONO_FIJO) || intval($TELEFONO_FIJO) < 0) {
	imprime_error_and_die("Verifica que el teléfono fijo sea un número y sea mayor o igual a cero");
}

$TELEFONO_CELULAR = $objeto->TELEFONO_CELULAR; // opcional
if ($TELEFONO_CELULAR != "" && (!is_numeric($TELEFONO_CELULAR) || intval($TELEFONO_CELULAR) < 0)) {
	imprime_error_and_die("Verifica que el teléfono celular sea un número y sea mayor o igual a cero");
}

$EMAIL = $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario capturar un email");
if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
  imprime_error_and_die("El email debe tener un formato válido (e.j. tu.nombre@tuempresa.com)");
}
$EMAIL2 = $objeto->EMAIL2;
$PADRON = $objeto->PADRON;
valida_error_medoo_and_die($PADRON, "Es necesario escoger una opción");

$STATUS = $objeto->STATUS;
valida_parametro_and_die($STATUS, "Es necesario capturar un status");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("PERSONAL_TECNICO", [
	"NOMBRE" => $NOMBRE,
	"APELLIDO_MATERNO" => $APELLIDO_MATERNO,
	"APELLIDO_PATERNO" => $APELLIDO_PATERNO,
	"INICIALES" => $INICIALES,
	"FECHA_NACIMIENTO" => $FECHA_NACIMIENTO,
	"CURP" => $CURP,
	"RFC" => $RFC,
	"TELEFONO_FIJO" => $TELEFONO_FIJO,
	"TELEFONO_CELULAR" => $TELEFONO_CELULAR,
	"EMAIL" => $EMAIL,
	"EMAIL2" => $EMAIL2,
	"PADRON" => $PADRON,
	"STATUS" => $STATUS,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
print_r(json_encode($respuesta));


//-------- FIN --------------
?>