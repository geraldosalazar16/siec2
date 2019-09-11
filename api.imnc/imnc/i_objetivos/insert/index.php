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

$PROPUESTAS = $objeto->PROPUESTAS;
valida_parametro_and_die($PROPUESTAS, "Es necesario introducir la propuesta");

$ANHIO = $objeto->ANHIO;
valida_parametro_and_die($ANHIO, "Es necesario seleccionar un anhio");

$MONTO_ENERO = $objeto->MONTO_ENERO;
valida_parametro_and_die($MONTO_ENERO, "Es necesario ingresar el monto de enero");

$MONTO_FEBRERO = $objeto->MONTO_FEBRERO;
valida_parametro_and_die($MONTO_FEBRERO, "Es necesario ingresar el monto de enero");

$MONTO_MARZO = $objeto->MONTO_MARZO;
valida_parametro_and_die($MONTO_MARZO, "Es necesario ingresar el monto de marzo");

$MONTO_ABRIL = $objeto->MONTO_ABRIL;
valida_parametro_and_die($MONTO_ABRIL, "Es necesario ingresar el monto de abril");

$MONTO_MAYO = $objeto->MONTO_MAYO;
valida_parametro_and_die($MONTO_MAYO, "Es necesario ingresar el monto de mayo");

$MONTO_JUNIO = $objeto->MONTO_JUNIO;
valida_parametro_and_die($MONTO_JUNIO, "Es necesario ingresar el monto de junio");

$MONTO_JULIO = $objeto->MONTO_JULIO;
valida_parametro_and_die($MONTO_JULIO, "Es necesario ingresar el monto de julio");

$MONTO_AGOSTO = $objeto->MONTO_AGOSTO;
valida_parametro_and_die($MONTO_AGOSTO, "Es necesario ingresar el monto de agosto");

$MONTO_SEPTIEMBRE = $objeto->MONTO_SEPTIEMBRE;
valida_parametro_and_die($MONTO_SEPTIEMBRE, "Es necesario ingresar el monto de septiembre");

$MONTO_OCTUBRE = $objeto->MONTO_OCTUBRE;
valida_parametro_and_die($MONTO_OCTUBRE, "Es necesario ingresar el monto de octubre");

$MONTO_NOVIEMBRE = $objeto->MONTO_NOVIEMBRE;
valida_parametro_and_die($MONTO_NOVIEMBRE, "Es necesario ingresar el monto de noviembre");

$MONTO_DICIEMBRE = $objeto->MONTO_DICIEMBRE;
valida_parametro_and_die($MONTO_DICIEMBRE, "Es necesario ingresar el monto de diciembre");


