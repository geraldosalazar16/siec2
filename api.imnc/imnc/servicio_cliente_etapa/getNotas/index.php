<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 11/01/2019
 * Time: 18:13
 */

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
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$ID_SCE = $objeto->ID_SCE;
$ID_TA = $objeto->ID_TA;
$CICLO = $objeto->CICLO;
$SERVICIO = $objeto->SERVICIO;


$notificacion = $database->get("AUDITORIAS_NOTIFICACION",["ID","DOMICILIO"],["AND"=>["ID_SCE"=>$ID_SCE,"ID_TA"=>$ID_TA,"CICLO"=>$CICLO,"SERVICIO"=>$SERVICIO]] );
$notas = $database->select("AUDITORIAS_NOTIFICACION_NOTAS",["NOTA"],["ID_AUDITORIAS_NOTIFICACION"=>$notificacion["ID"]]);
$notificacion["NOTAS"] = $notas;
valida_error_medoo_and_die();

print_r(json_encode($notificacion));