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
//INICIALIZANDO VARIABLES
switch($mes_curso){
	case 0:
		$datos1['X'][0]= 'Enero';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		break;
	case 1:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		break;
	case 2:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		break;
	case 3:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		break;
	case 4:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['X'][4]= 'Mayo';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		break;
	case 6:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['X'][4]= 'Mayo';
		$datos1['X'][5]= 'Junio';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		break;
	case 7:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['X'][4]= 'Mayo';
		$datos1['X'][5]= 'Junio';
		$datos1['X'][6]= 'Julio';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		break;
	case 8:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['X'][4]= 'Mayo';
		$datos1['X'][5]= 'Junio';
		$datos1['X'][6]= 'Julio';
		$datos1['X'][7]= 'Agosto';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		$datos1['Y1'][7]= '';		$datos1['Dia1'][7]= '';		$datos1['ID1'][7]= '';
		$datos1['Y2'][7]= '';		$datos1['Dia2'][7]= '';		$datos1['ID2'][7]= '';
		$datos1['Y3'][7]= '';		$datos1['Dia3'][7]= '';		$datos1['ID3'][7]= '';
		$datos1['Y4'][7]= '';		$datos1['Dia4'][7]= '';		$datos1['ID4'][7]= '';
		$datos1['Y5'][7]= '';		$datos1['Dia5'][7]= '';		$datos1['ID5'][7]= '';
		break;
	case 9:
		$datos1['X'][0]= 'Enero';
		$datos1['X'][1]= 'Febrero';
		$datos1['X'][2]= 'Marzo';
 		$datos1['X'][3]= 'Abril';
 		$datos1['X'][4]= 'Mayo';
		$datos1['X'][5]= 'Junio';
		$datos1['X'][6]= 'Julio';
		$datos1['X'][7]= 'Agosto';
		$datos1['X'][8]= 'Septiembre';
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		$datos1['Y1'][7]= '';		$datos1['Dia1'][7]= '';		$datos1['ID1'][7]= '';
		$datos1['Y2'][7]= '';		$datos1['Dia2'][7]= '';		$datos1['ID2'][7]= '';
		$datos1['Y3'][7]= '';		$datos1['Dia3'][7]= '';		$datos1['ID3'][7]= '';
		$datos1['Y4'][7]= '';		$datos1['Dia4'][7]= '';		$datos1['ID4'][7]= '';
		$datos1['Y5'][7]= '';		$datos1['Dia5'][7]= '';		$datos1['ID5'][7]= '';
		$datos1['Y1'][8]= '';		$datos1['Dia1'][8]= '';		$datos1['ID1'][8]= '';
		$datos1['Y2'][8]= '';		$datos1['Dia2'][8]= '';		$datos1['ID2'][8]= '';
		$datos1['Y3'][8]= '';		$datos1['Dia3'][8]= '';		$datos1['ID3'][8]= '';
		$datos1['Y4'][8]= '';		$datos1['Dia4'][8]= '';		$datos1['ID4'][8]= '';
		$datos1['Y5'][8]= '';		$datos1['Dia5'][8]= '';		$datos1['ID5'][8]= '';
		break;
	case 10:
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
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		$datos1['Y1'][7]= '';		$datos1['Dia1'][7]= '';		$datos1['ID1'][7]= '';
		$datos1['Y2'][7]= '';		$datos1['Dia2'][7]= '';		$datos1['ID2'][7]= '';
		$datos1['Y3'][7]= '';		$datos1['Dia3'][7]= '';		$datos1['ID3'][7]= '';
		$datos1['Y4'][7]= '';		$datos1['Dia4'][7]= '';		$datos1['ID4'][7]= '';
		$datos1['Y5'][7]= '';		$datos1['Dia5'][7]= '';		$datos1['ID5'][7]= '';
		$datos1['Y1'][8]= '';		$datos1['Dia1'][8]= '';		$datos1['ID1'][8]= '';
		$datos1['Y2'][8]= '';		$datos1['Dia2'][8]= '';		$datos1['ID2'][8]= '';
		$datos1['Y3'][8]= '';		$datos1['Dia3'][8]= '';		$datos1['ID3'][8]= '';
		$datos1['Y4'][8]= '';		$datos1['Dia4'][8]= '';		$datos1['ID4'][8]= '';
		$datos1['Y5'][8]= '';		$datos1['Dia5'][8]= '';		$datos1['ID5'][8]= '';
		$datos1['Y1'][9]= '';		$datos1['Dia1'][9]= '';		$datos1['ID1'][9]= '';
		$datos1['Y2'][9]= '';		$datos1['Dia2'][9]= '';		$datos1['ID2'][9]= '';
		$datos1['Y3'][9]= '';		$datos1['Dia3'][9]= '';		$datos1['ID3'][9]= '';
		$datos1['Y4'][9]= '';		$datos1['Dia4'][9]= '';		$datos1['ID4'][9]= '';
		$datos1['Y5'][9]= '';		$datos1['Dia5'][9]= '';		$datos1['ID5'][9]= '';
		break;	
	case 11:
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
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		$datos1['Y1'][7]= '';		$datos1['Dia1'][7]= '';		$datos1['ID1'][7]= '';
		$datos1['Y2'][7]= '';		$datos1['Dia2'][7]= '';		$datos1['ID2'][7]= '';
		$datos1['Y3'][7]= '';		$datos1['Dia3'][7]= '';		$datos1['ID3'][7]= '';
		$datos1['Y4'][7]= '';		$datos1['Dia4'][7]= '';		$datos1['ID4'][7]= '';
		$datos1['Y5'][7]= '';		$datos1['Dia5'][7]= '';		$datos1['ID5'][7]= '';
		$datos1['Y1'][8]= '';		$datos1['Dia1'][8]= '';		$datos1['ID1'][8]= '';
		$datos1['Y2'][8]= '';		$datos1['Dia2'][8]= '';		$datos1['ID2'][8]= '';
		$datos1['Y3'][8]= '';		$datos1['Dia3'][8]= '';		$datos1['ID3'][8]= '';
		$datos1['Y4'][8]= '';		$datos1['Dia4'][8]= '';		$datos1['ID4'][8]= '';
		$datos1['Y5'][8]= '';		$datos1['Dia5'][8]= '';		$datos1['ID5'][8]= '';
		$datos1['Y1'][9]= '';		$datos1['Dia1'][9]= '';		$datos1['ID1'][9]= '';
		$datos1['Y2'][9]= '';		$datos1['Dia2'][9]= '';		$datos1['ID2'][9]= '';
		$datos1['Y3'][9]= '';		$datos1['Dia3'][9]= '';		$datos1['ID3'][9]= '';
		$datos1['Y4'][9]= '';		$datos1['Dia4'][9]= '';		$datos1['ID4'][9]= '';
		$datos1['Y5'][9]= '';		$datos1['Dia5'][9]= '';		$datos1['ID5'][9]= '';
		$datos1['Y1'][10]= '';		$datos1['Dia1'][10]= '';		$datos1['ID1'][10]= '';
		$datos1['Y2'][10]= '';		$datos1['Dia2'][10]= '';		$datos1['ID2'][10]= '';
		$datos1['Y3'][10]= '';		$datos1['Dia3'][10]= '';		$datos1['ID3'][10]= '';
		$datos1['Y4'][10]= '';		$datos1['Dia4'][10]= '';		$datos1['ID4'][10]= '';
		$datos1['Y5'][10]= '';		$datos1['Dia5'][10]= '';		$datos1['ID5'][10]= '';
		break;
	case 12:
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
 		$datos1['Y1'][0]= '';		$datos1['Dia1'][0]= '';		$datos1['ID1'][0]= '';
		$datos1['Y2'][0]= '';		$datos1['Dia2'][0]= '';		$datos1['ID2'][0]= '';
		$datos1['Y3'][0]= '';		$datos1['Dia3'][0]= '';		$datos1['ID3'][0]= '';
		$datos1['Y4'][0]= '';		$datos1['Dia4'][0]= '';		$datos1['ID4'][0]= '';
		$datos1['Y5'][0]= '';		$datos1['Dia5'][0]= '';		$datos1['ID5'][0]= '';
		$datos1['Y1'][1]= '';		$datos1['Dia1'][1]= '';		$datos1['ID1'][1]= '';
		$datos1['Y2'][1]= '';		$datos1['Dia2'][1]= '';		$datos1['ID2'][1]= '';
		$datos1['Y3'][1]= '';		$datos1['Dia3'][1]= '';		$datos1['ID3'][1]= '';
		$datos1['Y4'][1]= '';		$datos1['Dia4'][1]= '';		$datos1['ID4'][1]= '';
		$datos1['Y5'][1]= '';		$datos1['Dia5'][1]= '';		$datos1['ID5'][1]= '';
		$datos1['Y1'][2]= '';		$datos1['Dia1'][2]= '';		$datos1['ID1'][2]= '';
		$datos1['Y2'][2]= '';		$datos1['Dia2'][2]= '';		$datos1['ID2'][2]= '';
		$datos1['Y3'][2]= '';		$datos1['Dia3'][2]= '';		$datos1['ID3'][2]= '';
		$datos1['Y4'][2]= '';		$datos1['Dia4'][2]= '';		$datos1['ID4'][2]= '';
		$datos1['Y5'][2]= '';		$datos1['Dia5'][2]= '';		$datos1['ID5'][2]= '';
		$datos1['Y1'][3]= '';		$datos1['Dia1'][3]= '';		$datos1['ID1'][3]= '';
		$datos1['Y2'][3]= '';		$datos1['Dia2'][3]= '';		$datos1['ID2'][3]= '';
		$datos1['Y3'][3]= '';		$datos1['Dia3'][3]= '';		$datos1['ID3'][3]= '';
		$datos1['Y4'][3]= '';		$datos1['Dia4'][3]= '';		$datos1['ID4'][3]= '';
		$datos1['Y5'][3]= '';		$datos1['Dia5'][3]= '';		$datos1['ID5'][3]= '';
		$datos1['Y1'][4]= '';		$datos1['Dia1'][4]= '';		$datos1['ID1'][4]= '';
		$datos1['Y2'][4]= '';		$datos1['Dia2'][4]= '';		$datos1['ID2'][4]= '';
		$datos1['Y3'][4]= '';		$datos1['Dia3'][4]= '';		$datos1['ID3'][4]= '';
		$datos1['Y4'][4]= '';		$datos1['Dia4'][4]= '';		$datos1['ID4'][4]= '';
		$datos1['Y5'][4]= '';		$datos1['Dia5'][4]= '';		$datos1['ID5'][4]= '';
		$datos1['Y1'][5]= '';		$datos1['Dia1'][5]= '';		$datos1['ID1'][5]= '';
		$datos1['Y2'][5]= '';		$datos1['Dia2'][5]= '';		$datos1['ID2'][5]= '';
		$datos1['Y3'][5]= '';		$datos1['Dia3'][5]= '';		$datos1['ID3'][5]= '';
		$datos1['Y4'][5]= '';		$datos1['Dia4'][5]= '';		$datos1['ID4'][5]= '';
		$datos1['Y5'][5]= '';		$datos1['Dia5'][5]= '';		$datos1['ID5'][5]= '';
		$datos1['Y1'][6]= '';		$datos1['Dia1'][6]= '';		$datos1['ID1'][6]= '';
		$datos1['Y2'][6]= '';		$datos1['Dia2'][6]= '';		$datos1['ID2'][6]= '';
		$datos1['Y3'][6]= '';		$datos1['Dia3'][6]= '';		$datos1['ID3'][6]= '';
		$datos1['Y4'][6]= '';		$datos1['Dia4'][6]= '';		$datos1['ID4'][6]= '';
		$datos1['Y5'][6]= '';		$datos1['Dia5'][6]= '';		$datos1['ID5'][6]= '';
		$datos1['Y1'][7]= '';		$datos1['Dia1'][7]= '';		$datos1['ID1'][7]= '';
		$datos1['Y2'][7]= '';		$datos1['Dia2'][7]= '';		$datos1['ID2'][7]= '';
		$datos1['Y3'][7]= '';		$datos1['Dia3'][7]= '';		$datos1['ID3'][7]= '';
		$datos1['Y4'][7]= '';		$datos1['Dia4'][7]= '';		$datos1['ID4'][7]= '';
		$datos1['Y5'][7]= '';		$datos1['Dia5'][7]= '';		$datos1['ID5'][7]= '';
		$datos1['Y1'][8]= '';		$datos1['Dia1'][8]= '';		$datos1['ID1'][8]= '';
		$datos1['Y2'][8]= '';		$datos1['Dia2'][8]= '';		$datos1['ID2'][8]= '';
		$datos1['Y3'][8]= '';		$datos1['Dia3'][8]= '';		$datos1['ID3'][8]= '';
		$datos1['Y4'][8]= '';		$datos1['Dia4'][8]= '';		$datos1['ID4'][8]= '';
		$datos1['Y5'][8]= '';		$datos1['Dia5'][8]= '';		$datos1['ID5'][8]= '';
		$datos1['Y1'][9]= '';		$datos1['Dia1'][9]= '';		$datos1['ID1'][9]= '';
		$datos1['Y2'][9]= '';		$datos1['Dia2'][9]= '';		$datos1['ID2'][9]= '';
		$datos1['Y3'][9]= '';		$datos1['Dia3'][9]= '';		$datos1['ID3'][9]= '';
		$datos1['Y4'][9]= '';		$datos1['Dia4'][9]= '';		$datos1['ID4'][9]= '';
		$datos1['Y5'][9]= '';		$datos1['Dia5'][9]= '';		$datos1['ID5'][9]= '';
		$datos1['Y1'][10]= '';		$datos1['Dia1'][10]= '';		$datos1['ID1'][10]= '';
		$datos1['Y2'][10]= '';		$datos1['Dia2'][10]= '';		$datos1['ID2'][10]= '';
		$datos1['Y3'][10]= '';		$datos1['Dia3'][10]= '';		$datos1['ID3'][10]= '';
		$datos1['Y4'][10]= '';		$datos1['Dia4'][10]= '';		$datos1['ID4'][10]= '';
		$datos1['Y5'][10]= '';		$datos1['Dia5'][10]= '';		$datos1['ID5'][10]= '';
		$datos1['Y1'][11]= '';		$datos1['Dia1'][11]= '';		$datos1['ID1'][11]= '';
		$datos1['Y2'][11]= '';		$datos1['Dia2'][11]= '';		$datos1['ID2'][11]= '';
		$datos1['Y3'][11]= '';		$datos1['Dia3'][11]= '';		$datos1['ID3'][11]= '';
		$datos1['Y4'][11]= '';		$datos1['Dia4'][11]= '';		$datos1['ID4'][11]= '';
		$datos1['Y5'][11]= '';		$datos1['Dia5'][11]= '';		$datos1['ID5'][11]= '';
		break;	
}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO CALIDAD
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'1']); 
valida_error_medoo_and_die(); 


