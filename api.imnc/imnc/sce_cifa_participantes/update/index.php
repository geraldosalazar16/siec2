<?php
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

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Es necesario introducir el ID Participante");

$RAZON_ENTIDAD = $objeto->RAZON_ENTIDAD;
valida_parametro_and_die($RAZON_ENTIDAD, "Es necesario introducir una Razon Social");

$EMAIL = $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario introducir un Correo Electrónico");

$TELEFONO	= $objeto->TELEFONO;
valida_parametro_and_die($TELEFONO, "Es necesario introducir un número de telefono");

$CURP	= $objeto->CURP;
valida_parametro_and_die($CURP, "Es necesario introducir el CURP del participante");

$RFC	= $objeto->RFC;
valida_parametro_and_die($RFC, "Es necesario introducir el RFC de su organización");

$ESTADO	= $objeto->ESTADO;
valida_parametro_and_die($ESTADO, "Es necesario introducir el Estado del que nos visita");

$EJECUTIVO	= $objeto->EJECUTIVO;
valida_parametro_and_die($EJECUTIVO, "Es necesario introducir el Nombre del ejecutivo comercial que le atendió");


$id_participante = $database->update("PARTICIPANTES", [
    "RAZON_ENTIDAD" => $RAZON_ENTIDAD,
    "EMAIL"=>	$EMAIL,
    "TELEFONO" => $TELEFONO,
    "CURP" => $CURP,
    "RFC"=>$RFC,
    "ID_ESTADO"=>$ESTADO,
    "EJECUTIVO"=>$EJECUTIVO
],["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
?> 
