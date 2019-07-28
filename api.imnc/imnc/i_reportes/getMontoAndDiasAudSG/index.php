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

	$datos1['Y1'][0] =	0;
	$datos1['Y1'][1] =	0;
	$datos1['Y1'][2] =	0;
	$datos1['Y1'][3] =	0;
	$datos1['Y1'][4] =	0;
	$datos1['Y1'][5] =	0;
	$datos1['Y1'][6] =	0;
	$datos1['Y1'][7] =	0;
	$datos1['Y1'][8] =	0;
	$datos1['Y1'][9] =	0;
	$datos1['Y1'][10] =	0;
	$datos1['Y1'][11] =	0;
	
	

	
	
	// PARA LO QUE SE QUIERE TENDREMOS Q HACER VARIOS PASOS
	// 1er PASO  
	//TRAER TODAS LAS AUDITORIAS CONFIRMADAS Y QUE COMIENZAN SUS FECHA EN EL ANO ACTUAL
		$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE '".$ano_curso."%' ";
		$datos = $database->query($consulta)->fetchAll();
		
	// 2do PASO	
	//RECORRER LOS DATOS RECIBIDOS DE LA CONSULTA PARA ASIGNAR CADA UNO AL MES QUE CORRESPONDE
	for($i=0;$i<count($datos);$i++){
		$mes = substr($datos[$i]['FECHA'],4,2);
		switch($mes){
			case 1:
				$datos1['Y1'][0]++;
				break;
			case 2:
				$datos1['Y1'][1]++;
				break;
			case 3:
				$datos1['Y1'][2]++;
				break;	
			case 4:
				$datos1['Y1'][3]++;
				break;
			case 5:
				$datos1['Y1'][4]++;
				break;
			case 6:
				$datos1['Y1'][5]++;
				break;	
			case 7:
				$datos1['Y1'][6]++;
				break;
			case 8:
				$datos1['Y1'][7]++;
				break;
			case 9:
				$datos1['Y1'][8]++;
				break;	
			case 10:
				$datos1['Y1'][9]++;
				break;
			case 11:
				$datos1['Y1'][10]++;
				break;
			case 12:
				$datos1['Y1'][11]++;
				break;		
		}
		
	}
	//3er PASO es necesario buscar los montos de las auditorias confirmadas para cada mes pero teniendo en cuenta que una misma auditoria puede tener varios dias
	//TRAER TODAS LAS AUDITORIAS CONFIRMADAS Y QUE COMIENZAN SUS FECHA EN EL ANO ACTUAL
	//ENERO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."01%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][0]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][0] =	0;
	}
	//FEBRERO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."02%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][1]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][1] =	0;
	}
	//MARZO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."03%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][2]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][2] =	0;
	}
	//ABRIL
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."04%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][3]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][3] =	0;
	}
	//MAYO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."05%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][4]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][4] =	0;
	}
	//JUNIO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."06%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][5]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][5] =	0;
	}
	//JULIO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."07%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][6]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][6] =	0;
	}
	//AGOSTO
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."08%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][7]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][7] =	0;
	}
	//SEPTIEMBRE
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."09%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][8]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][8] =	0;
	}
	//OCTUBRE
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."10%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][9]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][9] =	0;
	}
	//NOVIEMBRE
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."11%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][10]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][10] =	0;
	}
	//DICIEMBRE
	$consulta = "SELECT SUM(TT.MONTO)  AS SUM_TOT
					FROM(	SELECT 
								DISTINCT	 `ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
											`ISA`.`TIPO_AUDITORIA`,
											`ISA`.`CICLO`,
											`ISA`.`MONTO`
							FROM `I_SG_AUDITORIAS` `ISA`
							INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
							WHERE 
								`ISA`.`STATUS_AUDITORIA`='2' AND `ISAF`.`FECHA` LIKE  '".$ano_curso."12%') AS TT";
	$datos = $database->query($consulta)->fetchAll();
	if($datos[0]['SUM_TOT'] != null){
		$datos1['Z1'][11]= $datos[0]['SUM_TOT'];
	}
	else{
		$datos1['Z1'][11] =	0;
	}
		
		
	$respuesta[0]['DATOS'] = "Dias auditor";	
	$respuesta[1]['DATOS'] =  "Monto";						
	$respuesta[0]['ENERO'] = $datos1['Y1'][0];	
	$respuesta[1]['ENERO'] = '$ '. number_format( $datos1['Z1'][0],2);	
	$respuesta[0]['FEBRERO'] = $datos1['Y1'][1];	
	$respuesta[1]['FEBRERO'] = '$ '. number_format( $datos1['Z1'][1],2);	
	$respuesta[0]['MARZO'] = $datos1['Y1'][2];	
	$respuesta[1]['MARZO'] = '$ '. number_format( $datos1['Z1'][2],2);	
	$respuesta[0]['ABRIL'] = $datos1['Y1'][3];	
	$respuesta[1]['ABRIL'] = '$ '. number_format( $datos1['Z1'][3],2);	
	$respuesta[0]['MAYO'] = $datos1['Y1'][4];	
	$respuesta[1]['MAYO'] = '$ '. number_format( $datos1['Z1'][4],2);	
	$respuesta[0]['JUNIO'] = $datos1['Y1'][5];	
	$respuesta[1]['JUNIO'] = '$ '. number_format( $datos1['Z1'][5],2);	
	$respuesta[0]['JULIO'] = $datos1['Y1'][6];	
	$respuesta[1]['JULIO'] = '$ '. number_format( $datos1['Z1'][6],2);	
	$respuesta[0]['AGOSTO'] = $datos1['Y1'][7];	
	$respuesta[1]['AGOSTO'] =  '$ '. number_format($datos1['Z1'][7],2);	
	$respuesta[0]['SEPTIEMBRE'] = $datos1['Y1'][8];	
	$respuesta[1]['SEPTIEMBRE'] =  '$ '. number_format($datos1['Z1'][8],2);	
	$respuesta[0]['OCTUBRE'] = $datos1['Y1'][9];	
	$respuesta[1]['OCTUBRE'] =  '$ '. number_format($datos1['Z1'][9],2);	
	$respuesta[0]['NOVIEMBRE'] = $datos1['Y1'][10];	
	$respuesta[1]['NOVIEMBRE'] = '$ '. number_format($datos1['Z1'][10],2);	
	$respuesta[0]['DICIEMBRE'] = $datos1['Y1'][11];	
	$respuesta[1]['DICIEMBRE'] =  '$ '. number_format($datos1['Z1'][11],2);	
		
print_r(json_encode($respuesta));

?> 
