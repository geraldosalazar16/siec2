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

$respuesta = $database->get("TIPOS_SERVICIO",["[><]SERVICIOS"=>["TIPOS_SERVICIO.ID_SERVICIO"=>"ID"]], ["SERVICIOS.NOMBRE(NOMBRE_SERVICIO)","SERVICIOS.ID(ID_SERVICIO)","TIPOS_SERVICIO.ID","TIPOS_SERVICIO.ACRONIMO","TIPOS_SERVICIO.NOMBRE","TIPOS_SERVICIO.ID_REFERENCIA"], ["TIPOS_SERVICIO.ID"=>$id]);
valida_error_medoo_and_die();

print_r(json_encode($respuesta));


//-------- FIN --------------
?>