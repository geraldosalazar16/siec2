<?php
/**
 * Created by PhpStorm.
 * User: bmayor
 * Date: 30/05/2019
 * Time: 14:56
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
valida_parametro_and_die($NOMBRE, "Es necesario introducir un nombre");

$DESCRIPCION = $objeto->DESCRIPCION;

$SECCION = $objeto->SECCION;
valida_parametro_and_die($SECCION, "Es necesario introducir un seccion");

$ETAPA = $objeto->ETAPA;
valida_parametro_and_die($ETAPA, "Es necesario introducir un etapa");
$SERVICIO = $objeto->SERVICIO;
valida_parametro_and_die($SERVICIO, "Es necesario seleccionar el servicio");
$TIPO_SERVICIO = $objeto->TIPO_SERVICIO;
valida_parametro_and_die($TIPO_SERVICIO, "Es necesario seleccionar el tipo de servicio");



$database->insert("CATALOGO_DOCUMENTOS", [
    "NOMBRE" => strtoupper($NOMBRE),
    "DESCRIPCION"=>	strtoupper($DESCRIPCION),
    "ID_SECCION" => $SECCION,
    "ID_ETAPA" => $ETAPA,
	"ID_TIPO_SERVICIO" => $TIPO_SERVICIO
]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
