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

$eventos = $database->select("CURSOS_PROGRAMADOS", "*" , ["ORDER"=>"FECHAS"]);
valida_error_medoo_and_die();

for ($i=0; $i < count($eventos) ; $i++) {
        $fechas = $eventos[$i]["FECHAS"];
        $array = explode("-",$fechas);
        /// separo las fechas
        $eventos[$i]["FECHA_INICIO"] = $array[0];
        $eventos[$i]["FECHA_FIN"] = $array[1];

        ///resto las fechas para saber la cantidad de dias
        $fi = DateTime::createFromFormat('d/m/Y', $array[0]);
        $ff = DateTime::createFromFormat('d/m/Y', $array[1]);
        $intervalo = date_diff($fi,$ff);
        $out = $intervalo->format("%d");
        $eventos[$i]["DIAS"] = $out+1;

		$curso = $database->get("CURSOS", "NOMBRE", ["ID_CURSO"=>$eventos[$i]["ID_CURSO"]]);
		valida_error_medoo_and_die();
    $eventos[$i]["NOMBRE_CURSO"] = $curso;

	$auditor= $database->get("PERSONAL_TECNICO", ["NOMBRE","APELLIDO_PATERNO","APELLIDO_MATERNO"], ["ID"=>$eventos[$i]["ID_INSTRUCTOR"]]);
	valida_error_medoo_and_die();
    $eventos[$i]["NOMBRE_AUDITOR"] = $auditor["NOMBRE"]." ".$auditor["APELLIDO_PATERNO"]." ".$auditor["APELLIDO_MATERNO"];

    $canttidad_perticipantes = $database->count("CURSOS_PROGRAMADOS_PARTICIPANTES",["ID_CURSO_PROGRAMADO"],["ID_CURSO_PROGRAMADO"=>$eventos[$i]["ID"]]);
    valida_error_medoo_and_die();

    $eventos[$i]["CANTIDAD_PARTICIPANTES_REAL"] = $canttidad_perticipantes;
}

print_r(json_encode($eventos));


//-------- FIN --------------
?>