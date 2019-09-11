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
$ANHIO = $objeto->ANHIO;
valida_parametro_and_die($ANHIO, "Es necesario introducir el año");
$MONTO = $objeto->MONTO;
valida_parametro_and_die($MONTO, "Es necesario ingresar el monto");


$monto_anterior = $database->select("OBJETIVO_VALORES", ["VALOR_OBJETIVO" ],["AND"=>["OBJETIVOS_ID"=>$ID,"ANHIO"=>$ANHIO]]);

$id_obj_valor = $database->update("OBJETIVO_VALORES", [
	"VALOR_OBJETIVO" => $MONTO
	
],["AND"=>["OBJETIVOS_ID"=>$ID,"ANHIO"=>$ANHIO]]);

valida_error_medoo_and_die();

if($ID == 1 || $ID == 2 || $ID == 3 || $ID == 4 || $ID == 5 || $ID == 6 || $ID == 7 || $ID == 8 || $ID == 9 || $ID == 10 || $ID == 11 || $ID == 12 ){
	$sum_ant = $database->select("OBJETIVO_VALORES", ["VALOR_OBJETIVO" ],["AND"=>["OBJETIVOS_ID"=>25,"ANHIO"=>$ANHIO]]);
	$id_obj_valor = $database->update("OBJETIVO_VALORES", [
	"VALOR_OBJETIVO" => $sum_ant[0]["VALOR_OBJETIVO"] + $MONTO - $monto_anterior[0]["VALOR_OBJETIVO"]
	
],["AND"=>["OBJETIVOS_ID"=>25,"ANHIO"=>$ANHIO]]);
}
if($ID == 13 || $ID == 14 || $ID == 15 || $ID == 16 || $ID == 17 || $ID == 18 || $ID == 19 || $ID == 20 || $ID == 21 || $ID == 22 || $ID == 23 || $ID == 24 ){
	$sum_ant = $database->select("OBJETIVO_VALORES", ["VALOR_OBJETIVO" ],["AND"=>["OBJETIVOS_ID"=>26,"ANHIO"=>$ANHIO]]);
	$id_obj_valor = $database->update("OBJETIVO_VALORES", [
	"VALOR_OBJETIVO" => $sum_ant[0]["VALOR_OBJETIVO"] + $MONTO - $monto_anterior[0]["VALOR_OBJETIVO"]
	
],["AND"=>["OBJETIVOS_ID"=>26,"ANHIO"=>$ANHIO]]);
}
$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));

?> 
