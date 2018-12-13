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

$respuesta=array();
$id = $_REQUEST["idCalif"];
valida_parametro_and_die($id,"Falta parametro idCalif");

$personal_tecnico_calif_curso = $database->select("PERSONAL_TECNICO_CALIF_CURSOS", "*", ["ID_PERSONAL_TECNICO_CALIFICACION" => $id]);
valida_error_medoo_and_die();
for ($i=0; $i < count($personal_tecnico_calif_curso) ; $i++) {
	$curso = $database->get("CURSOS", "*", ["ID_CURSO" => $personal_tecnico_calif_curso[$i]["ID_CURSO"]]);
	valida_error_medoo_and_die();
    $personal_tecnico_calif_curso[$i]["NOMBRE_CURSO"] = $curso["NOMBRE"];
    //$personal_tecnico_calif_curso[$i]["NOMBRE_SECTOR_TRUNCADO"] = substr($sector["NOMBRE"], 0, 40);
}

print_r(json_encode($personal_tecnico_calif_curso));


//-------- FIN --------------
?>