//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	$ano = intval(substr($datos[$i]['FECHA'],0,4));
	$mes = intval(substr($datos[$i]['FECHA'],4,2))-1;
	$dia = intval(substr($datos[$i]['FECHA'],6,2));
	if($ano == $ano_curso){
		if($datos1['Y1'][$mes] == ''){
			$datos1['Y1'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
			$datos1['Dia1'][$mes]= $dia;
			$datos1['ID1'][$mes] = $datos[$i]['ID'];
		}
		else{
			if($datos1['Dia1'][$mes] <= $dia){
				if( $datos1['ID1'][$mes]  < $datos[$i]['ID'] ) {
					$datos1['Y1'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
					$datos1['Dia1'][$mes]= $dia;
					$datos1['ID1'][$mes] = $datos[$i]['ID'];
				}	
				
			}
		}
	}
	
}
for($i=0;$i<$mes_curso;$i++){
	if($datos1['Y1'][$i] == '' && $i==0){
		$consulta = "SELECT `ID`,`FECHA`,`CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_anterior."%' AND `ID_TIPO_SERVICIO` = 1 ORDER BY `ID` DESC";
		$dd1 = $database->query($consulta)->fetchAll();
		if(empty($dd1)){
			$datos1['Y1'][$i] = ''; 
		}
		else{
			$datos1['Y1'][$i] = $dd1[0]['CANTIDAD_CERTIFICADOS']; 
		}	
		
	}
	if($datos1['Y1'][$i] == '' && $i>0){
		$datos1['Y1'][$i] = $datos1['Y1'][$i-1];
	}
	
}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO AMBIENTE
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'2']); 
valida_error_medoo_and_die(); 

