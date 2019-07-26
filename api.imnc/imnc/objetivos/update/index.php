<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Es necesario un ID");
$NOMBRE_OBJETIVO = $objeto->NOMBRE_OBJETIVO;
valida_parametro_and_die($NOMBRE_OBJETIVO, "Es necesario introducir un nombre del objetivo");
$PRIORICIDAD = $objeto->PRIORICIDAD;
valida_parametro_and_die($PRIORICIDAD, "Es necesario seleccionar la periodicidad");
$str = "Es necesario ingresar el año";
if($PRIORICIDAD == 2)
{
	$str = "Es necesario seleccionar un mes";
}
$VALOR_PERIODICIDAD	= $objeto->VALOR_PERIODICIDAD;
valida_parametro_and_die($VALOR_PERIODICIDAD, $str);
$MONTO = $objeto->MONTO;
valida_parametro_and_die($MONTO, "Es necesario ingresar el monto");



$id_obj = $database->update("OBJETIVOS", [
	"NOMBRE" => $NOMBRE_OBJETIVO,
],["ID"=>$ID]);
valida_error_medoo_and_die();

$id_obj_valor = $database->update("OBJETIVO_VALORES", [
	"VALOR_OBJETIVO" => $MONTO,
	"VALOR_PERIODICIDAD" => $VALOR_PERIODICIDAD,
	"OBJETIVOS_PERIODICIDADES_ID" => $PRIORICIDAD,
	"OBJETIVOS_ID" => $ID,
],["OBJETIVOS_ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));

?> 
