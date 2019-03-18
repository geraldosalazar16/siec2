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

$ix = 0;
//$datos1['X'][$ix] = '';
//INICIALIZANDO VARIABLES
$consulta = "SELECT `ID`,`SECTOR_PUBLICO`,`SECTOR_PRIVADO`,`FECHA` FROM `REPORTES_CLIENTES` WHERE `FECHA` LIKE '".$ano_curso."%' ORDER BY `ID` ASC";
$datos = $database->query($consulta)->fetchAll();


//RECORRIENDO LOS DATOS
for($i=0;$i<count($datos);$i++){
	if($i==0){
		$datos1['X'][$ix] = $datos[$i]['FECHA'];
	}
	if($datos[$i]['FECHA'] != $datos1['X'][$ix]){
		$ix++;
		$datos1['X'][$ix] = $datos[$i]['FECHA'];
		
	}
		
	$datos1['Y1'][$ix] = $datos[$i]['SECTOR_PUBLICO'];
	$datos1['Y2'][$ix] = $datos[$i]['SECTOR_PRIVADO'];
	
	
	
}	

/**************************************/
print_r(json_encode($datos1));

?> 
