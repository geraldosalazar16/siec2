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
ON CLIENTES.ID = SERVICIO_CLIENTE_ETAPA.ID_CLIENTE";
//print_r($strQuery);

$arreglo_fechas = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();

//print_r($database->last_query());

$respuesta["FECHAS"] = $arreglo_fechas;

print_r(json_encode($respuesta));


?>