if($PROPUESTAS == 'Propuestas Emitidas'){
	if($database->count("OBJETIVO_VALORES",["AND"=>['ANHIO'=>$ANHIO,'OBJETIVOS_PERIODICIDADES_ID'=>1,'OBJETIVOS_ID'=>25]])==0){
		//INSERTO ENERO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 1,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_ENERO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 1,
									]);
		valida_error_medoo_and_die();
		//INSERTO FEBRERO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 2,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_FEBRERO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 2,
									]);
		valida_error_medoo_and_die();
		//INSERTO MARZO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 3,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_MARZO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 3,
									]);
		valida_error_medoo_and_die();
		//INSERTO ABRIL
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 4,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_ABRIL,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 4,
									]);
		valida_error_medoo_and_die();
		//INSERTO MAYO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 5,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_MAYO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 5,
									]);
		valida_error_medoo_and_die();
		//INSERTO JUNIO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 6,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_JUNIO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 6,
									]);
		valida_error_medoo_and_die();
		//INSERTO JULIO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 7,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_JULIO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 7,
									]);
		valida_error_medoo_and_die();
		//INSERTO AGOSTO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 8,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_AGOSTO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 8,
									]);
		valida_error_medoo_and_die();
		//INSERTO SEPTIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 9,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_SEPTIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 9,
									]);
		valida_error_medoo_and_die();
		//INSERTO OCTUBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 10,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_OCTUBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 10,
									]);
		valida_error_medoo_and_die();
		//INSERTO NOVIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 11,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_NOVIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 11,
									]);
		valida_error_medoo_and_die();
		//INSERTO DICIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 12,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_DICIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 12,
									]);
		valida_error_medoo_and_die();
		//INSERTO OBJETIVO ANUAL
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => '',
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => ($MONTO_ENERO+$MONTO_FEBRERO+$MONTO_MARZO+$MONTO_ABRIL+$MONTO_MAYO+$MONTO_JUNIO+$MONTO_JULIO+$MONTO_AGOSTO+$MONTO_SEPTIEMBRE+$MONTO_OCTUBRE+$MONTO_NOVIEMBRE+$MONTO_DICIEMBRE),
										"OBJETIVOS_PERIODICIDADES_ID" => 1,
										"OBJETIVOS_ID" => 25,
									]);
		valida_error_medoo_and_die();
	}
	else{
		$respuesta["resultado"]="error";
		$respuesta["mensaje"]="Los objetivos de propuestas emitidas ya fueron insertado para el ".$ANHIO;
		print_r(json_encode($respuesta));
		die();
	}
}
if($PROPUESTAS == 'Propuestas Ganadas'){
	if($database->count("OBJETIVO_VALORES",["AND"=>['ANHIO'=>$ANHIO,'OBJETIVOS_PERIODICIDADES_ID'=>1,'OBJETIVOS_ID'=>26]])==0){
		//INSERTO ENERO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 1,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_ENERO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 13,
									]);
		valida_error_medoo_and_die();
		//INSERTO FEBRERO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 2,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_FEBRERO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 14,
									]);
		valida_error_medoo_and_die();
		//INSERTO MARZO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 3,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_MARZO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 15,
									]);
		valida_error_medoo_and_die();
		//INSERTO ABRIL
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 4,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_ABRIL,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 16,
									]);
		valida_error_medoo_and_die();
		//INSERTO MAYO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 5,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_MAYO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 17,
									]);
		valida_error_medoo_and_die();
		//INSERTO JUNIO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 6,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_JUNIO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 18,
									]);
		valida_error_medoo_and_die();
		//INSERTO JULIO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 7,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_JULIO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 19,
									]);
		valida_error_medoo_and_die();
		//INSERTO AGOSTO
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 8,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_AGOSTO,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 20,
									]);
		valida_error_medoo_and_die();
		//INSERTO SEPTIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 9,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_SEPTIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 21,
									]);
		valida_error_medoo_and_die();
		//INSERTO OCTUBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 10,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_OCTUBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 22,
									]);
		valida_error_medoo_and_die();
		//INSERTO NOVIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 11,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_NOVIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 23,
									]);
		valida_error_medoo_and_die();
		//INSERTO DICIEMBRE
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => 12,
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => $MONTO_DICIEMBRE,
										"OBJETIVOS_PERIODICIDADES_ID" => 2,
										"OBJETIVOS_ID" => 24,
									]);
		valida_error_medoo_and_die();
		//INSERTO OBJETIVO ANUAL
		$id_obj_valor = $database->insert("OBJETIVO_VALORES", [
										"MES" => '',
										"ANHIO" => $ANHIO,
										"VALOR_OBJETIVO" => ($MONTO_ENERO+$MONTO_FEBRERO+$MONTO_MARZO+$MONTO_ABRIL+$MONTO_MAYO+$MONTO_JUNIO+$MONTO_JULIO+$MONTO_AGOSTO+$MONTO_SEPTIEMBRE+$MONTO_OCTUBRE+$MONTO_NOVIEMBRE+$MONTO_DICIEMBRE),
										"OBJETIVOS_PERIODICIDADES_ID" => 1,
										"OBJETIVOS_ID" => 26,
									]);
		valida_error_medoo_and_die();
	}
	else{
		$respuesta["resultado"]="error";
		$respuesta["mensaje"]="Los objetivos de propuestas ganadas ya fueron insertado para el ".$ANHIO;
		print_r(json_encode($respuesta));
		die();
	}
}

	


$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
