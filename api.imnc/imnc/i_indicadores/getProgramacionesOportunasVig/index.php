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


function compararFechas($primera, $segunda)
 {
  $valoresPrimera = explode ("/", $primera);   
  $valoresSegunda = explode ("/", $segunda); 

  $diaPrimera    = $valoresPrimera[0];  
  $mesPrimera  = $valoresPrimera[1];  
  $anyoPrimera   = $valoresPrimera[2]; 

  $diaSegunda   = $valoresSegunda[0];  
  $mesSegunda = $valoresSegunda[1];  
  $anyoSegunda  = $valoresSegunda[2];

  $diasPrimeraJuliano = gregoriantojd($mesPrimera, $diaPrimera, $anyoPrimera);  
  $diasSegundaJuliano = gregoriantojd($mesSegunda, $diaSegunda, $anyoSegunda);     

  if(!checkdate($mesPrimera, $diaPrimera, $anyoPrimera)){
    // "La fecha ".$primera." no es v&aacute;lida";
    return 0;
  }elseif(!checkdate($mesSegunda, $diaSegunda, $anyoSegunda)){
    // "La fecha ".$segunda." no es v&aacute;lida";
    return 0;
  }else{
    return  $diasPrimeraJuliano - $diasSegundaJuliano;
  } 

}

