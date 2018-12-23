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
$completo = $_REQUEST["completo"];


$personal_tecnico = $database->get("PERSONAL_TECNICO", "*", ["ID"=>$id]);
valida_error_medoo_and_die();

if (isset($completo)) {
	$domicilios = $database->select("PERSONAL_TECNICO_DOMICILIOS", "*", ["ID_PERSONAL_TECNICO"=>$id]);
	valida_error_medoo_and_die();
	$personal_tecnico["DOMICILIOS"] = $domicilios;

	$califis = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "*", ["ID_PERSONAL_TECNICO"=>$id,"ORDER"=>"ID_TIPO_SERVICIO"]);
	valida_error_medoo_and_die();
	for ($i=0; $i < count($califis) ; $i++) { 
		$datos_tipo_servicio = $database->get("TIPOS_SERVICIO", ["NOMBRE","ACRONIMO","ID_SERVICIO"], ["ID"=>$califis[$i]["ID_TIPO_SERVICIO"]]);
		valida_error_medoo_and_die();
		$califis[$i]["NOMBRE_TIPO_SERVICIO"] = $datos_tipo_servicio["NOMBRE"];
		$califis[$i]["ACRONIMO"] = $datos_tipo_servicio["ACRONIMO"];
		$califis[$i]["ID_SERVICIO"] = $datos_tipo_servicio["ID_SERVICIO"];
		$datos_pt_rol = $database->get("PERSONAL_TECNICO_ROLES", ["ACRONIMO"], ["ID"=>$califis[$i]["ID_ROL"]]);
		valida_error_medoo_and_die();
		$califis[$i]["ACRONIMO_ROL"] = $datos_pt_rol["ACRONIMO"];
		
		$aaa="";
		$normas_cal	=	$database->select("CALIFICACIONES_NORMAS","*",["ID_CALIFICACION"=>$califis[$i]['ID']]);
		if(isset($normas_cal)){
			for($j=0;$j<count($normas_cal);$j++){
				$aaa	.=	$normas_cal[$j]["ID_NORMA"]." ;".PHP_EOL;
			}
		}
	
		$califis[$i]["NORMA_ID"] = $aaa;
		
	}
	$personal_tecnico["CALIFICACIONES"] = $califis;
}


print_r(json_encode($personal_tecnico));



//-------- FIN --------------
?>