//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	$ano = intval(substr($datos[$i]['FECHA'],0,4));
	$mes = intval(substr($datos[$i]['FECHA'],4,2))-1;
	$dia = intval(substr($datos[$i]['FECHA'],6,2));
	if($ano == $ano_curso){
		if($datos1['Y2'][$mes] == ''){
			$datos1['Y2'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
			$datos1['Dia2'][$mes]= $dia;
			$datos1['ID2'][$mes] = $datos[$i]['ID'];
		}
		else{
			if($datos1['Dia2'][$mes] <= $dia){
				if( $datos1['ID2'][$mes]  < $datos[$i]['ID'] ) {
					$datos1['Y2'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
					$datos1['Dia2'][$mes]= $dia;
					$datos1['ID2'][$mes] = $datos[$i]['ID'];
				}	
				
			}
		}
	}
	
}
for($i=0;$i<$mes_curso;$i++){
	if($datos1['Y2'][$i] == '' && $i==0){
		$consulta = "SELECT `ID`,`FECHA`,`CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_anterior."%' AND `ID_TIPO_SERVICIO` =2 ORDER BY `ID` DESC";
		$dd2 = $database->query($consulta)->fetchAll();
		if(empty($dd2)){
			$datos1['Y2'][$i] = ''; 
		}
		else{
			$datos1['Y2'][$i] = $dd2[0]['CANTIDAD_CERTIFICADOS']; 
		}	
		
	}
	if($datos1['Y2'][$i] == '' && $i>0){
		$datos1['Y2'][$i] = $datos1['Y2'][$i-1];
	}
	
}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO SAST
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'12']); 
valida_error_medoo_and_die(); 

