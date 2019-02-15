<?php 

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error\n"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$TIPO_SERVICIO = $objeto->TIPO_SERVICIO;
//$SECTOR = $objeto->SECTOR;
$REFERENCIA = $objeto->REFERENCIA;
$CLIENTE = $objeto->CLIENTE;


$whereTIPO_SERVICIO = "";
$whereSECTOR = "";
$whereREFERENCIA = "";
$whereCLIENTE = "";
$where = " WHERE 1 ";

$whereTIPO_SERVICIO = "";
if ($TIPO_SERVICIO != "") {
	$whereTIPO_SERVICIO = " AND SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO = " . $database->quote($TIPO_SERVICIO) . " ";
}
/*
$whereSECTOR = "";
if ($SECTOR != "") {
	$whereSECTOR = " AND SECTORES.ID_SECTOR = " . $database->quote($SECTOR) . " ";
}
*/
$whereREFERENCIA = "";
if ($REFERENCIA != "") {
	$whereREFERENCIA = " AND SERVICIO_CLIENTE_ETAPA.REFERENCIA = " . $database->quote($REFERENCIA) . " ";
}

$whereCLIENTE = "";
if ($CLIENTE != "") {
	$whereCLIENTE = " AND CLIENTES.ID = " . $database->quote($CLIENTE) . " ";
}

$strQuery = "SELECT DISTINCT 
I_SG_AUDITORIA_FECHAS.ID, 
I_SG_AUDITORIA_FECHAS.FECHA FECHA_AUDITORIA,
I_SG_AUDITORIAS.DURACION_DIAS, 
SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO, 
I_SG_AUDITORIAS.ID_SERVICIO_CLIENTE_ETAPA,
I_SG_AUDITORIAS.CICLO,
I_SG_AUDITORIAS.TIPO_AUDITORIA,  
SERVICIO_CLIENTE_ETAPA.REFERENCIA, 
CLIENTES.ID
FROM 
I_SG_AUDITORIAS
INNER JOIN I_SG_AUDITORIA_FECHAS
ON I_SG_AUDITORIAS.ID_SERVICIO_CLIENTE_ETAPA = I_SG_AUDITORIA_FECHAS.ID_SERVICIO_CLIENTE_ETAPA
AND I_SG_AUDITORIAS.CICLO = I_SG_AUDITORIA_FECHAS.CICLO
AND I_SG_AUDITORIAS.TIPO_AUDITORIA = I_SG_AUDITORIA_FECHAS.TIPO_AUDITORIA
INNER JOIN SERVICIO_CLIENTE_ETAPA
ON I_SG_AUDITORIAS.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID
INNER JOIN CLIENTES
ON CLIENTES.ID = SERVICIO_CLIENTE_ETAPA.ID_CLIENTE" .
$where . $whereTIPO_SERVICIO . $whereREFERENCIA . $whereCLIENTE;


$arreglo_fechas = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();

//print_r($database->last_query());

$respuesta["FECHAS"] = $arreglo_fechas;

print_r(json_encode($respuesta));


?>