/*==========================================================================================*/
/*	Y1  => AUDITORIAS DE VIGILANCIAS PROGRAMADAS Y QUE CUMPLEN LA REGLA DE LOS 30 DIAS.		*/
/*	Y2  => AUDITORIAS DE VIGILANCIAS PROGRAMADAS Y QUE NO CUMPLEN LA REGLA DE LOS 30 DIAS.	*/
/*==========================================================================================*/
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

	$datos1['Y1'][0] =	0;	$datos1['Y2'][0] =	0;	
	$datos1['Y1'][1] =	0;	$datos1['Y2'][1] =	0;	
	$datos1['Y1'][2] =	0;	$datos1['Y2'][2] =	0;	
	$datos1['Y1'][3] =	0;	$datos1['Y2'][3] =	0;	
	$datos1['Y1'][4] =	0;	$datos1['Y2'][4] =	0;	
	$datos1['Y1'][5] =	0;	$datos1['Y2'][5] =	0;	
	$datos1['Y1'][6] =	0;	$datos1['Y2'][6] =	0;	
	$datos1['Y1'][7] =	0;	$datos1['Y2'][7] =	0;	
	$datos1['Y1'][8] =	0;	$datos1['Y2'][8] =	0;	
	$datos1['Y1'][9] =	0;	$datos1['Y2'][9] =	0;	
	$datos1['Y1'][10] =	0;	$datos1['Y2'][10] =	0;	
	$datos1['Y1'][11] =	0;	$datos1['Y2'][11] =	0;	

	
	
	// PARA LO QUE SE QUIERE TENDREMOS Q HACER VARIOS PASOS
	// 1er PASO  
	// AQUI BUSCO PARA CADA MES DEL ANO PRESENTE EL VALOR QUE DEBE TENER LA TABLA
	//ENERO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."01%' ORDER BY `IPOV`.`ID` DESC";
	$datos_enero = $database->query($consulta)->fetchAll();
	if(empty($datos_enero)){
		$datos1['Y1'][0] = 0;
		$datos1['Y2'][0] = 0;
	}
	else{
		$datos1['Y1'][0] = $datos_enero[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][0] = $datos_enero[0]['CANT_AUD_PROG_F_TIEMPO'];
	}
	//FEBRERO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."02%' ORDER BY `IPOV`.`ID` DESC";
	$datos_febrero = $database->query($consulta)->fetchAll();
	if(empty($datos_febrero)){
		$datos1['Y1'][1] = 0;
		$datos1['Y2'][1] = 0;
	}
	else{
		$datos1['Y1'][1] = $datos_febrero[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][1] = $datos_febrero[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//MARZO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."03%' ORDER BY `IPOV`.`ID` DESC";
	$datos_marzo = $database->query($consulta)->fetchAll();
	if(empty($datos_marzo)){
		$datos1['Y1'][2] = 0;
		$datos1['Y2'][2] = 0;
	}
	else{
		$datos1['Y1'][2] = $datos_marzo[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][2] = $datos_marzo[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//ABRIL
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."04%' ORDER BY `IPOV`.`ID` DESC";
	$datos_abril = $database->query($consulta)->fetchAll();
	if(empty($datos_abril)){
		$datos1['Y1'][3] = 0;
		$datos1['Y2'][3] = 0;
	}
	else{
		$datos1['Y1'][3] = $datos_abril[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][3] = $datos_abril[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//MAYO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."05%' ORDER BY `IPOV`.`ID` DESC";
	$datos_mayo = $database->query($consulta)->fetchAll();
	if(empty($datos_mayo)){
		$datos1['Y1'][4] = 0;
		$datos1['Y2'][4] = 0;
	}
	else{
		$datos1['Y1'][4] = $datos_mayo[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][4] = $datos_mayo[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//JUNIO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."06%' ORDER BY `IPOV`.`ID` DESC";
	$datos_junio = $database->query($consulta)->fetchAll();
	if(empty($datos_junio)){
		$datos1['Y1'][5] = 0;
		$datos1['Y2'][5] = 0;
	}
	else{
		$datos1['Y1'][5] = $datos_junio[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][5] = $datos_junio[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//JULIO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."07%' ORDER BY `IPOV`.`ID` DESC";
	$datos_julio = $database->query($consulta)->fetchAll();
	if(empty($datos_julio)){
		$datos1['Y1'][6] = 0;
		$datos1['Y2'][6] = 0;
	}
	else{
		$datos1['Y1'][6] = $datos_julio[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][6] = $datos_julio[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//AGOSTO
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."08%' ORDER BY `IPOV`.`ID` DESC";
	$datos_agosto = $database->query($consulta)->fetchAll();
	if(empty($datos_agosto)){
		$datos1['Y1'][7] = 0;
		$datos1['Y2'][7] = 0;
	}
	else{
		$datos1['Y1'][7] = $datos_agosto[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][7] = $datos_agosto[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//SEPTIEMBRE
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."09%' ORDER BY `IPOV`.`ID` DESC";
	$datos_septiembre = $database->query($consulta)->fetchAll();
	if(empty($datos_septiembre)){
		$datos1['Y1'][8] = 0;
		$datos1['Y2'][8] = 0;
	}
	else{
		$datos1['Y1'][8] = $datos_septiembre[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][8] = $datos_septiembre[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//OCTUBRE
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."10%' ORDER BY `IPOV`.`ID` DESC";
	$datos_octubre = $database->query($consulta)->fetchAll();
	if(empty($datos_octubre)){
		$datos1['Y1'][9] = 0;
		$datos1['Y2'][9] = 0;
	}
	else{
		$datos1['Y1'][9] = $datos_octubre[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][9] = $datos_octubre[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//NOVIEMBRE
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."11%' ORDER BY `IPOV`.`ID` DESC";
	$datos_noviembre = $database->query($consulta)->fetchAll();
	if(empty($datos_noviembre)){
		$datos1['Y1'][10] = 0;
		$datos1['Y2'][10] = 0;
	}
	else{
		$datos1['Y1'][10] = $datos_noviembre[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][10] = $datos_noviembre[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	//DICIEMBRE
	$consulta = "SELECT `IPOV`.`CANT_AUD_PROG_A_TIEMPO`,`IPOV`.`CANT_AUD_PROG_F_TIEMPO`
				
						FROM `I_PROGRAMACIONES_OPORTUNAS_VIGILANCIAS_SG` `IPOV`
						WHERE 
							`IPOV`.`FECHA` LIKE '".$ano_curso."12%' ORDER BY `IPOV`.`ID` DESC";
	$datos_diciembre = $database->query($consulta)->fetchAll();
	if(empty($datos_diciembre)){
		$datos1['Y1'][11] = 0;
		$datos1['Y2'][11] = 0;
	}
	else{
		$datos1['Y1'][11] = $datos_diciembre[0]['CANT_AUD_PROG_A_TIEMPO'];
		$datos1['Y2'][11] = $datos_diciembre[0]['CANT_AUD_PROG_F_TIEMPO'];
	}	
	// 2do PASO	
	
	
	//  PARTIR DE AQUI ES NECESARIO CALCULAMOS EN % CUANTOS CUMPLEN CON LA REGLA DE 30 DIAS SOBRE EL TOTAL POR MES
	if(($datos1['Y1'][0]+$datos1['Y2'][0])==0){
		$datos1['Z1'][0] = 0;
		$datos1['Z2'][0] = 0;
		
	}
	else{
		$datos1['Z1'][0] = ($datos1['Y1'][0] * 100)/($datos1['Y1'][0]+$datos1['Y2'][0]);
		$datos1['Z2'][0] = ($datos1['Y2'][0] * 100)/($datos1['Y1'][0]+$datos1['Y2'][0]);
		
	}
	
	if(($datos1['Y1'][1]+$datos1['Y2'][1])==0){
		$datos1['Z1'][1] = 0;
		$datos1['Z2'][1] = 0;
		
	}
	else{
		$datos1['Z1'][1] = ($datos1['Y1'][1] * 100)/($datos1['Y1'][1]+$datos1['Y2'][1]);
		$datos1['Z2'][1] = ($datos1['Y2'][1] * 100)/($datos1['Y1'][1]+$datos1['Y2'][1]);
		
	}
	
	if(($datos1['Y1'][2]+$datos1['Y2'][2])==0){
		$datos1['Z1'][2] = 0;
		$datos1['Z2'][2] = 0;
		
	}
	else{
		$datos1['Z1'][2] = ($datos1['Y1'][2] * 100)/($datos1['Y1'][2]+$datos1['Y2'][2]);
		$datos1['Z2'][2] = ($datos1['Y2'][2] * 100)/($datos1['Y1'][2]+$datos1['Y2'][2]);
		
	}
	
	if(($datos1['Y1'][3]+$datos1['Y2'][3])==0){
		$datos1['Z1'][3] = 0;
		$datos1['Z2'][3] = 0;
		
	}
	else{
		$datos1['Z1'][3] = ($datos1['Y1'][3] * 100)/($datos1['Y1'][3]+$datos1['Y2'][3]);
		$datos1['Z2'][3] = ($datos1['Y2'][3] * 100)/($datos1['Y1'][3]+$datos1['Y2'][3]);
		
	}
	
	if(($datos1['Y1'][4]+$datos1['Y2'][4])==0){
		$datos1['Z1'][4] = 0;
		$datos1['Z2'][4] = 0;
		
	}
	else{
		$datos1['Z1'][4] = ($datos1['Y1'][4] * 100)/($datos1['Y1'][4]+$datos1['Y2'][4]);
		$datos1['Z2'][4] = ($datos1['Y2'][4] * 100)/($datos1['Y1'][4]+$datos1['Y2'][4]);
		
	}
	
	if(($datos1['Y1'][5]+$datos1['Y2'][5])==0){
		$datos1['Z1'][5] = 0;
		$datos1['Z2'][5] = 0;
		
	}
	else{
		$datos1['Z1'][5] = ($datos1['Y1'][5] * 100)/($datos1['Y1'][5]+$datos1['Y2'][5]);
		$datos1['Z2'][5] = ($datos1['Y2'][5] * 100)/($datos1['Y1'][5]+$datos1['Y2'][5]);
		
	}
	
	if(($datos1['Y1'][6]+$datos1['Y2'][6])==0){
		$datos1['Z1'][6] = 0;
		$datos1['Z2'][6] = 0;
		
	}
	else{
		$datos1['Z1'][6] = ($datos1['Y1'][6] * 100)/($datos1['Y1'][6]+$datos1['Y2'][6]);
		$datos1['Z2'][6] = ($datos1['Y2'][6] * 100)/($datos1['Y1'][6]+$datos1['Y2'][6]);
		
	}
	
	if(($datos1['Y1'][7]+$datos1['Y2'][7])==0){
		$datos1['Z1'][7] = 0;
		$datos1['Z2'][7] = 0;
		
	}
	else{
		$datos1['Z1'][7] = ($datos1['Y1'][7] * 100)/($datos1['Y1'][7]+$datos1['Y2'][7]);
		$datos1['Z2'][7] = ($datos1['Y2'][7] * 100)/($datos1['Y1'][7]+$datos1['Y2'][7]);
		
	}
	
	if(($datos1['Y1'][8]+$datos1['Y2'][8])==0){
		$datos1['Z1'][8] = 0;
		$datos1['Z2'][8] = 0;
		
	}
	else{
		$datos1['Z1'][8] = ($datos1['Y1'][8] * 100)/($datos1['Y1'][8]+$datos1['Y2'][8]);
		$datos1['Z2'][8] = ($datos1['Y2'][8] * 100)/($datos1['Y1'][8]+$datos1['Y2'][8]);
		
	}
	
	if(($datos1['Y1'][9]+$datos1['Y2'][9])==0){
		$datos1['Z1'][9] = 0;
		$datos1['Z2'][9] = 0;
		
	}
	else{
		$datos1['Z1'][9] = ($datos1['Y1'][9] * 100)/($datos1['Y1'][9]+$datos1['Y2'][9]);
		$datos1['Z2'][9] = ($datos1['Y2'][9] * 100)/($datos1['Y1'][9]+$datos1['Y2'][9]);
		
	}
	
	if(($datos1['Y1'][10]+$datos1['Y2'][10])==0){
		$datos1['Z1'][10] = 0;
		$datos1['Z2'][10] = 0;
		
	}
	else{
		$datos1['Z1'][10] = ($datos1['Y1'][10] * 100)/($datos1['Y1'][10]+$datos1['Y2'][10]);
		$datos1['Z2'][10] = ($datos1['Y2'][10] * 100)/($datos1['Y1'][10]+$datos1['Y2'][10]);
		
	}
	
	if(($datos1['Y1'][11]+$datos1['Y2'][11])==0){
		$datos1['Z1'][11] = 0;
		$datos1['Z2'][11] = 0;
		
	}
	else{
		$datos1['Z1'][11] = ($datos1['Y1'][11] * 100)/($datos1['Y1'][11]+$datos1['Y2'][11]);
		$datos1['Z2'][11] = ($datos1['Y2'][11] * 100)/($datos1['Y1'][11]+$datos1['Y2'][11]);
		
	}
	
	
	
print_r(json_encode($datos1));

?> 
