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
				
		$datos1['Y3'][0]= '';
		$datos1['Y3'][1]= '';		
		$datos1['Y3'][2]= '';		
		$datos1['Y3'][3]= '';		
		$datos1['Y3'][4]= '';		
				
		$datos1['Y4'][0]= '';
		$datos1['Y4'][1]= '';		
		$datos1['Y4'][2]= '';		
		$datos1['Y4'][3]= '';		
		$datos1['Y4'][4]= '';		
				
		$datos1['Y5'][0]= '';		
		$datos1['Y5'][1]= '';		
		$datos1['Y5'][2]= '';		
		$datos1['Y5'][3]= '';		
		$datos1['Y5'][4]= '';		
		
		
// A PARTIR DE AQUI ES PARA TIPO SERVICIO CALIDAD
// ANO ACTUAL
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y1'][0] = ''; 
	}
	else{
		$datos1['Y1'][0] = $dd0[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_1."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y1'][1] = ''; 
	}
	else{
		$datos1['Y1'][1] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_2."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y1'][2] = ''; 
	}
	else{
		$datos1['Y1'][2] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_3."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y1'][3] = ''; 
	}
	else{
		$datos1['Y1'][3] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 4
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_4."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y1'][4] = ''; 
	}
	else{
		$datos1['Y1'][4] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
	}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO AMBIENTE
// ANO ACTUAL
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' AND `ID_TIPO_SERVICIO` = 2 ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y2'][0] = ''; 
	}
	else{
		$datos1['Y2'][0] = $dd0[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_1."%' AND `ID_TIPO_SERVICIO` = 2 ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y2'][1] = ''; 
	}
	else{
		$datos1['Y2'][1] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_2."%' AND `ID_TIPO_SERVICIO` = 2 ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y2'][2] = ''; 
	}
	else{
		$datos1['Y2'][2] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_3."%' AND `ID_TIPO_SERVICIO` = 2 ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y2'][3] = ''; 
	}
	else{
		$datos1['Y2'][3] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 4
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_4."%' AND `ID_TIPO_SERVICIO` = 2 ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y2'][4] = ''; 
	}
	else{
		$datos1['Y2'][4] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
	}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO SAST
// ANO ACTUAL
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' AND `ID_TIPO_SERVICIO` = 12 ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y3'][0] = ''; 
	}
	else{
		$datos1['Y3'][0] = $dd0[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_1."%' AND `ID_TIPO_SERVICIO` = 12 ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y3'][1] = ''; 
	}
	else{
		$datos1['Y3'][1] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_2."%' AND `ID_TIPO_SERVICIO` = 12 ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y3'][2] = ''; 
	}
	else{
		$datos1['Y3'][2] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_3."%' AND `ID_TIPO_SERVICIO` = 12 ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y3'][3] = ''; 
	}
	else{
		$datos1['Y3'][3] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 4
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_4."%' AND `ID_TIPO_SERVICIO` = 12 ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y3'][4] = ''; 
	}
	else{
		$datos1['Y3'][4] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
	}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO INTEGRAL
// ANO ACTUAL
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' AND `ID_TIPO_SERVICIO` = 20 ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y4'][0] = ''; 
	}
	else{
		$datos1['Y4'][0] = $dd0[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_1."%' AND `ID_TIPO_SERVICIO` = 20 ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y4'][1] = ''; 
	}
	else{
		$datos1['Y4'][1] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_2."%' AND `ID_TIPO_SERVICIO` = 20 ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y4'][2] = ''; 
	}
	else{
		$datos1['Y4'][2] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_3."%' AND `ID_TIPO_SERVICIO` = 20 ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y4'][3] = ''; 
	}
	else{
		$datos1['Y4'][3] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 4
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_4."%' AND `ID_TIPO_SERVICIO` = 20 ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y4'][4] = ''; 
	}
	else{
		$datos1['Y4'][4] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
	}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO ENERGIA
// ANO ACTUAL
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' AND `ID_TIPO_SERVICIO` = 21 ORDER BY `ID` DESC";
$dd0 = $database->query($consulta)->fetchAll();
	if(empty($dd0)){
		$datos1['Y5'][0] = ''; 
	}
	else{
		$datos1['Y5'][0] = $dd0[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 1
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_1."%' AND `ID_TIPO_SERVICIO` = 21 ORDER BY `ID` DESC";
$dd1 = $database->query($consulta)->fetchAll();
	if(empty($dd1)){
		$datos1['Y5'][1] = ''; 
	}
	else{
		$datos1['Y5'][1] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 2
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_2."%' AND `ID_TIPO_SERVICIO` = 21 ORDER BY `ID` DESC";
$dd2 = $database->query($consulta)->fetchAll();
	if(empty($dd2)){
		$datos1['Y5'][2] = ''; 
	}
	else{
		$datos1['Y5'][2] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 3
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_3."%' AND `ID_TIPO_SERVICIO` = 21 ORDER BY `ID` DESC";
$dd3 = $database->query($consulta)->fetchAll();
	if(empty($dd3)){
		$datos1['Y5'][3] = ''; 
	}
	else{
		$datos1['Y5'][3] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
	}
// ANO ACTUAL - 4
$consulta = "SELECT `CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_4."%' AND `ID_TIPO_SERVICIO` = 21 ORDER BY `ID` DESC";
$dd4 = $database->query($consulta)->fetchAll();
	if(empty($dd4)){
		$datos1['Y5'][4] = ''; 
	}
	else{
		$datos1['Y5'][4] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
	}
/**************************************/
print_r(json_encode($datos1));

?> 
