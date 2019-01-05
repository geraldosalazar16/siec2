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

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$ID_SCE = $objeto->ID_SERVICIO_CLIENTE_ETAPA;
valida_parametro_and_die($ID_SCE, "Es necesario introducir el ID SCE");

$participante = $database->get("PARTICIPANTES","*",["ID"=>$ID]);

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

if($id_participante !=0)
{
    $estado_anterior = "Razón Social: ".$participante["RAZON_ENTIDAD"].", Correo:".$participante["EMAIL"].", Teléfono: ".$participante["TELEFONO"].", CURP: ".$participante["CURP"].", RFC: ".$participante["RFC"].", Estado:".$participante["ESTADO"].", le atendió:".$participante["EJECUTIVO"];
    $estado_actual = "Razón Social: ".$RAZON_ENTIDAD.", Correo:".$EMAIL.", Teléfono: ".$TELEFONO.", CURP: ".$CURP.", RFC: ".$RFC.", Estado:".$ESTADO.", le atendió:".$EJECUTIVO;
    $id2=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
        "ID_SERVICIO_CONTRATADO" => $ID_SCE,
        "MODIFICACION" => "MODIFICANDO PARTICIPANTE",
        "ESTADO_ANTERIOR"=>	$estado_anterior,
        "ESTADO_ACTUAL"=>$estado_actual,
        "USUARIO" => $ID_USUARIO_MODIFICACION,
        "FECHA_USUARIO" => date("Ymd"),
        "FECHA_MODIFICACION" => date("Ymd")]);
}

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
?> 
