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

$respuesta=array();

$tipos_servicio = $database->select("TIPOS_SERVICIO",["[><]SERVICIOS"=>["TIPOS_SERVICIO.ID_SERVICIO"=>"ID"],], ["SERVICIOS.ID(ID_SERVICIO)","SERVICIOS.NOMBRE(NOMBRE_SERVICIO)","TIPOS_SERVICIO.ID","TIPOS_SERVICIO.ACRONIMO","TIPOS_SERVICIO.NOMBRE","TIPOS_SERVICIO.ID_REFERENCIA"]);	
valida_error_medoo_and_die();


for($i=0;$i<count($tipos_servicio);$i++){
	$normas_ts	=	$database->select("NORMAS_TIPOSERVICIO","*",["ID_TIPO_SERVICIO"=>$tipos_servicio[$i]['ID']]);	
	$tipos_servicio[$i]["NORMAS"] = $normas_ts;
}

print_r(json_encode($tipos_servicio));


//-------- FIN --------------
?>