//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	$ano = intval(substr($datos[$i]['FECHA'],0,4));
	$mes = intval(substr($datos[$i]['FECHA'],4,2))-1;
	$dia = intval(substr($datos[$i]['FECHA'],6,2));
	if($ano == $ano_curso){
		if($datos1['Y3'][$mes] == ''){
			$datos1['Y3'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
			$datos1['Dia3'][$mes]= $dia;
			$datos1['ID3'][$mes] = $datos[$i]['ID'];
		}
		else{
			if($datos1['Dia3'][$mes] <= $dia){
				if( $datos1['ID3'][$mes]  < $datos[$i]['ID'] ) {
					$datos1['Y3'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
					$datos1['Dia3'][$mes]= $dia;
					$datos1['ID3'][$mes] = $datos[$i]['ID'];
				}	
				
			}
		}
	}
	
}
for($i=0;$i<$mes_curso;$i++){
	if($datos1['Y3'][$i] == '' && $i==0){
		$consulta = "SELECT `ID`,`FECHA`,`CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_anterior."%' AND `ID_TIPO_SERVICIO` =12 ORDER BY `ID` DESC";
		$dd3 = $database->query($consulta)->fetchAll();
		if(empty($dd3)){
			$datos1['Y3'][$i] = ''; 
		}
		else{
			$datos1['Y3'][$i] = $dd3[0]['CANTIDAD_CERTIFICADOS']; 
		}	
		
	}
	if($datos1['Y3'][$i] == '' && $i>0){
		$datos1['Y3'][$i] = $datos1['Y3'][$i-1];
	}
	
}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO INTEGRAL
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'20']); 
valida_error_medoo_and_die(); 
/**************************************/

