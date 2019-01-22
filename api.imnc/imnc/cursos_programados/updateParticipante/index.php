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

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario introducir el nombre");

$EMAIL= $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario introducir el correo electronico");

$TELEFONO= $objeto->TELEFONO;
valida_parametro_and_die($TELEFONO, "Es necesario introducir el telefono");

$CURP= $objeto->CURP;
valida_parametro_and_die($CURP, "Es necesario introducir el curp");

$PERFIL= $objeto->PERFIL;
valida_parametro_and_die($PERFIL, "Es necesario introducir el perfil");

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesario el id del curso");

$ESTADO= $objeto->ESTADO;
valida_parametro_and_die($ESTADO, "Es necesario introducir el estado");

$ID_CURSO= $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario el id curso");

$ID_CLIENTE= $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Es necesario  el cliente");


$id = $database->update("PARTICIPANTES", [
    "NOMBRE" => $NOMBRE,
    "EMAIL"=>	$EMAIL,
    "TELEFONO"=>	$TELEFONO,
    "CURP" => $CURP,
    "PERFIL" => $PERFIL,
    "ID_ESTADO" => $ESTADO
],["ID"=>$ID]);
valida_error_medoo_and_die();

$id_p = $database->update("CURSOS_PROGRAMADOS_PARTICIPANTES", [
    "ID_CLIENTE"=>$ID_CLIENTE
],["AND"=>["ID_CURSO_PROGRAMADO" => $ID_CURSO,"ID_PARTICIPANTE"=>	$ID]]);
valida_error_medoo_and_die();



$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
