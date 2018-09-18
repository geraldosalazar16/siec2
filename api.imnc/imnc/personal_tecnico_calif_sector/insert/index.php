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

$ID_PERSONAL_TECNICO_CALIFICACION = $objeto->ID_PERSONAL_TECNICO_CALIFICACION;
valida_parametro_and_die($ID_PERSONAL_TECNICO_CALIFICACION, "Falta ID_PERSONAL_TECNICO_CALIFICACION");

$ID_SECTOR = $objeto->ID_SECTOR;
valida_parametro_and_die($ID_SECTOR, "Es necesario seleccionar un sector");

$SECTOR_NACE = $objeto->SECTOR_NACE;
valida_parametro_and_die($SECTOR_NACE, "Es necesario capturar un sector nace o seleccionar N/A");

$ESQUEMA_CERTIFICACION = $objeto->ESQUEMA_CERTIFICACION;
valida_parametro_and_die($ESQUEMA_CERTIFICACION, "Es necesario capturar un esquema de certificación");

$ALCANCE = $objeto->ALCANCE;
valida_parametro_and_die($ALCANCE, "Es necesario capturar un alcance");

$APROBACION_UVIC = $objeto->APROBACION_UVIC;
valida_parametro_and_die($APROBACION_UVIC, "Es necesario capturar una aprobación UVIC");

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

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$idPTCS = $database->insert("PERSONAL_TECNICO_CALIF_SECTOR", [
	"ID_PERSONAL_TECNICO_CALIFICACION" => $ID_PERSONAL_TECNICO_CALIFICACION,
	"ID_SECTOR" => $ID_SECTOR,
	"SECTOR_NACE" => $SECTOR_NACE,
	"ESQUEMA_CERTIFICACION" => $ESQUEMA_CERTIFICACION,
	"ALCANCE" => $ALCANCE,
	"APROBACION_UVIC" => $APROBACION_UVIC,
	"FECHA_INICIO" => $FECHA_INICIO,
	"FECHA_FIN" => $FECHA_FIN,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$idPTCS;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>