//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	$ano = intval(substr($datos[$i]['FECHA'],0,4));
	$mes = intval(substr($datos[$i]['FECHA'],4,2))-1;
	$dia = intval(substr($datos[$i]['FECHA'],6,2));
	if($ano == $ano_curso){
		if($datos1['Y4'][$mes] == ''){
			$datos1['Y4'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
			$datos1['Dia4'][$mes]= $dia;
			$datos1['ID4'][$mes] = $datos[$i]['ID'];
		}
		else{
			if($datos1['Dia4'][$mes] <= $dia){
				if( $datos1['ID4'][$mes]  < $datos[$i]['ID'] ) {
					$datos1['Y4'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
					$datos1['Dia4'][$mes]= $dia;
					$datos1['ID4'][$mes] = $datos[$i]['ID'];
				}	
				
			}
		}
	}
	
}
for($i=0;$i<$mes_curso;$i++){
	if($datos1['Y4'][$i] == '' && $i==0){
		$consulta = "SELECT `ID`,`FECHA`,`CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_anterior."%' AND `ID_TIPO_SERVICIO` =20 ORDER BY `ID` DESC";
		$dd4 = $database->query($consulta)->fetchAll();
		if(empty($dd4)){
			$datos1['Y4'][$i] = ''; 
		}
		else{
			$datos1['Y4'][$i] = $dd4[0]['CANTIDAD_CERTIFICADOS']; 
		}	
		
	}
	if($datos1['Y4'][$i] == '' && $i>0){
		$datos1['Y4'][$i] = $datos1['Y4'][$i-1];
	}
	
}
// A PARTIR DE AQUI ES PARA TIPO SERVICIO ENERGIA
$datos = $database->select("REPORTES_CERTIFICADOS_VIGENTES",
														[
															"REPORTES_CERTIFICADOS_VIGENTES.ID",
															"REPORTES_CERTIFICADOS_VIGENTES.FECHA",
															"REPORTES_CERTIFICADOS_VIGENTES.CANTIDAD_CERTIFICADOS"
															
														],['REPORTES_CERTIFICADOS_VIGENTES.ID_TIPO_SERVICIO'=>'21']); 
valida_error_medoo_and_die(); 
/**************************************/

//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	$ano = intval(substr($datos[$i]['FECHA'],0,4));
	$mes = intval(substr($datos[$i]['FECHA'],4,2))-1;
	$dia = intval(substr($datos[$i]['FECHA'],6,2));
	if($ano == $ano_curso){
		if($datos1['Y5'][$mes] == ''){
			$datos1['Y5'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
			$datos1['Dia5'][$mes]= $dia;
			$datos1['ID5'][$mes] = $datos[$i]['ID'];
		}
		else{
			if($datos1['Dia5'][$mes] <= $dia){
				if( $datos1['ID5'][$mes]  < $datos[$i]['ID'] ) {
					$datos1['Y5'][$mes] = $datos[$i]['CANTIDAD_CERTIFICADOS'];
					$datos1['Dia5'][$mes]= $dia;
					$datos1['ID5'][$mes] = $datos[$i]['ID'];
				}	
				
			}
		}
	}
	
}
for($i=0;$i<$mes_curso;$i++){
	if($datos1['Y5'][$i] == '' && $i==0){
		$consulta = "SELECT `ID`,`FECHA`,`CANTIDAD_CERTIFICADOS` FROM `REPORTES_CERTIFICADOS_VIGENTES` WHERE `FECHA` LIKE '".$ano_anterior."%' AND `ID_TIPO_SERVICIO` =21 ORDER BY `ID` DESC";
		$dd5 = $database->query($consulta)->fetchAll();
		if(empty($dd5)){
			$datos1['Y5'][$i] = ''; 
		}
		else{
			$datos1['Y5'][$i] = $dd5[0]['CANTIDAD_CERTIFICADOS']; 
		}	
		
	}
	if($datos1['Y5'][$i] == '' && $i>0){
		$datos1['Y5'][$i] = $datos1['Y5'][$i-1];
	}
	
}
/**************************************/
print_r(json_encode($datos1));

?> 
