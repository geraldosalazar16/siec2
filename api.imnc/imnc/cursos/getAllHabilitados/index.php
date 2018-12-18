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

$respuesta = array();

$cursos = $database->select("CURSOS", "*" , ["ISACTIVO"=>"1","ORDER"=>"NOMBRE"]);
valida_error_medoo_and_die();

for ($i=0; $i < count($cursos) ; $i++) { 
	if (!is_null(cursos[$i]["ID_NORMA"])) {
		$norma = $database->get("NORMAS", "NOMBRE", ["ID"=>$cursos[$i]["ID_NORMA"]]);
		valida_error_medoo_and_die();

		$cursos[$i]["NOMBRE_NORMA"] = $norma;
	}
	$tipo= $database->get("TIPOS_SERVICIO", "NOMBRE", ["ID"=>$cursos[$i]["ID_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die();
	$cursos[$i]["NOMBRE_TIPO_SEVICIO"] = $tipo;
	if($cursos[$i]["ISACTIVO"])
    {
        $cursos[$i]["ISACTIVO"] = "habilitado";
    }
    else
    {
        $cursos[$i]["ISACTIVO"] = "deshabilitado";
    }
}

print_r(json_encode($cursos));


//-------- FIN --------------
?>