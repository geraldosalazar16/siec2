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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$id = $_REQUEST["id"];
$completo = $_REQUEST["completo"];


$personal_tecnico = $database->get("PERSONAL_TECNICO", "*", ["ID"=>$id]);
valida_error_medoo_and_die();

if (isset($completo)) {
	$domicilios = $database->select("PERSONAL_TECNICO_DOMICILIOS", "*", ["ID_PERSONAL_TECNICO"=>$id]);
	valida_error_medoo_and_die();
	$personal_tecnico["DOMICILIOS"] = $domicilios;

	$califis = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "*", ["ID_PERSONAL_TECNICO"=>$id]);
	valida_error_medoo_and_die();
	for ($i=0; $i < count($califis) ; $i++) { 
		$nombre_tipo_servicio = $database->get("TIPOS_SERVICIO", "NOMBRE", ["ID"=>$califis[$i]["ID_TIPO_SERVICIO"]]);
		valida_error_medoo_and_die();
		$califis[$i]["NOMBRE_TIPO_SERVICIO"] = $nombre_tipo_servicio;
	}
	$personal_tecnico["CALIFICACIONES"] = $califis;
}


print_r(json_encode($personal_tecnico));



//-------- FIN --------------
?>