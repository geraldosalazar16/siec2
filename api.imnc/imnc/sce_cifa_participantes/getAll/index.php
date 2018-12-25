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

$id = $_REQUEST["id"];
$query = "SELECT * FROM SCE_PARTICIPANTES SCEP INNER JOIN PARTICIPANTES P ON SCEP.ID_PARTICIPANTE = P.ID WHERE SCEP.ID_SCE=".$id;

$participantes = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();



print_r(json_encode($participantes));


//-------- FIN --------------
?>