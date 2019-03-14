<?php 
include  '../../common/conn-apiserver.php'; 

include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 




function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		die();
	}
}

$id = $_REQUEST["id"];

$personal_interno_mobiliario = $database->get("PERSONAL_INTERNO_MOBILIARIO", "*", ["NO_EMPLEADO"=>$id]);
valida_error_medoo_and_die();

$personal_interno_equipos = $database->select("PERSONAL_INTERNO_EQUIPOS", "*", ["NO_EMPLEADO"=>$id]);
valida_error_medoo_and_die();

$resultado["MOBILIARIO"] = $personal_interno_mobiliario;
$resultado["EQUIPOS"] = $personal_interno_equipos;




print_r(json_encode($resultado));



//-------- FIN --------------
?>