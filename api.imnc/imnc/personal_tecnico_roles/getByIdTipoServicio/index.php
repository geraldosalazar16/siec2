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

$rol = $database->select("PERSONALTECNICOROLES_TIPOSERVICIO",
							[
								"[><]PERSONAL_TECNICO_ROLES"=> ["PERSONALTECNICOROLES_TIPOSERVICIO.ID_ROL"=>"ID"]
							], 
							[
								"PERSONAL_TECNICO_ROLES.ID",
								"PERSONAL_TECNICO_ROLES.ACRONIMO",
								"PERSONAL_TECNICO_ROLES.ROL",
								"PERSONALTECNICOROLES_TIPOSERVICIO.DESC_DIAS",
								"PERSONALTECNICOROLES_TIPOSERVICIO.OBLIGATORIO"
							]
							,
									["PERSONALTECNICOROLES_TIPOSERVICIO.ID_TIPO_SERVICIO"=>$id]);
valida_error_medoo_and_die();

print_r(json_encode($rol));


//-------- FIN --------------
?>