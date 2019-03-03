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
$i1 = 0;
$i2 = 0;
$i3 = 0;
$i4 = 0;
$i5 = 0;
$ix = 0;
//$datos1['X'][$ix] = '';
//INICIALIZANDO VARIABLES
// A PARTIR DE AQUI ES PARA TIPO SERVICIO CALIDAD
$consulta = "SELECT `ID`,`CANTIDAD_CERTIFICADOS`,`FECHA`,`ID_TIPO_SERVICIO` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_curso."%' ORDER BY `ID` ASC";
$datos = $database->query($consulta)->fetchAll();
/*
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'1']); 
valida_error_medoo_and_die(); 
*/

//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	if($i==0){
		$datos1['X'][$ix] = $datos[$i]['FECHA'];
	}
	if($datos[$i]['FECHA'] != $datos1['X'][$ix]){
		$ix++;
		$datos1['X'][$ix] = $datos[$i]['FECHA'];
		
	}
	if($datos[$i]['ID_TIPO_SERVICIO'] == 1){
		$datos1['Y1'][$ix] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
		//$datos1['X'][$i1] = $datos[$i]['FECHA'];
		//$i1++;
	}
	if($datos[$i]['ID_TIPO_SERVICIO'] == 2){
		$datos1['Y2'][$ix] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
		//$datos1['X'][$i2] = $datos[$i]['FECHA'];
		//$i2++;
	}
	if($datos[$i]['ID_TIPO_SERVICIO'] == 12){
		$datos1['Y3'][$ix] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
		//$datos1['X'][$i3] = $datos[$i]['FECHA'];
		//$i3++;
	}
	if($datos[$i]['ID_TIPO_SERVICIO'] == 20){
		$datos1['Y4'][$ix] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
		//$datos1['X'][$i4] = $datos[$i]['FECHA'];
		//$i4++;
	}
	if($datos[$i]['ID_TIPO_SERVICIO'] == 21){
		$datos1['Y5'][$ix] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
		//$datos1['X'][$i5] = $datos[$i]['FECHA'];
		//$i5++;
	}
	
}	

/**************************************/
print_r(json_encode($datos1));

?> 
