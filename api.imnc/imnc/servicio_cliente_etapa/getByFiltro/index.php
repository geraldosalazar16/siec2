<?php 
 // error_reporting(E_ALL);
 // ini_set("display_errors",1);

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

$NOMBRE_CLIENTE = $objeto->NOMBRE_CLIENTE; //CLIENTES
$NOMBRE_CLIENTE_CONTAINS = $objeto->NOMBRE_CLIENTE_CONTAINS; // contains = 1, starts with = 0

$NOMBRE_SERVICIO = $objeto->NOMBRE_SERVICIO; //CLIENTES
$NOMBRE_SERVICIO_CONTAINS = $objeto->NOMBRE_SERVICIO_CONTAINS; // contains = 1, starts with = 0

$REFERENCIA = $objeto->REFERENCIA; //CLIENTES_DOMICILIOS
$REFERENCIA_CONTAINS = $objeto->REFERENCIA_CONTAINS; // contains = 1, starts with = 0

$ID_SECTOR = $objeto->ID_SECTOR;
$whereID_SECTOR ="";
if($ID_SECTOR != "" && $ID_SECTOR != "TODOS"){
    $whereID_SECTOR = " AND SG_SECTORES.ID_SECTOR = ".$ID_SECTOR;
}
$whereNOMBRE_CLIENTE = "";
if ($NOMBRE_CLIENTE != "") {
	$likeNOMBRE_CLIENTE = "";
	if ($NOMBRE_CLIENTE_CONTAINS) {
		$likeNOMBRE_CLIENTE = "%";
	}
	$whereNOMBRE_CLIENTE = " AND CL.NOMBRE LIKE " . $database->quote($likeNOMBRE_CLIENTE . $NOMBRE_CLIENTE."%");
}



$whereNOMBRE_SERVICIO = "";
if ($NOMBRE_SERVICIO != "") {
	$likeNOMBRE_SERVICIO = "";
	if ($NOMBRE_SERVICIO_CONTAINS) {
		$likeNOMBRE_SERVICIO = "%";
	}
	$whereNOMBRE_SERVICIO = " AND SERVICIOS.NOMBRE LIKE " . $database->quote($likeNOMBRE_SERVICIO . $NOMBRE_SERVICIO."%");

	
}

$whereREFERENCIA = "";
if ($REFERENCIA != "") {
	$likeREFERENCIA = "";
	if ($REFERENCIA_CONTAINS) {
		$likeREFERENCIA = "%";
	}
	$whereREFERENCIA = " AND REFERENCIA LIKE " . $database->quote($likeREFERENCIA . $REFERENCIA."%");
}




$strQuery = "SELECT SCE.ID, ID_CLIENTE, SCE.ID_SERVICIO, REFERENCIA, ID_ETAPA_PROCESO,ETAPA AS NOMBRE_ETAPA, SG_INTEGRAL, CL.NOMBRE AS NOMBRE_CLIENTE, SERVICIOS.NOMBRE AS NOMBRE_SERVICIO
FROM SERVICIO_CLIENTE_ETAPA AS SCE , CLIENTES AS CL, SERVICIOS,ETAPAS_PROCESO,SG_TIPOS_SERVICIO AS STS, SG_SECTORES
WHERE ETAPAS_PROCESO.ID_ETAPA = SCE.ID_ETAPA_PROCESO AND SCE.ID_CLIENTE = CL.ID AND SERVICIOS.ID = SCE.ID_SERVICIO AND SCE.ID = STS.ID_SERVICIO_CLIENTE_ETAPA AND SG_SECTORES.ID_SG_TIPO_SERVICIO = STS.ID".$whereNOMBRE_CLIENTE.$whereNOMBRE_SERVICIO.$whereREFERENCIA.$whereID_SECTOR;
//print_r($strQuery);

$arreglo_clientes = $database->query($strQuery);
valida_error_medoo_and_die();
$arreglo_clientes = $arreglo_clientes->fetchAll();




print_r(json_encode($arreglo_clientes));



?>