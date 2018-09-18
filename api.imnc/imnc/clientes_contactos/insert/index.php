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


$ID_CLIENTE_DOMICILIO = $objeto->ID_CLIENTE_DOMICILIO;
valida_parametro_and_die($ID_CLIENTE_DOMICILIO, "Falta ID_CLIENTE_DOMICILIO");

$ID_TIPO_CONTACTO = $objeto->ID_TIPO_CONTACTO;
valida_parametro_and_die($ID_TIPO_CONTACTO, "Es necesario capturar el tipo de contacto");

$NOMBRE_CONTACTO = $objeto->NOMBRE_CONTACTO;
valida_parametro_and_die($NOMBRE_CONTACTO, "Es necesario capturar el nombre de contacto");

$CARGO = $objeto->CARGO;
valida_parametro_and_die($CARGO, "Es necesario capturar el cargo del contacto");

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
$ES_PRINCIPAL = $objeto->ES_PRINCIPAL;
valida_parametro_and_die($ES_PRINCIPAL, "Es necesario indicar si es contacto principal");


$TELEFONO_MOVIL = $objeto->TELEFONO_MOVIL; // opcional
if ($TELEFONO_MOVIL != "" && (!is_numeric($TELEFONO_MOVIL) || intval($TELEFONO_MOVIL) < 0)) {
	imprime_error_and_die("Verifica que el teléfono móvil sea un número y sea mayor o igual a cero");
}

$TELEFONO_FIJO = $objeto->TELEFONO_FIJO;
valida_parametro_and_die($TELEFONO_FIJO, "Es necesario capturar el teléfono fijo");
if (!is_numeric($TELEFONO_FIJO) || intval($TELEFONO_FIJO) < 0) {
	imprime_error_and_die("Verifica que el teléfono fijo sea un número y sea mayor o igual a cero");
}

$EXTENSION = $objeto->EXTENSION; // opcional
if ($EXTENSION != "" && (!is_numeric($EXTENSION) || intval($EXTENSION) < 0)) {
	imprime_error_and_die("Verifica que la extensión sea un número y sea mayor o igual a cero");
}

$EMAIL = $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario capturar un email");
if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
  imprime_error_and_die("El email debe tener un formato válido (e.j. tu.nombre@tuempresa.com)");
}

//Validar que solo puede haber un contacto principal por domicilio
$total_con_principal = $database->count("CLIENTES_CONTACTOS", "*", ["AND"=>["ID_CLIENTE_DOMICILIO"=>$ID_CLIENTE_DOMICILIO, "ES_PRINCIPAL"=>"si"]]);
valida_error_medoo_and_die();

if ($ES_PRINCIPAL == 'si' && $total_con_principal > 0) {
	imprime_error_and_die("Solo puede haber un contacto principal por domicilio");
}

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$DATOS_ADICIONALES = $objeto->DATOS_ADICIONALES;
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");


$client_contact = $database->insert("CLIENTES_CONTACTOS", [
	"ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO,
	"ID_TIPO_CONTACTO" => $ID_TIPO_CONTACTO,
	"NOMBRE_CONTACTO" => $NOMBRE_CONTACTO,
	"CARGO" => $CARGO,
	"TELEFONO_MOVIL" => $TELEFONO_MOVIL,
	"TELEFONO_FIJO" => $TELEFONO_FIJO,
	"EXTENSION" => $EXTENSION,
	"EMAIL" => $EMAIL,
	"DATOS_ADICIONALES" => $DATOS_ADICIONALES,
	"FECHA_INICIO" => $FECHA_INICIO,
	"FECHA_FIN" => $FECHA_FIN,
	"ES_PRINCIPAL" => $ES_PRINCIPAL,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$client_contact;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>