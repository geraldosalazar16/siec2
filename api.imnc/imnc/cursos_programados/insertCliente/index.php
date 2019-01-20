<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 15/01/2019
 * Time: 12:13
 */

include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';



function valida_parametro_and_die($parametro, $mensaje_error){
    $parametro = "" . $parametro;
    if ($parametro == "" or is_null($parametro)) {
        $respuesta["resultado"] = "error";
        $respuesta["mensaje"] = $mensaje_error;
        print_r(json_encode($respuesta));
        die();
    }
}

function valida_error_medoo_and_die(){
    global $database, $mailerror;
    if ($database->error()[2]) {
        $respuesta["resultado"]="error";
        $respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2];
        print_r(json_encode($respuesta));
        die();
    }
}

$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario el ID CURSO");

$ID_CLIENTE= $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Es necesario  el cliente");

$SOLO_PARA_CLIENTE= $objeto->SOLO_PARA_CLIENTE;
valida_parametro_and_die($SOLO_PARA_CLIENTE, "Es necesario saber si es solo para el cliente");

$CANTIDAD_PARTICIPANTES= $objeto->CANTIDAD_PARTICIPANTES;
valida_parametro_and_die($CANTIDAD_PARTICIPANTES, "Es necesario la cantidad de participantes");

$count = $database->count("CLIENTE_CURSOS_PROGRAMADOS",["ID_CLIENTE"],["AND"=>["ID_CLIENTE"=>$ID_CLIENTE,"ID_CURSO_PROGRAMADO"=>$ID_CURSO]]);

    if($count == 0)
    {
        $idp = $database->insert("CLIENTE_CURSOS_PROGRAMADOS", [
            "ID_CLIENTE" => $ID_CLIENTE,
            "ID_CURSO_PROGRAMADO"=>	$ID_CURSO,
            "CANTIDAD_PARTICIPANTES"=>	$CANTIDAD_PARTICIPANTES,
            "SOLO_PARA_CLIENTE" => $SOLO_PARA_CLIENTE
        ]);
        valida_error_medoo_and_die();
    }
    else
        valida_parametro_and_die(null, "Ya ese cliente esta inscrito");



$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
