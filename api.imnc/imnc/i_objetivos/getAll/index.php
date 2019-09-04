<?php 
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}


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

$respuesta=array();

$respuesta = $database->select("OBJETIVOS",
	                            [
	                            	"[><]OBJETIVO_VALORES"=>["ID"=>"OBJETIVOS_ID"],
	                            	"[><]OBJETIVOS_PERIODICIDADES"=>["OBJETIVO_VALORES.OBJETIVOS_PERIODICIDADES_ID"=>"ID"],
								],
	                            [
									"OBJETIVOS.ID",
									"OBJETIVOS.NOMBRE",
									"OBJETIVO_VALORES.VALOR_OBJETIVO",
									"OBJETIVO_VALORES.MES",
									"OBJETIVO_VALORES.ANHIO",
									"OBJETIVOS_PERIODICIDADES.ID(ID_PERIODICIDAD)",
									"OBJETIVOS_PERIODICIDADES.NOMBRE(NOMBRE_PERIODICIDAD)",
	                            ]);
print_r(json_encode($respuesta));


//-------- FIN --------------
?>
