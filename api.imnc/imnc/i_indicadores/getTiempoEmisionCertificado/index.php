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
/*	Y1  => CERTIFICADOS EMITIDOS Y QUE CUMPLEN LA REGLA DE LOS 7 DIAS.	*/
/*	Y2  => CERTIFICADOS EMITIDOS Y QUE NO CUMPLEN LA REGLA DE LOS 7 DIAS.	*/
/*	Y3  => CERTIFICADOS EMITIDOS Y QUE NO APARECEN DICTAMINADOS.											*/
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
	//TRAER TODOS LOS CERTIFICADOS CARGADOS Y QUE COMIENZAN SUS FECHA EN EL ANO ACTUAL
	
		$consulta = "SELECT 
						`BD`.`FECHA_CREACION`,
						`BD`.`ID_SERVICIO` AS `ID_SERVICIO_CLIENTE_ETAPA`,
						`BD`.`CICLO`,
						`ISAT`.`ID` AS `ID_TIPO_AUDITORIA`
						FROM `BASE_DOCUMENTOS` `BD`
						INNER JOIN `CATALOGO_DOCUMENTOS` `CD` ON `BD`.`ID_CATALOGO_DOCUMENTOS` = `CD`.`ID`
						INNER JOIN `I_SG_AUDITORIAS_TIPOS` `ISAT` ON `CD`.`ID_ETAPA` = `ISAT`.`ID_ETAPA` 
						WHERE 
							(`BD`.`ID_CATALOGO_DOCUMENTOS` = 32 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 46 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 60 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 74 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 88 OR
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 102 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 116 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 130 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 154 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 176 OR 
							`BD`.`ID_CATALOGO_DOCUMENTOS` = 141 ) AND 
							`BD`.`FECHA_CREACION` LIKE '".$ano_curso."%' 
							";
		$datos = $database->query($consulta)->fetchAll();
		valida_error_medoo_and_die();
	// 2do PASO	
	//RECORRER LOS DATOS RECIBIDOS DE LA CONSULTA PARA ASIGNAR CADA UNO AL MES QUE CORRESPONDE
	for($i=0;$i<count($datos);$i++){
		$variable =0;
		$mes = substr($datos[$i]['FECHA_CREACION'],5,2);
		//AQUI BUSCO LA DICTAMINACION 
		$consulta = "SELECT `DICT`.`FECHA_MODIFICACION`
							FROM `DICTAMINACIONES` `DICT` 
							WHERE 	`DICT`.`STATUS`=1
								AND `CICLO` = ".$datos[$i]['CICLO']."
								AND `DICT`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$datos[$i]['ID_SERVICIO_CLIENTE_ETAPA']."
								AND `DICT`.`TIPO_AUDITORIA` = ".$datos[$i]['ID_TIPO_AUDITORIA'];
		$datos2 = $database->query($consulta)->fetchAll();
		valida_error_medoo_and_die();
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
			$fecha2 =  substr($datos2[0]['FECHA_MODIFICACION'],6,2).'/'.substr($datos2[0]['FECHA_MODIFICACION'],4,2).'/'.substr($datos2[0]['FECHA_MODIFICACION'],0,4);
			$fecha1 =  substr($datos[$i]['FECHA_CREACION'],8,2).'/'.substr($datos[$i]['FECHA_CREACION'],5,2).'/'.substr($datos[$i]['FECHA_CREACION'],0,4);
			//Funcion para determinar si existe diferencia de 7 dias
			$dif_dias = compararFechas($fecha1,$fecha2);
			if($dif_dias>=0 && $dif_dias<7){
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
	
	
$respuesta = $datos1;
$respuesta['resultado']="ok";	
print_r(json_encode($respuesta));

?> 
