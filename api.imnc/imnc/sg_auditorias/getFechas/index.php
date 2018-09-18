<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

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

$respuesta["FILTROS"] = $objeto;

$TIPO_SERVICIO = $objeto->TIPO_SERVICIO;
$SECTOR = $objeto->SECTOR;
$REFERENCIA = $objeto->REFERENCIA;
$CLIENTE = $objeto->CLIENTE;


$whereTIPO_SERVICIO = "";
$whereSECTOR = "";
$whereREFERENCIA = "";
$whereCLIENTE = "";

$whereTIPO_SERVICIO = "";
if ($TIPO_SERVICIO != "") {
	$whereTIPO_SERVICIO = " AND SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO = " . $database->quote($TIPO_SERVICIO) . " ";
}

$whereSECTOR = "";
if ($SECTOR != "") {
	$whereSECTOR = " AND SECTORES.ID_SECTOR = " . $database->quote($SECTOR) . " ";
}

$whereREFERENCIA = "";
if ($REFERENCIA != "") {
	$whereREFERENCIA = " AND SERVICIO_CLIENTE_ETAPA.REFERENCIA = " . $database->quote($REFERENCIA) . " ";
}

$whereCLIENTE = "";
if ($CLIENTE != "") {
	$whereCLIENTE = " AND CLIENTES.ID = " . $database->quote($CLIENTE) . " ";
}


$strQuery = "SELECT DISTINCT SG_AUDITORIA_FECHAS.ID, SG_AUDITORIA_FECHAS.FECHA FECHA_AUDITORIA, SG_AUDITORIAS.ID ID_AUDITORIA, 
					SG_AUDITORIAS.DURACION_DIAS, SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO, 
					SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA, SG_AUDITORIAS.ID_SG_TIPO_SERVICIO, 
					SERVICIO_CLIENTE_ETAPA.REFERENCIA, CLIENTES.ID
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SG_AUDITORIA_FECHAS, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND
      				SG_AUDITORIAS.ID = SG_AUDITORIA_FECHAS.ID_SG_AUDITORIA AND
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO " .
      				$whereTIPO_SERVICIO . $whereSECTOR . $whereREFERENCIA . $whereCLIENTE .
      		"ORDER BY SG_AUDITORIA_FECHAS.FECHA ";
//print_r($strQuery);

$arreglo_fechas = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();

//print_r($database->last_query());

$respuesta["FECHAS"] = $arreglo_fechas;


//----- Filtro por TIPOS_SERVICIO -----

$strQuery = "SELECT DISTINCT SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO, TIPOS_SERVICIO.NOMBRE 
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES, TIPOS_SERVICIO 
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO AND 
      				TIPOS_SERVICIO.ID = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO " .
      				$whereTIPO_SERVICIO . $whereSECTOR . $whereREFERENCIA . $whereCLIENTE;

$arreglo_tipos_servicio = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();
$respuesta["TIPOS_SERVICIO"] = $arreglo_tipos_servicio;

//----- Filtro por SECTORES -----

$strQuery = "SELECT DISTINCT SECTORES.ID_SECTOR, SECTORES.NOMBRE 
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES 
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND 
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND 
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND 
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO " .
      				$whereTIPO_SERVICIO . $whereSECTOR . $whereREFERENCIA . $whereCLIENTE;

$arreglo_sectores = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();
$respuesta["SECTORES"] = $arreglo_sectores;

//----- Filtro por REFERENCIA -----

$strQuery = "SELECT DISTINCT SERVICIO_CLIENTE_ETAPA.REFERENCIA 
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND 
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND 
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND 
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO " .
      				$whereTIPO_SERVICIO . $whereSECTOR . $whereREFERENCIA . $whereCLIENTE;

$arreglo_referencias = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();
$respuesta["REFERENCIAS"] = $arreglo_referencias;

//----- Filtro por CLIENTES -----

$strQuery = "SELECT DISTINCT SERVICIO_CLIENTE_ETAPA.ID_CLIENTE, CLIENTES.NOMBRE  
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND 
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND 
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND 
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO " .
      				$whereTIPO_SERVICIO . $whereSECTOR . $whereREFERENCIA . $whereCLIENTE;

$arreglo_cliente = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();
$respuesta["CLIENTES"] = $arreglo_cliente;

print_r(json_encode($respuesta));


?>