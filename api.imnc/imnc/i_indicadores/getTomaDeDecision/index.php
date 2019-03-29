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
/*	Y1  => DICTAMENES EMITIDOS A TIEMPO	*/
/*	Y2  => DICTAMENES EMITIDOS FUERA DE TIEMPO	*/
/*	Y3  => AUDITORIAS CARGADAS SIN SU PLAN.											*/
/*==================================================================================*/
$respuesta=array(); 
$datos1= array();
$resp = array();
//Constantes
$fecha_actual = date('Ymd');
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

	
	
	// PARA LO QUE SE QUIERE TENDREMOS Q HACER VARIOS PASOS
	// 1er PASO  
	// BUSACAR LA CANTIDAD DE DICTAMINADORES QUE APORTAN AL GRAFICO
	$consulta = "SELECT 
						DISTINCT	`DICTAMINACIONES`.`ID_DICTAMINADOR`,
									`USUARIOS`.`NOMBRE`
					 FROM `DICTAMINACIONES` 
					 INNER JOIN USUARIOS ON `DICTAMINACIONES`.`ID_DICTAMINADOR` = `USUARIOS`.`ID`
					 WHERE '".$fecha_actual."' - `DICTAMINACIONES`.`FECHA_CREACION`>7 AND `DICTAMINACIONES`.`FECHA_CREACION` LIKE '".$ano_curso."%' 
					 ORDER BY `DICTAMINACIONES`.`ID_DICTAMINADOR` ASC";
	$datos0 = $database->query($consulta)->fetchAll();
	// $n cantidad de dictaminadores
	if(empty($datos0)){
		$n=0;
	}
	else{
		$n = count($datos0);
	}
	// 2do PASO	
	// TRAER LOS DATOS Y RECORRER EL CICLO POR DICTAMINADOR EN CUESTION
	for($j = 0; $j < count($datos0); $j++){
		//INICIALIZANDO VARIABLES
		$datos1['Y'.($j*3 +1)][0] =	0;	$datos1['Y'.($j*3 +2)][0] =	0;	$datos1['Y'.($j*3 +3)][0] =	0;
		$datos1['Y'.($j*3 +1)][1] =	0;	$datos1['Y'.($j*3 +2)][1] =	0;	$datos1['Y'.($j*3 +3)][1] =	0;
		$datos1['Y'.($j*3 +1)][2] =	0;	$datos1['Y'.($j*3 +2)][2] =	0;	$datos1['Y'.($j*3 +3)][2] =	0;
		$datos1['Y'.($j*3 +1)][3] =	0;	$datos1['Y'.($j*3 +2)][3] =	0;	$datos1['Y'.($j*3 +3)][3] =	0;
		$datos1['Y'.($j*3 +1)][4] =	0;	$datos1['Y'.($j*3 +2)][4] =	0;	$datos1['Y'.($j*3 +3)][4] =	0;
		$datos1['Y'.($j*3 +1)][5] =	0;	$datos1['Y'.($j*3 +2)][5] =	0;	$datos1['Y'.($j*3 +3)][5] =	0;
		$datos1['Y'.($j*3 +1)][6] =	0;	$datos1['Y'.($j*3 +2)][6] =	0;	$datos1['Y'.($j*3 +3)][6] =	0;
		$datos1['Y'.($j*3 +1)][7] =	0;	$datos1['Y'.($j*3 +2)][7] =	0;	$datos1['Y'.($j*3 +3)][7] =	0;
		$datos1['Y'.($j*3 +1)][8] =	0;	$datos1['Y'.($j*3 +2)][8] =	0;	$datos1['Y'.($j*3 +3)][8] =	0;
		$datos1['Y'.($j*3 +1)][9] =	0;	$datos1['Y'.($j*3 +2)][9] =	0;	$datos1['Y'.($j*3 +3)][9] =	0;
		$datos1['Y'.($j*3 +1)][10] =	0;	$datos1['Y'.($j*3 +2)][10] =	0;	$datos1['Y'.($j*3 +3)][10] =	0;
		$datos1['Y'.($j*3 +1)][11] =	0;	$datos1['Y'.($j*3 +2)][11] =	0;	$datos1['Y'.($j*3 +3)][11] =	0;
		//TRAER TODAS LAS AUDITORIAS CONFIRMADAS Y QUE COMIENZAN SUS FECHA EN EL ANO ACTUAL POR DICTAMINADOR
		$consulta = "SELECT 
							`ID_DICTAMINADOR`,
							`STATUS`,
							`FECHA_CREACION`,
							`FECHA_MODIFICACION`
					 FROM `DICTAMINACIONES` 
					 WHERE '".$fecha_actual."' - `FECHA_CREACION`>7 AND `FECHA_CREACION` LIKE '".$ano_curso."%' AND  `ID_DICTAMINADOR` = ".$datos0[$j]['ID_DICTAMINADOR']."
					 ORDER BY `ID_DICTAMINADOR`,`FECHA_CREACION` ASC";
		$datos = $database->query($consulta)->fetchAll();
		
		// 3er PASO	
		//RECORRER LOS DATOS RECIBIDOS DE LA CONSULTA PARA ASIGNAR CADA UNO AL MES QUE CORRESPONDE
		for($i=0;$i<count($datos);$i++){
		$variable =0;
		$mes = substr($datos[$i]['FECHA_CREACION'],4,2);
		
	
			if(empty($datos[$i]['FECHA_MODIFICACION'])){
				switch($mes){
					case 1:
						$datos1['Y'.($j*3 +3)][0]++;
						break;
					case 2:
						$datos1['Y'.($j*3 +3)][1]++;
						break;
					case 3:
						$datos1['Y'.($j*3 +3)][2]++;
						break;	
					case 4:
						$datos1['Y'.($j*3 +3)][3]++;
						break;
					case 5:
						$datos1['Y'.($j*3 +3)][4]++;
						break;
					case 6:
						$datos1['Y'.($j*3 +3)][5]++;
						break;	
					case 7:
						$datos1['Y'.($j*3 +3)][6]++;
						break;
					case 8:
						$datos1['Y'.($j*3 +3)][7]++;
						break;
					case 9:
						$datos1['Y'.($j*3 +3)][8]++;
						break;	
					case 10:
						$datos1['Y'.($j*3 +3)][9]++;
						break;
					case 11:
						$datos1['Y'.($j*3 +3)][10]++;
						break;
					case 12:
						$datos1['Y'.($j*3 +3)][11]++;
						break;		
				}
			}
			else{
				$fecha1 =  substr($datos[$i]['FECHA_CREACION'],6,2).'/'.substr($datos[$i]['FECHA_CREACION'],4,2).'/'.substr($datos[$i]['FECHA_CREACION'],0,4);
				$fecha2 =  substr($datos[$i]['FECHA_MODIFICACION'],6,2).'/'.substr($datos[$i]['FECHA_MODIFICACION'],4,2).'/'.substr($datos[$i]['FECHA_MODIFICACION'],0,4);
				//Funcion para determinar si existe diferencia de 7 dias
				$dif_dias = compararFechas($fecha1,$fecha2);
				if($dif_dias>=0 && $dif_dias<8 ){
					switch($mes){
						case 1:
							$datos1['Y'.($j*3 +1)][0]++;
							break;
						case 2:
							$datos1['Y'.($j*3 +1)][1]++;
							break;
						case 3:
							$datos1['Y'.($j*3 +1)][2]++;
							break;	
						case 4:
							$datos1['Y'.($j*3 +1)][3]++;
							break;
						case 5:
							$datos1['Y'.($j*3 +1)][4]++;
							break;
						case 6:
							$datos1['Y'.($j*3 +1)][5]++;
							break;	
						case 7:
							$datos1['Y'.($j*3 +1)][6]++;
							break;
						case 8:
							$datos1['Y'.($j*3 +1)][7]++;
							break;
						case 9:
							$datos1['Y'.($j*3 +1)][8]++;
							break;	
						case 10:
							$datos1['Y'.($j*3 +1)][9]++;
							break;
						case 11:
							$datos1['Y'.($j*3 +1)][10]++;
							break;
						case 12:
							$datos1['Y'.($j*3 +1)][11]++;
							break;		
					}
				}
				else{
					switch($mes){
						case 1:
							$datos1['Y'.($j*3 +2)][0]++;
							break;
						case 2:
							$datos1['Y'.($j*3 +2)][1]++;
							break;
						case 3:
							$datos1['Y'.($j*3 +2)][2]++;
							break;	
						case 4:
							$datos1['Y'.($j*3 +2)][3]++;
							break;
						case 5:
							$datos1['Y'.($j*3 +2)][4]++;
							break;
						case 6:
							$datos1['Y'.($j*3 +2)][5]++;
							break;	
						case 7:
							$datos1['Y'.($j*3 +2)][6]++;
							break;
						case 8:
							$datos1['Y'.($j*3 +2)][7]++;
							break;
						case 9:
							$datos1['Y'.($j*3 +2)][8]++;
							break;	
						case 10:
							$datos1['Y'.($j*3 +2)][9]++;
							break;
						case 11:
							$datos1['Y'.($j*3 +2)][10]++;
							break;
						case 12:
							$datos1['Y'.($j*3 +2)][11]++;
							break;		
					}
				}
			}		
		
		}
		if(($datos1['Y'.($j*3 +1)][0]+$datos1['Y'.($j*3 +2)][0]+$datos1['Y'.($j*3 +3)][0])==0){
			$datos1['Z'.($j*3+1)][0] = 0;
			$datos1['Z'.($j*3+2)][0] = 0;
			$datos1['Z'.($j*3+3)][0] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][0] = ($datos1['Y'.($j*3 +1)][0] * 100)/($datos1['Y'.($j*3 +1)][0]+$datos1['Y'.($j*3 +2)][0]+$datos1['Y'.($j*3 +3)][0]);
			$datos1['Z'.($j*3+2)][0] = ($datos1['Y'.($j*3 +2)][0] * 100)/($datos1['Y'.($j*3 +1)][0]+$datos1['Y'.($j*3 +2)][0]+$datos1['Y'.($j*3 +3)][0]);
			$datos1['Z'.($j*3+3)][0] = ($datos1['Y'.($j*3 +3)][0] * 100)/($datos1['Y'.($j*3 +1)][0]+$datos1['Y'.($j*3 +2)][0]+$datos1['Y'.($j*3 +3)][0]);
		}
	
		if(($datos1['Y'.($j*3 +1)][1]+$datos1['Y'.($j*3 +2)][1]+$datos1['Y'.($j*3 +3)][1])==0){
			$datos1['Z'.($j*3+1)][1] = 0;
			$datos1['Z'.($j*3+2)][1] = 0;
			$datos1['Z'.($j*3+3)][1] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][1] = ($datos1['Y'.($j*3 +1)][1] * 100)/($datos1['Y'.($j*3 +1)][1]+$datos1['Y'.($j*3 +2)][1]+$datos1['Y'.($j*3 +3)][1]);
			$datos1['Z'.($j*3+2)][1] = ($datos1['Y'.($j*3 +2)][1] * 100)/($datos1['Y'.($j*3 +1)][1]+$datos1['Y'.($j*3 +2)][1]+$datos1['Y'.($j*3 +3)][1]);
			$datos1['Z'.($j*3+3)][1] = ($datos1['Y'.($j*3 +3)][1] * 100)/($datos1['Y'.($j*3 +1)][1]+$datos1['Y'.($j*3 +2)][1]+$datos1['Y'.($j*3 +3)][1]);
		}
	
		if(($datos1['Y'.($j*3 +1)][2]+$datos1['Y'.($j*3 +2)][2]+$datos1['Y'.($j*3 +3)][2])==0){
			$datos1['Z'.($j*3+1)][2] = 0;
			$datos1['Z'.($j*3+2)][2] = 0;
			$datos1['Z'.($j*3+3)][2] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][2] = ($datos1['Y'.($j*3 +1)][2] * 100)/($datos1['Y'.($j*3 +1)][2]+$datos1['Y'.($j*3 +2)][2]+$datos1['Y'.($j*3 +3)][2]);
			$datos1['Z'.($j*3+2)][2] = ($datos1['Y'.($j*3 +2)][2] * 100)/($datos1['Y'.($j*3 +1)][2]+$datos1['Y'.($j*3 +2)][2]+$datos1['Y'.($j*3 +3)][2]);
			$datos1['Z'.($j*3+3)][2] = ($datos1['Y'.($j*3 +3)][2] * 100)/($datos1['Y'.($j*3 +1)][2]+$datos1['Y'.($j*3 +2)][2]+$datos1['Y'.($j*3 +3)][2]);
		}
	
		if(($datos1['Y'.($j*3 +1)][3]+$datos1['Y'.($j*3 +2)][3]+$datos1['Y'.($j*3 +3)][3])==0){
			$datos1['Z'.($j*3+1)][3] = 0;
			$datos1['Z'.($j*3+2)][3] = 0;
			$datos1['Z'.($j*3+3)][3] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][3] = ($datos1['Y'.($j*3 +1)][3] * 100)/($datos1['Y'.($j*3 +1)][3]+$datos1['Y'.($j*3 +2)][3]+$datos1['Y'.($j*3 +3)][3]);
			$datos1['Z'.($j*3+2)][3] = ($datos1['Y'.($j*3 +2)][3] * 100)/($datos1['Y'.($j*3 +1)][3]+$datos1['Y'.($j*3 +2)][3]+$datos1['Y'.($j*3 +3)][3]);
			$datos1['Z'.($j*3+3)][3] = ($datos1['Y'.($j*3 +3)][3] * 100)/($datos1['Y'.($j*3 +1)][3]+$datos1['Y'.($j*3 +2)][3]+$datos1['Y'.($j*3 +3)][3]);
		}
	
		if(($datos1['Y'.($j*3 +1)][4]+$datos1['Y'.($j*3 +2)][4]+$datos1['Y'.($j*3 +3)][4])==0){
			$datos1['Z'.($j*3+1)][4] = 0;
			$datos1['Z'.($j*3+2)][4] = 0;
			$datos1['Z'.($j*3+3)][4] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][4] = ($datos1['Y'.($j*3 +1)][4] * 100)/($datos1['Y'.($j*3 +1)][4]+$datos1['Y'.($j*3 +2)][4]+$datos1['Y'.($j*3 +3)][4]);
			$datos1['Z'.($j*3+2)][4] = ($datos1['Y'.($j*3 +2)][4] * 100)/($datos1['Y'.($j*3 +1)][4]+$datos1['Y'.($j*3 +2)][4]+$datos1['Y'.($j*3 +3)][4]);
			$datos1['Z'.($j*3+3)][4] = ($datos1['Y'.($j*3 +3)][4] * 100)/($datos1['Y'.($j*3 +1)][4]+$datos1['Y'.($j*3 +2)][4]+$datos1['Y'.($j*3 +3)][4]);
		}
	
		if(($datos1['Y'.($j*3 +1)][5]+$datos1['Y'.($j*3 +2)][5]+$datos1['Y'.($j*3 +3)][5])==0){
			$datos1['Z'.($j*3+1)][5] = 0;
			$datos1['Z'.($j*3+2)][5] = 0;
			$datos1['Z'.($j*3+3)][5] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][5] = ($datos1['Y'.($j*3 +1)][5] * 100)/($datos1['Y'.($j*3 +1)][5]+$datos1['Y'.($j*3 +2)][5]+$datos1['Y'.($j*3 +3)][5]);
			$datos1['Z'.($j*3+2)][5] = ($datos1['Y'.($j*3 +2)][5] * 100)/($datos1['Y'.($j*3 +1)][5]+$datos1['Y'.($j*3 +2)][5]+$datos1['Y'.($j*3 +3)][5]);
			$datos1['Z'.($j*3+3)][5] = ($datos1['Y'.($j*3 +3)][5] * 100)/($datos1['Y'.($j*3 +1)][5]+$datos1['Y'.($j*3 +2)][5]+$datos1['Y'.($j*3 +3)][5]);
		}
	
		if(($datos1['Y'.($j*3 +1)][6]+$datos1['Y'.($j*3 +2)][6]+$datos1['Y'.($j*3 +3)][6])==0){
			$datos1['Z'.($j*3+1)][6] = 0;
			$datos1['Z'.($j*3+2)][6] = 0;
			$datos1['Z'.($j*3+3)][6] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][6] = ($datos1['Y'.($j*3 +1)][6] * 100)/($datos1['Y'.($j*3 +1)][6]+$datos1['Y'.($j*3 +2)][6]+$datos1['Y'.($j*3 +3)][6]);
			$datos1['Z'.($j*3+2)][6] = ($datos1['Y'.($j*3 +2)][6] * 100)/($datos1['Y'.($j*3 +1)][6]+$datos1['Y'.($j*3 +2)][6]+$datos1['Y'.($j*3 +3)][6]);
			$datos1['Z'.($j*3+3)][6] = ($datos1['Y'.($j*3 +3)][6] * 100)/($datos1['Y'.($j*3 +1)][6]+$datos1['Y'.($j*3 +2)][6]+$datos1['Y'.($j*3 +3)][6]);
		}
	
		if(($datos1['Y'.($j*3 +1)][7]+$datos1['Y'.($j*3 +2)][7]+$datos1['Y'.($j*3 +3)][7])==0){
			$datos1['Z'.($j*3+1)][7] = 0;
			$datos1['Z'.($j*3+2)][7] = 0;
			$datos1['Z'.($j*3+3)][7] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][7] = ($datos1['Y'.($j*3 +1)][7] * 100)/($datos1['Y'.($j*3 +1)][7]+$datos1['Y'.($j*3 +2)][7]+$datos1['Y'.($j*3 +3)][7]);
			$datos1['Z'.($j*3+2)][7] = ($datos1['Y'.($j*3 +2)][7] * 100)/($datos1['Y'.($j*3 +1)][7]+$datos1['Y'.($j*3 +2)][7]+$datos1['Y'.($j*3 +3)][7]);
			$datos1['Z'.($j*3+3)][7] = ($datos1['Y'.($j*3 +3)][7] * 100)/($datos1['Y'.($j*3 +1)][7]+$datos1['Y'.($j*3 +2)][7]+$datos1['Y'.($j*3 +3)][7]);
		}
	
		if(($datos1['Y'.($j*3 +1)][8]+$datos1['Y'.($j*3 +2)][8]+$datos1['Y'.($j*3 +3)][8])==0){
			$datos1['Z'.($j*3+1)][8] = 0;
			$datos1['Z'.($j*3+2)][8] = 0;
			$datos1['Z'.($j*3+3)][8] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][8] = ($datos1['Y'.($j*3 +1)][8] * 100)/($datos1['Y'.($j*3 +1)][8]+$datos1['Y'.($j*3 +2)][8]+$datos1['Y'.($j*3 +3)][8]);
			$datos1['Z'.($j*3+2)][8] = ($datos1['Y'.($j*3 +2)][8] * 100)/($datos1['Y'.($j*3 +1)][8]+$datos1['Y'.($j*3 +2)][8]+$datos1['Y'.($j*3 +3)][8]);
			$datos1['Z'.($j*3+3)][8] = ($datos1['Y'.($j*3 +3)][8] * 100)/($datos1['Y'.($j*3 +1)][8]+$datos1['Y'.($j*3 +2)][8]+$datos1['Y'.($j*3 +3)][8]);
		}
	
		if(($datos1['Y'.($j*3 +1)][9]+$datos1['Y'.($j*3 +2)][9]+$datos1['Y'.($j*3 +3)][9])==0){
			$datos1['Z'.($j*3+1)][9] = 0;
			$datos1['Z'.($j*3+2)][9] = 0;
			$datos1['Z'.($j*3+3)][9] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][9] = ($datos1['Y'.($j*3 +1)][9] * 100)/($datos1['Y'.($j*3 +1)][9]+$datos1['Y'.($j*3 +2)][9]+$datos1['Y'.($j*3 +3)][9]);
			$datos1['Z'.($j*3+2)][9] = ($datos1['Y'.($j*3 +2)][9] * 100)/($datos1['Y'.($j*3 +1)][9]+$datos1['Y'.($j*3 +2)][9]+$datos1['Y'.($j*3 +3)][9]);
			$datos1['Z'.($j*3+3)][9] = ($datos1['Y'.($j*3 +3)][9] * 100)/($datos1['Y'.($j*3 +1)][9]+$datos1['Y'.($j*3 +2)][9]+$datos1['Y'.($j*3 +3)][9]);
		}
	
		if(($datos1['Y'.($j*3 +1)][10]+$datos1['Y'.($j*3 +2)][10]+$datos1['Y'.($j*3 +3)][10])==0){
			$datos1['Z'.($j*3+1)][10] = 0;
			$datos1['Z'.($j*3+2)][10] = 0;
			$datos1['Z'.($j*3+3)][10] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][10] = ($datos1['Y'.($j*3 +1)][10] * 100)/($datos1['Y'.($j*3 +1)][10]+$datos1['Y'.($j*3 +2)][10]+$datos1['Y'.($j*3 +3)][10]);
			$datos1['Z'.($j*3+2)][10] = ($datos1['Y'.($j*3 +2)][10] * 100)/($datos1['Y'.($j*3 +1)][10]+$datos1['Y'.($j*3 +2)][10]+$datos1['Y'.($j*3 +3)][10]);
			$datos1['Z'.($j*3+3)][10] = ($datos1['Y'.($j*3 +3)][10] * 100)/($datos1['Y'.($j*3 +1)][10]+$datos1['Y'.($j*3 +2)][10]+$datos1['Y'.($j*3 +3)][10]);
		}
	
		if(($datos1['Y'.($j*3 +1)][11]+$datos1['Y'.($j*3 +2)][11]+$datos1['Y'.($j*3 +3)][11])==0){
			$datos1['Z'.($j*3+1)][11] = 0;
			$datos1['Z'.($j*3+2)][11] = 0;
			$datos1['Z'.($j*3+3)][11] = 0;
		}
		else{
			$datos1['Z'.($j*3+1)][11] = ($datos1['Y'.($j*3 +1)][11] * 100)/($datos1['Y'.($j*3 +1)][11]+$datos1['Y'.($j*3 +2)][11]+$datos1['Y'.($j*3 +3)][11]);
			$datos1['Z'.($j*3+2)][11] = ($datos1['Y'.($j*3 +2)][11] * 100)/($datos1['Y'.($j*3 +1)][11]+$datos1['Y'.($j*3 +2)][11]+$datos1['Y'.($j*3 +3)][11]);
			$datos1['Z'.($j*3+3)][11] = ($datos1['Y'.($j*3 +3)][11] * 100)/($datos1['Y'.($j*3 +1)][11]+$datos1['Y'.($j*3 +2)][11]+$datos1['Y'.($j*3 +3)][11]);
		}
		$transparencia = rand(10,90)/100;
		$a = array('label'=>'Auditorias dictaminadas que cumplen los 7 dias de '.$datos0[$j]['NOMBRE'].'(%)','backgroundColor'=> 'rgba(255, 0, 0, '.$transparencia.')','stack'=>'Stack '.$j,'data'=> $datos1['Z'.($j*3+1)]);						
		array_push($resp,$a);
		$b = array('label'=>'Auditorias dictaminadas que no cumplen los 7 dias de '.$datos0[$j]['NOMBRE'].'(%)','backgroundColor'=> 'rgba(0, 255, 0, '.$transparencia.')','stack'=>'Stack '.$j,'data'=> $datos1['Z'.($j*3+2)]);	
		array_push($resp,$b);
		$c = array('label'=>'Auditorias que siguen sin dictaminar de '.$datos0[$j]['NOMBRE'].'(%)','backgroundColor'=> 'rgba(0, 0, 255, '.$transparencia.')','stack'=>'Stack '.$j,'data'=> $datos1['Z'.($j*3+3)]);	
		array_push($resp,$c);
	}
	
$datos1['Y'] = $resp;						
print_r(json_encode($datos1));

?> 
