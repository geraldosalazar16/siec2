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
$id = $_REQUEST["idCalif"];
valida_parametro_and_die($id,"Falta parametro idCalif");

$personal_tecnico_calif_sector = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "*", ["ID_PERSONAL_TECNICO_CALIFICACION" => $id]);
valida_error_medoo_and_die();
for ($i=0; $i < count($personal_tecnico_calif_sector) ; $i++) { 
	$sector = $database->get("SECTORES", "*", ["ID_SECTOR" => $personal_tecnico_calif_sector[$i]["ID_SECTOR"]]);
	valida_error_medoo_and_die();
	$personal_tecnico_calif_sector[$i]["CLAVE_COMPUESTA"] = $sector["ID"] . "-" . $sector["ID_TIPO_SERVICIO"] . "-" .  $sector["ANHIO"];
	$personal_tecnico_calif_sector[$i]["NOMBRE_SECTOR"] = $sector["NOMBRE"];
	$personal_tecnico_calif_sector[$i]["NOMBRE_SECTOR_TRUNCADO"] = substr($sector["NOMBRE"], 0, 40);
}

print_r(json_encode($personal_tecnico_calif_sector));


//-------- FIN --------------
?>