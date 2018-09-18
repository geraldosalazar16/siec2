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

$ID_CLIENTE = $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Falta ID_CLIENTE");

$NOMBRE_DOMICILIO = $objeto->NOMBRE_DOMICILIO;
valida_parametro_and_die($NOMBRE_DOMICILIO, "Es necesario capturar el nombre de domicilio");

$CALLE = $objeto->CALLE;
valida_parametro_and_die($CALLE, "Es necesario capturar la calle");

$NUMERO_EXTERIOR = $objeto->NUMERO_EXTERIOR;
valida_parametro_and_die($NUMERO_EXTERIOR, "Es necesario capturar el número exterior");

$NUMERO_INTERIOR = $objeto->NUMERO_INTERIOR; // opcional

$COLONIA_BARRIO = $objeto->COLONIA_BARRIO;
valida_parametro_and_die($COLONIA_BARRIO, "Es necesario capturar la colonia");

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

$ES_FISCAL = $objeto->ES_FISCAL;
valida_parametro_and_die($ES_FISCAL, "Es necesario indicar si es domicilio fiscal");

//Validar que solo puede haber una domicilio fiscal por cliente
$total_con_fiscal = $database->count("CLIENTES_DOMICILIOS", "*", ["AND"=>["ID_CLIENTE"=>$ID_CLIENTE, "ES_FISCAL"=>"si"]]);
valida_error_medoo_and_die();

if ($ES_FISCAL == 'si' && $total_con_fiscal > 0) {
	$respuesta['resultado']="error";
	$respuesta['mensaje']="Solo puede haber un domicilio fiscal por cliente";
	print_r(json_encode($respuesta));
	die();
}

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$client_dom = $database->insert("CLIENTES_DOMICILIOS", 
	[
	"ID_CLIENTE" => $ID_CLIENTE,
	"NOMBRE_DOMICILIO" => $NOMBRE_DOMICILIO,
	"CALLE" => $CALLE,
	"NUMERO_EXTERIOR" => $NUMERO_EXTERIOR,
	"NUMERO_INTERIOR" => $NUMERO_INTERIOR,
	"COLONIA_BARRIO" => $COLONIA_BARRIO,
	"DELEGACION_MUNICIPIO" => $DELEGACION_MUNICIPIO,
	"ENTIDAD_FEDERATIVA" => $ENTIDAD_FEDERATIVA,
	"CP" => $CP,
	"PAIS" => $PAIS,
	"ES_FISCAL" => $ES_FISCAL,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$client_dom;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>