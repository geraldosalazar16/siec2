<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include '../../ex_common/archivos.php';
include 'funciones.php';

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

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario capturar un nombre");

$APELLIDO_MATERNO = $objeto->APELLIDO_MATERNO;
valida_parametro_and_die($APELLIDO_MATERNO, "Es necesario capturar un apellido materno");

$APELLIDO_PATERNO = $objeto->APELLIDO_PATERNO;
valida_parametro_and_die($APELLIDO_PATERNO, "Es necesario capturar un apellido paterno");

$INICIALES = $objeto->INICIALES;
valida_parametro_and_die($INICIALES, "Es necesario capturar las iniciales");
$count_iniciales = $database->count("PERSONAL_TECNICO", ["INICIALES" => $INICIALES]);
if ($count_iniciales > 0) {
	imprime_error_and_die("Las iniciales que estás intentando capturar ya existen, por favor captura unas iniciales distintas");
}

$FECHA_NACIMIENTO = $objeto->FECHA_NACIMIENTO;
valida_parametro_and_die($FECHA_NACIMIENTO, "Es necesario capturar una fecha de nacimiento");

$CURP = $objeto->CURP;
valida_parametro_and_die($CURP, "Es necesario capturar el CURP");

if ($CURP != '') {
	$count_curp = $database->count("PERSONAL_TECNICO", ["CURP" => $CURP]);
	if ($count_curp > 0) {
		imprime_error_and_die("El CURP que estás intentando capturar ya existe.");
	}
}
else{
	$CURP = NULL;
}

$RFC = $objeto->RFC;
valida_parametro_and_die($RFC, "Es necesario capturar el RFC");
$count_rfc = $database->count("PERSONAL_TECNICO", ["RFC" => $RFC]);
if ($count_rfc > 0) {
	imprime_error_and_die("El RFC que estás intentando capturar ya existe.");
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

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id = $database->insert("PERSONAL_TECNICO", [
	"NOMBRE"=>$NOMBRE,
	"APELLIDO_MATERNO"=>$APELLIDO_MATERNO,
	"APELLIDO_PATERNO"=>$APELLIDO_PATERNO,
	"INICIALES"=>$INICIALES,
	"FECHA_NACIMIENTO"=>$FECHA_NACIMIENTO,
	"CURP"=>$CURP,
	"RFC"=>$RFC,
	"TELEFONO_FIJO" => $TELEFONO_FIJO,
	"TELEFONO_CELULAR" => $TELEFONO_CELULAR,
	"EMAIL" => $EMAIL,
	"EMAIL2" => $EMAIL2,
	"PADRON"=>$PADRON,
	"STATUS"=>$STATUS,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);

valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$id;
creacion_expediente_registro($id,2, $rutaExpediente, $database);
crea_instancia_expedientes_registro($id,2, $database);
print_r(json_encode($respuesta));

//creacion_expediente_registro(177,2, $rutaExpediente, $database);
//-------- FIN --------------
?>