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
		
		$datos1['Y2'][0]= '';	
		$datos1['Y2'][1]= '';
		$datos1['Y2'][2]= '';		
		$datos1['Y2'][3]= '';		
		$datos1['Y2'][4]= '';		
				
				
		
		
// ANO ACTUAL
$consulta = "SELECT `AUDITORES_EXTERNOS`,`AUDITORES_INTERNOS` FROM `REPORTES_AUDITORES_CONTRATADOS` WHERE `FECHA` LIKE '".$ano_curso."%'  ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y1'][0] = ''; 
		$datos1['Y2'][0] = ''; 
	}
	else{
		$datos1['Y1'][0] = redondeado($dd0[0]['AUDITORES_EXTERNOS']*100/($dd0[0]['AUDITORES_EXTERNOS']+$dd0[0]['AUDITORES_INTERNOS']),2); 
		$datos1['Y2'][0] = redondeado($dd0[0]['AUDITORES_INTERNOS']*100/($dd0[0]['AUDITORES_EXTERNOS']+$dd0[0]['AUDITORES_INTERNOS']),2); 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `AUDITORES_EXTERNOS`,`AUDITORES_INTERNOS` FROM `REPORTES_AUDITORES_CONTRATADOS` WHERE `FECHA` LIKE '".$ano_1."%'  ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y1'][1] = ''; 
		$datos1['Y2'][1] = ''; 
	}
	else{
		$datos1['Y1'][1] = redondeado($dd1[0]['AUDITORES_EXTERNOS']*100/($dd1[0]['AUDITORES_EXTERNOS']+$dd1[0]['AUDITORES_INTERNOS']),2); 
		$datos1['Y2'][1] = redondeado($dd1[0]['AUDITORES_INTERNOS']*100/($dd1[0]['AUDITORES_EXTERNOS']+$dd1[0]['AUDITORES_INTERNOS']),2); 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `AUDITORES_EXTERNOS`,`AUDITORES_INTERNOS` FROM `REPORTES_AUDITORES_CONTRATADOS` WHERE `FECHA` LIKE '".$ano_2."%'  ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y1'][2] = ''; 
		$datos1['Y2'][2] = ''; 
	}
	else{
		$datos1['Y1'][2] = redondeado($dd2[0]['AUDITORES_EXTERNOS']*100/($dd2[0]['AUDITORES_EXTERNOS']+$dd2[0]['AUDITORES_INTERNOS']),2); 
		$datos1['Y2'][2] = redondeado($dd2[0]['AUDITORES_INTERNOS']*100/($dd2[0]['AUDITORES_EXTERNOS']+$dd2[0]['AUDITORES_INTERNOS']),2); 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `AUDITORES_EXTERNOS`,`AUDITORES_INTERNOS` FROM `REPORTES_AUDITORES_CONTRATADOS` WHERE `FECHA` LIKE '".$ano_3."%'  ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y1'][3] = ''; 
		$datos1['Y2'][3] = ''; 
	}
	else{
		$datos1['Y1'][3] = redondeado($dd3[0]['AUDITORES_EXTERNOS']*100/($dd3[0]['AUDITORES_EXTERNOS']+$dd3[0]['AUDITORES_INTERNOS']),2);  
		$datos1['Y2'][3] = redondeado($dd3[0]['AUDITORES_INTERNOS']*100/($dd3[0]['AUDITORES_EXTERNOS']+$dd3[0]['AUDITORES_INTERNOS']),2);
	}
// ANO ACTUAL - 4
$consulta = "SELECT `AUDITORES_EXTERNOS`,`AUDITORES_INTERNOS` FROM `REPORTES_AUDITORES_CONTRATADOS` WHERE `FECHA` LIKE '".$ano_4."%'  ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y1'][4] = ''; 
		$datos1['Y2'][4] = ''; 
	}
	else{
		$datos1['Y1'][4] = redondeado($dd4[0]['AUDITORES_EXTERNOS']*100/($dd4[0]['AUDITORES_EXTERNOS']+$dd4[0]['AUDITORES_INTERNOS']),2);  
		$datos1['Y2'][4] = redondeado($dd4[0]['AUDITORES_INTERNOS']*100/($dd4[0]['AUDITORES_EXTERNOS']+$dd4[0]['AUDITORES_INTERNOS']),2);  
	}
/**************************************/
print_r(json_encode($datos1));

?> 
