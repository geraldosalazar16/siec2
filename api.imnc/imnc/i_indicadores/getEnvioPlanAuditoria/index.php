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

/*==================================================================================*/
/*	Y1  => AUDITORIAS CARGADAS CON SU PLAN Y QUE CUMPLEN LA REGLA DE LOS 5 DIAS.	*/
/*	Y2  => AUDITORIAS CARGADAS CON SU PLAN Y QUE NO CUMPLEN LA REGLA DE LOS 5 DIAS.	*/
/*	Y3  => AUDITORIAS CARGADAS SIN SU PLAN.											*/
/*==================================================================================*/
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

	$datos1['Y1'][0] =	0;	$datos1['Y2'][0] =	0;	$datos1['Y3'][0] =	0;
	$datos1['Y1'][1] =	0;	$datos1['Y2'][1] =	0;	$datos1['Y3'][1] =	0;
	$datos1['Y1'][2] =	0;	$datos1['Y2'][2] =	0;	$datos1['Y3'][2] =	0;
	$datos1['Y1'][3] =	0;	$datos1['Y2'][3] =	0;	$datos1['Y3'][3] =	0;
	$datos1['Y1'][4] =	0;	$datos1['Y2'][4] =	0;	$datos1['Y3'][4] =	0;
	$datos1['Y1'][5] =	0;	$datos1['Y2'][5] =	0;	$datos1['Y3'][5] =	0;
	$datos1['Y1'][6] =	0;	$datos1['Y2'][6] =	0;	$datos1['Y3'][6] =	0;
	$datos1['Y1'][7] =	0;	$datos1['Y2'][7] =	0;	$datos1['Y3'][7] =	0;
	$datos1['Y1'][8] =	0;	$datos1['Y2'][8] =	0;	$datos1['Y3'][8] =	0;
	$datos1['Y1'][9] =	0;	$datos1['Y2'][9] =	0;	$datos1['Y3'][9] =	0;
	$datos1['Y1'][10] =	0;	$datos1['Y2'][10] =	0;	$datos1['Y3'][10] =	0;
	$datos1['Y1'][11] =	0;	$datos1['Y2'][11] =	0;	$datos1['Y3'][11] =	0;

	
	
	// PARA LO QUE SE QUIERE TENDREMOS Q HACER VARIOS PASOS
	// 1er PASO  
	//TRAER TODAS LAS AUDITORIAS CONFIRMADAS Y QUE COMIENZAN SUS FECHA EN EL ANO ACTUAL
		$consulta = "SELECT 
				`ISA`.`ID_SERVICIO_CLIENTE_ETAPA`,
				`ISA`.`TIPO_AUDITORIA`,
				`ISA`.`CICLO`,
				`ISAF`.`FECHA`,
				`ISAT`.`ID_ETAPA`
						FROM `I_SG_AUDITORIAS` `ISA`
						INNER JOIN `I_SG_AUDITORIA_FECHAS` `ISAF` ON `ISA`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISA`.`TIPO_AUDITORIA` = `ISAF`.`TIPO_AUDITORIA` AND `ISA`.`CICLO` = `ISAF`.`CICLO`
                        INNER JOIN `I_SG_AUDITORIAS_TIPOS` `ISAT` ON `ISA`.`TIPO_AUDITORIA` = `ISAT`.`ID` 
						INNER JOIN `ETAPAS_PROCESO` `EP` ON `ISAT`.`ID_ETAPA` = `EP`.`ID_ETAPA` 
						WHERE 
							`ISA`.`STATUS_AUDITORIA`='2' 
							AND `ISAF`.`FECHA` LIKE '".$ano_curso."%' 
							AND 
								`ISAF`.`FECHA` = ( SELECT MIN(`ISAF1`.`FECHA`) 
                                              		FROM `I_SG_AUDITORIA_FECHAS` `ISAF1` 
                                              		WHERE `ISAF`.`ID_SERVICIO_CLIENTE_ETAPA` = `ISAF1`.`ID_SERVICIO_CLIENTE_ETAPA` AND `ISAF`.`TIPO_AUDITORIA` = `ISAF1`.`TIPO_AUDITORIA` AND `ISAF`.`CICLO` = `ISAF1`.`CICLO`
                                )
							AND  (`ISAT`.`ID_ETAPA` = 1 OR `ISAT`.`ID_ETAPA` = 2 OR `ISAT`.`ID_ETAPA` = 4  OR `ISAT`.`ID_ETAPA` = 5  OR `ISAT`.`ID_ETAPA` = 6  OR `ISAT`.`ID_ETAPA` = 7  OR `ISAT`.`ID_ETAPA` = 8  OR `ISAT`.`ID_ETAPA` = 9  OR `ISAT`.`ID_ETAPA` = 10  OR `ISAT`.`ID_ETAPA` = 11  OR `ISAT`.`ID_ETAPA` = 12  OR `ISAT`.`ID_ETAPA` = 13)";
		$datos = $database->query($consulta)->fetchAll();
		
	// 2do PASO	
	//RECORRER LOS DATOS RECIBIDOS DE LA CONSULTA PARA ASIGNAR CADA UNO AL MES QUE CORRESPONDE
	for($i=0;$i<count($datos);$i++){
		$variable =0;
		$mes = substr($datos[$i]['FECHA'],4,2);
		//AQUI BUSCO ID DEL DOCUMENTO PARA ESTA ETAPA
		switch($datos[$i]['ID_ETAPA']){
			case 1:
				$variable = 14;
				break;
			case 2:
				$variable = 22;
				break;
			
			case 4:
				$variable = 36;
				break;
			case 5:
				$variable = 50;
				break;
			case 6:
				$variable = 64;
				break;	
			case 7:
				$variable = 78;
				break;
			case 8:
				$variable = 92;
				break;
			case 9:
				$variable = 106;
				break;	
			case 10:
				$variable = 120;
				break;
			case 11:
				$variable = 146;
				break;
			case 12:
				$variable = 168;
				break;	
			case 13:
				$variable = 133;
				break;	
		}
		//AQUI BUSCO SI ESTA GUARDADO EL PLAN DE AUDITORIA Y SI SE CUMPLE LA CONDICION DE LOS 5 DIAS
		$consulta = "SELECT `BD`.`FECHA_CREACION`
							FROM `BASE_DOCUMENTOS` `BD` 
							INNER JOIN `CATALOGO_DOCUMENTOS` `CD` ON `BD`.`ID_CATALOGO_DOCUMENTOS` = `CD`.`ID`
							WHERE `CICLO` = ".$datos[$i]['CICLO']."
								AND `ID_SERVICIO`= ".$datos[$i]['ID_SERVICIO_CLIENTE_ETAPA']."
								AND `CD`.`ID` = ".$variable;
		$datos2 = $database->query($consulta)->fetchAll();
		
		if(empty($datos2)){
			switch($mes){
				case 1:
					$datos1['Y3'][0]++;
					break;
				case 2:
					$datos1['Y3'][1]++;
					break;
				case 3:
					$datos1['Y3'][2]++;
					break;	
				case 4:
					$datos1['Y3'][3]++;
					break;
				case 5:
					$datos1['Y3'][4]++;
					break;
				case 6:
					$datos1['Y3'][5]++;
					break;	
				case 7:
					$datos1['Y3'][6]++;
					break;
				case 8:
					$datos1['Y3'][7]++;
					break;
				case 9:
					$datos1['Y3'][8]++;
					break;	
				case 10:
					$datos1['Y3'][9]++;
					break;
				case 11:
					$datos1['Y3'][10]++;
					break;
				case 12:
					$datos1['Y3'][11]++;
					break;		
			}
		}
		else{
			$fecha1 =  substr($datos2[0]['FECHA_CREACION'],8,2).'/'.substr($datos2[0]['FECHA_CREACION'],5,2).'/'.substr($datos2[0]['FECHA_CREACION'],0,4);
			$fecha2 =  substr($datos[$i]['FECHA'],6,2).'/'.substr($datos[$i]['FECHA'],4,2).'/'.substr($datos[$i]['FECHA'],0,4);
			//Funcion para determinar si existe diferencia de 5 dias
			$dif_dias = compararFechas($fecha1,$fecha2);
			if($dif_dias>5 ){
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
		else{
			switch($mes){
				case 1:
					$datos1['Y2'][0]++;
					break;
				case 2:
					$datos1['Y2'][1]++;
					break;
				case 3:
					$datos1['Y2'][2]++;
					break;	
				case 4:
					$datos1['Y2'][3]++;
					break;
				case 5:
					$datos1['Y2'][4]++;
					break;
				case 6:
					$datos1['Y2'][5]++;
					break;	
				case 7:
					$datos1['Y2'][6]++;
					break;
				case 8:
					$datos1['Y2'][7]++;
					break;
				case 9:
					$datos1['Y2'][8]++;
					break;	
				case 10:
					$datos1['Y2'][9]++;
					break;
				case 11:
					$datos1['Y2'][10]++;
					break;
				case 12:
					$datos1['Y2'][11]++;
					break;		
			}
		}
		}		
		
	}
	//  PARTIR DE AQUI ES NECESARIO CALCULAMOS EN % CUANTOS CUMPLEN CON LA REGLA DE 5 DIAS SOBRE EL TOTAL POR MES
	if(($datos1['Y1'][0]+$datos1['Y2'][0]+$datos1['Y3'][0])==0){
		$datos1['Z1'][0] = 0;
		$datos1['Z2'][0] = 0;
		$datos1['Z3'][0] = 0;
	}
	else{
		$datos1['Z1'][0] = ($datos1['Y1'][0] * 100)/($datos1['Y1'][0]+$datos1['Y2'][0]+$datos1['Y3'][0]);
		$datos1['Z2'][0] = ($datos1['Y2'][0] * 100)/($datos1['Y1'][0]+$datos1['Y2'][0]+$datos1['Y3'][0]);
		$datos1['Z3'][0] = ($datos1['Y3'][0] * 100)/($datos1['Y1'][0]+$datos1['Y2'][0]+$datos1['Y3'][0]);
	}
	
	if(($datos1['Y1'][1]+$datos1['Y2'][1]+$datos1['Y3'][1])==0){
		$datos1['Z1'][1] = 0;
		$datos1['Z2'][1] = 0;
		$datos1['Z3'][1] = 0;
	}
	else{
		$datos1['Z1'][1] = ($datos1['Y1'][1] * 100)/($datos1['Y1'][1]+$datos1['Y2'][1]+$datos1['Y3'][1]);
		$datos1['Z2'][1] = ($datos1['Y2'][1] * 100)/($datos1['Y1'][1]+$datos1['Y2'][1]+$datos1['Y3'][1]);
		$datos1['Z3'][1] = ($datos1['Y3'][1] * 100)/($datos1['Y1'][1]+$datos1['Y2'][1]+$datos1['Y3'][1]);
	}
	
	if(($datos1['Y1'][2]+$datos1['Y2'][2]+$datos1['Y3'][2])==0){
		$datos1['Z1'][2] = 0;
		$datos1['Z2'][2] = 0;
		$datos1['Z3'][2] = 0;
	}
	else{
		$datos1['Z1'][2] = ($datos1['Y1'][2] * 100)/($datos1['Y1'][2]+$datos1['Y2'][2]+$datos1['Y3'][2]);
		$datos1['Z2'][2] = ($datos1['Y2'][2] * 100)/($datos1['Y1'][2]+$datos1['Y2'][2]+$datos1['Y3'][2]);
		$datos1['Z3'][2] = ($datos1['Y3'][2] * 100)/($datos1['Y1'][2]+$datos1['Y2'][2]+$datos1['Y3'][2]);
	}
	
	if(($datos1['Y1'][3]+$datos1['Y2'][3]+$datos1['Y3'][3])==0){
		$datos1['Z1'][3] = 0;
		$datos1['Z2'][3] = 0;
		$datos1['Z3'][3] = 0;
	}
	else{
		$datos1['Z1'][3] = ($datos1['Y1'][3] * 100)/($datos1['Y1'][3]+$datos1['Y2'][3]+$datos1['Y3'][3]);
		$datos1['Z2'][3] = ($datos1['Y2'][3] * 100)/($datos1['Y1'][3]+$datos1['Y2'][3]+$datos1['Y3'][3]);
		$datos1['Z3'][3] = ($datos1['Y3'][3] * 100)/($datos1['Y1'][3]+$datos1['Y2'][3]+$datos1['Y3'][3]);
	}
	
	if(($datos1['Y1'][4]+$datos1['Y2'][4]+$datos1['Y3'][4])==0){
		$datos1['Z1'][4] = 0;
		$datos1['Z2'][4] = 0;
		$datos1['Z3'][4] = 0;
	}
	else{
		$datos1['Z1'][4] = ($datos1['Y1'][4] * 100)/($datos1['Y1'][4]+$datos1['Y2'][4]+$datos1['Y3'][4]);
		$datos1['Z2'][4] = ($datos1['Y2'][4] * 100)/($datos1['Y1'][4]+$datos1['Y2'][4]+$datos1['Y3'][4]);
		$datos1['Z3'][4] = ($datos1['Y3'][4] * 100)/($datos1['Y1'][4]+$datos1['Y2'][4]+$datos1['Y3'][4]);
	}
	
	if(($datos1['Y1'][5]+$datos1['Y2'][5]+$datos1['Y3'][5])==0){
		$datos1['Z1'][5] = 0;
		$datos1['Z2'][5] = 0;
		$datos1['Z3'][5] = 0;
	}
	else{
		$datos1['Z1'][5] = ($datos1['Y1'][5] * 100)/($datos1['Y1'][5]+$datos1['Y2'][5]+$datos1['Y3'][5]);
		$datos1['Z2'][5] = ($datos1['Y2'][5] * 100)/($datos1['Y1'][5]+$datos1['Y2'][5]+$datos1['Y3'][5]);
		$datos1['Z3'][5] = ($datos1['Y3'][5] * 100)/($datos1['Y1'][5]+$datos1['Y2'][5]+$datos1['Y3'][5]);
	}
	
	if(($datos1['Y1'][6]+$datos1['Y2'][6]+$datos1['Y3'][6])==0){
		$datos1['Z1'][6] = 0;
		$datos1['Z2'][6] = 0;
		$datos1['Z3'][6] = 0;
	}
	else{
		$datos1['Z1'][6] = ($datos1['Y1'][6] * 100)/($datos1['Y1'][6]+$datos1['Y2'][6]+$datos1['Y3'][6]);
		$datos1['Z2'][6] = ($datos1['Y2'][6] * 100)/($datos1['Y1'][6]+$datos1['Y2'][6]+$datos1['Y3'][6]);
		$datos1['Z3'][6] = ($datos1['Y3'][6] * 100)/($datos1['Y1'][6]+$datos1['Y2'][6]+$datos1['Y3'][6]);
	}
	
	if(($datos1['Y1'][7]+$datos1['Y2'][7]+$datos1['Y3'][7])==0){
		$datos1['Z1'][7] = 0;
		$datos1['Z2'][7] = 0;
		$datos1['Z3'][7] = 0;
	}
	else{
		$datos1['Z1'][7] = ($datos1['Y1'][7] * 100)/($datos1['Y1'][7]+$datos1['Y2'][7]+$datos1['Y3'][7]);
		$datos1['Z2'][7] = ($datos1['Y2'][7] * 100)/($datos1['Y1'][7]+$datos1['Y2'][7]+$datos1['Y3'][7]);
		$datos1['Z3'][7] = ($datos1['Y3'][7] * 100)/($datos1['Y1'][7]+$datos1['Y2'][7]+$datos1['Y3'][7]);
	}
	
	if(($datos1['Y1'][8]+$datos1['Y2'][8]+$datos1['Y3'][8])==0){
		$datos1['Z1'][8] = 0;
		$datos1['Z2'][8] = 0;
		$datos1['Z3'][8] = 0;
	}
	else{
		$datos1['Z1'][8] = ($datos1['Y1'][8] * 100)/($datos1['Y1'][8]+$datos1['Y2'][8]+$datos1['Y3'][8]);
		$datos1['Z2'][8] = ($datos1['Y2'][8] * 100)/($datos1['Y1'][8]+$datos1['Y2'][8]+$datos1['Y3'][8]);
		$datos1['Z3'][8] = ($datos1['Y3'][8] * 100)/($datos1['Y1'][8]+$datos1['Y2'][8]+$datos1['Y3'][8]);
	}
	
	if(($datos1['Y1'][9]+$datos1['Y2'][9]+$datos1['Y3'][9])==0){
		$datos1['Z1'][9] = 0;
		$datos1['Z2'][9] = 0;
		$datos1['Z3'][9] = 0;
	}
	else{
		$datos1['Z1'][9] = ($datos1['Y1'][9] * 100)/($datos1['Y1'][9]+$datos1['Y2'][9]+$datos1['Y3'][9]);
		$datos1['Z2'][9] = ($datos1['Y2'][9] * 100)/($datos1['Y1'][9]+$datos1['Y2'][9]+$datos1['Y3'][9]);
		$datos1['Z3'][9] = ($datos1['Y3'][9] * 100)/($datos1['Y1'][9]+$datos1['Y2'][9]+$datos1['Y3'][9]);
	}
	
	if(($datos1['Y1'][10]+$datos1['Y2'][10]+$datos1['Y3'][10])==0){
		$datos1['Z1'][10] = 0;
		$datos1['Z2'][10] = 0;
		$datos1['Z3'][10] = 0;
	}
	else{
		$datos1['Z1'][10] = ($datos1['Y1'][10] * 100)/($datos1['Y1'][10]+$datos1['Y2'][10]+$datos1['Y3'][10]);
		$datos1['Z2'][10] = ($datos1['Y2'][10] * 100)/($datos1['Y1'][10]+$datos1['Y2'][10]+$datos1['Y3'][10]);
		$datos1['Z3'][10] = ($datos1['Y3'][10] * 100)/($datos1['Y1'][10]+$datos1['Y2'][10]+$datos1['Y3'][10]);
	}
	
	if(($datos1['Y1'][11]+$datos1['Y2'][11]+$datos1['Y3'][11])==0){
		$datos1['Z1'][11] = 0;
		$datos1['Z2'][11] = 0;
		$datos1['Z3'][11] = 0;
	}
	else{
		$datos1['Z1'][11] = ($datos1['Y1'][11] * 100)/($datos1['Y1'][11]+$datos1['Y2'][11]+$datos1['Y3'][11]);
		$datos1['Z2'][11] = ($datos1['Y2'][11] * 100)/($datos1['Y1'][11]+$datos1['Y2'][11]+$datos1['Y3'][11]);
		$datos1['Z3'][11] = ($datos1['Y3'][11] * 100)/($datos1['Y1'][11]+$datos1['Y2'][11]+$datos1['Y3'][11]);
	}
	
	
	
print_r(json_encode($datos1));

?> 
