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
print_r(json_encode($datos1));

?> 
