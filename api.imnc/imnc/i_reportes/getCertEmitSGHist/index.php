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

//Funcion para redondear
function redondeado ($numero, $decimales) { 
   $factor = pow(10, $decimales); 
   return (round($numero*$factor)/$factor); 
  }
   
   
$respuesta=array(); 
$datos1= array();
//Constantes
$ano_curso = date('Y');
$ano_1 = $ano_curso - 1;
$ano_2 = $ano_curso - 2;
$ano_3 = $ano_curso - 3;
$ano_4 = $ano_curso - 4;
//INICIALIZANDO VARIABLES

		$datos1['X'][0]= $ano_curso;
		$datos1['X'][1]= $ano_1;
		$datos1['X'][2]= $ano_2;
 		$datos1['X'][3]= $ano_3;
 		$datos1['X'][4]= $ano_4;
 		
		$datos1['Y1'][0]= '';	
		$datos1['Y1'][1]= '';
		$datos1['Y1'][2]= '';	
		$datos1['Y1'][3]= '';
		$datos1['Y1'][4]= '';	
		
			
				
				
		
		
// ANO ACTUAL
$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_curso."%' ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_curso."%'  ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y1'][0] = ''; 
		
	}
	else{
		$datos1['Y1'][0] = $dd0[0]['CANT_CERT_EMITIDOS_SG']; 
		
	}
// ANO ACTUAL - 1
$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_1."%' ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_1."%'  ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y1'][1] = ''; 
	}
	else{
		$datos1['Y1'][1] = $dd1[0]['CANT_CERT_EMITIDOS_SG'];   
	}
// ANO ACTUAL - 2
$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_2."%' ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_2."%'  ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y1'][2] = ''; 
	}
	else{
		$datos1['Y1'][2] = $dd2[0]['CANT_CERT_EMITIDOS_SG'];   
	}
// ANO ACTUAL - 3
$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_3."%' ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_3."%'  ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y1'][3] = ''; 
	}
	else{
		$datos1['Y1'][3] = $dd3[0]['CANT_CERT_EMITIDOS_SG'];   
	}
// ANO ACTUAL - 4
$consulta = "SELECT COUNT(`STATUS`) AS `CANT_CERT_EMITIDOS_SG` FROM `DICTAMINACIONES` WHERE `STATUS`='1' AND `FECHA_MODIFICACION` LIKE '".$ano_4."%' ";
//$consulta = "SELECT `SECTOR_PUBLICO`,`SECTOR_PRIVADO` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_4."%'  ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y1'][4] = ''; 
	}
	else{
		$datos1['Y1'][4] = $dd4[0]['CANT_CERT_EMITIDOS_SG'];   
	}
/**************************************/
print_r(json_encode($datos1));

?> 
