<?php 
// error_reporting(E_ALL);
// ini_set("display_errors",1);

//include  '../../common/conn-apiserver.php'; 
//include  '../../common/conn-medoo.php'; 
//include  '../../common/conn-sendgrid.php';

include  '../../ex_common/query.php';
include 'funciones.php';
include '../../ex_common/archivos.php';

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE,"Es necesario capturar el nombre del cliente");

$RFC = $objeto->RFC;
//valida_parametro_and_die($RFC,"Es necesario capturar el RFC");
if ($RFC != '') {
	$count_rfc = $database->count("CLIENTES", ["RFC" => $RFC]);
	if ($count_rfc > 0) {
		imprime_error_and_die("El RFC que estás intentando capturar ya existe.");
	}
}
else{
	$RFC = NULL;
}

$ES_FACTURARIO = $objeto->ES_FACTURARIO;
valida_parametro_and_die($ES_FACTURARIO,"Es necesario seleccionar si es facturario");

//$TIENE_FACTURARIO = "N";

//$ID_CLIENTE_FACTURARIO = $objeto->ID_CLIENTE_FACTURARIO; // opcional

$CLIENTE_FACTURARIO = $objeto->CLIENTE_FACTURARIO; // opcional

$RFC_FACTURARIO = $objeto->RFC_FACTURARIO; // opcional


if ($NOMBRE != '' && $RFC_FACTURARIO != '') {
	$count_nombre = $database->count("CLIENTES", ["NOMBRE" => $NOMBRE]);
	$count_rfc_facturario = $database->count("CLIENTES", ["RFC" => $RFC_FACTURARIO]);	
	if ($count_nombre > 0 && $count_rfc_facturario > 0) {
		imprime_error_and_die("El NOMBRE y RFC FACTURARIO que estás intentando capturar ya existe.");
	}
}
else{
	$RFC_FACTURARIO = "";
}


$ID_TIPO_PERSONA = $objeto->ID_TIPO_PERSONA;
valida_parametro_and_die($ID_TIPO_PERSONA,"Es necesario capturar el tipo de persona");

$ID_TIPO_ENTIDAD = $objeto->ID_TIPO_ENTIDAD;
valida_parametro_and_die($ID_TIPO_ENTIDAD,"Es necesario capturar el tipo de entidad");

//$UNICA_RAZON_SOCIAL = "N";

//$OTRAS_RAZONES_SOCIALES = $objeto->OTRAS_RAZONES_SOCIALES; //ARRAY


$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");


$idCliente = $database->insert("CLIENTES", [
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
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die("", "leovardo.quintero@dhttecno.com");


$respuesta['resultado']="ok";
$respuesta['id']=$idCliente;

creacion_expediente_registro($idCliente,1,$rutaExpediente, $database);
crea_instancia_expedientes_registro($idCliente,1,$database);

print_r(json_encode($respuesta));


//-------- FIN --------------
?>