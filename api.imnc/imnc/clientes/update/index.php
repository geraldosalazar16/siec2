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
valida_parametro_and_die($ID_CLIENTE, "Falta ID de cliente");

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE,"Es necesario capturar el nombre del cliente");

if ($NOMBRE != '') {
	$NOMBRE_ACTUAL = $database->get("CLIENTES","NOMBRE",["ID"=>$ID_CLIENTE]);
	if (trim($NOMBRE) != trim($NOMBRE_ACTUAL)){
		$count_nombre = $database->count("CLIENTES", ["NOMBRE" => $NOMBRE]);
		if ($count_nombre > 0) {
			imprime_error_and_die("El NOMBRE que estás intentando capturar ya existe.");
		}	
	}
}

$RFC = $objeto->RFC;

if ($RFC != '') {
	$RFC_ACTUAL = $database->get("CLIENTES","RFC",["ID"=>$ID_CLIENTE]);
	if (trim($RFC) != trim($RFC_ACTUAL)){
		$count_rfc = $database->count("CLIENTES", ["RFC" => $RFC]);
		if ($count_rfc > 0) {
			imprime_error_and_die("El RFC que estás intentando capturar ya existe.");
		}	
	}
}
else{
	$RFC = NULL;
}


$ES_FACTURARIO = $objeto->ES_FACTURARIO;
valida_parametro_and_die($ES_FACTURARIO,"Es necesario seleccionar si es facturario");
if ($RFC_FACTURARIO != '') {
	$count_rfc_facturario = $database->count("CLIENTES", ["RFC" => $RFC_FACTURARIO]);	
	if ($count_nombre > 0 && $count_rfc_facturario > 0) {
		imprime_error_and_die("RFC FACTURARIO que estás intentando capturar ya existe.");
	}
}
else{
	$RFC_FACTURARIO = NULL;
}

//$TIENE_FACTURARIO =  "N";

//$ID_CLIENTE_FACTURARIO = $objeto->ID_CLIENTE_FACTURARIO; // opcional

$CLIENTE_FACTURARIO = $objeto->CLIENTE_FACTURARIO; // opcional

$RFC_FACTURARIO = $objeto->RFC_FACTURARIO; // opcional

$ID_TIPO_PERSONA = $objeto->ID_TIPO_PERSONA;
valida_parametro_and_die($ID_TIPO_PERSONA,"Es necesario capturar el tipo de persona");

$ID_TIPO_ENTIDAD = $objeto->ID_TIPO_ENTIDAD;
valida_parametro_and_die($ID_TIPO_ENTIDAD,"Es necesario capturar el tipo de entidad");

//$UNICA_RAZON_SOCIAL = "N";


//$OTRAS_RAZONES_SOCIALES = $objeto->OTRAS_RAZONES_SOCIALES; //ARRAY


$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("CLIENTES", [
	"ID_TIPO_PERSONA" => $ID_TIPO_PERSONA,
	"ID_TIPO_ENTIDAD" => $ID_TIPO_ENTIDAD,
	//"UNICA_RAZON_SOCIAL" => $UNICA_RAZON_SOCIAL,
	//"ID_CLIENTE_FACTURARIO" => $ID_CLIENTE_FACTURARIO,
	"CLIENTE_FACTURARIO"=>$CLIENTE_FACTURARIO,
	"RFC_FACTURARIO"=>$RFC_FACTURARIO,
	"NOMBRE" => $NOMBRE,
	"RFC" => $RFC,
	"ES_FACTURARIO" => $ES_FACTURARIO,
	//"TIENE_FACTURARIO" => $TIENE_FACTURARIO,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID_CLIENTE]);
valida_error_medoo_and_die();

$id = $database->delete("CLIENTES_RAZONES_SOCIALES", ["ID_CLIENTE" => $ID_CLIENTE]); 
valida_error_medoo_and_die(); 



$respuesta['resultado']="ok";
$respuesta['mensaje']="Los datos del cliente han sido actualizados";
print_r(json_encode($respuesta));


//-------- FIN --------------
?>