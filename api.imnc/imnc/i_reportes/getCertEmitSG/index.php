<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error"; 
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
$datos1= array();
//Constantes
$ano_curso = date('Y');
$ano_anterior = $ano_curso - 1;
$mes_curso = date('m')-1;
	
//$datos1['X'][$ix] = '';
//INICIALIZANDO VARIABLES
	$datos1['X'][0]= 'Enero';
	$datos1['X'][1]= 'Febrero';
	$datos1['X'][2]= 'Marzo';
 	$datos1['X'][3]= 'Abril';
 	$datos1['X'][4]= 'Mayo';
	$datos1['X'][5]= 'Junio';
	$datos1['X'][6]= 'Julio';
	$datos1['X'][7]= 'Agosto';
	$datos1['X'][8]= 'Septiembre';
	$datos1['X'][9]= 'Octubre';
	$datos1['X'][10]= 'Noviembre';
	$datos1['X'][11]= 'Diciembre';


	
	
	//MES DE ENERO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."01%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][0] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE FEBRERO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."02%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][1] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE MARZO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."03%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][2] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE ABRIL
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."04%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][3] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE MAYO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."05%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][4] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE JUNIO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."06%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][5] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE JULIO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."07%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][6] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE AGOSTO
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."08%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][7] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE SEPTIEMBRE
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."09%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][8] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE OCTUBRE
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."10%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][9] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE NOVIEMBRE
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."11%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][10] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
	//MES DE DICIEMBRE
	$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."12%' ";
	$datos = $database->query($consulta)->fetchAll();
	$datos1['Y1'][11] = $datos[0]['CANT_CERT_EMITIDOS_SG'];
/**************************************/
print_r(json_encode($datos1));

?> 
