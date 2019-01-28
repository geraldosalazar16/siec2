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
include  '../../common/jwt.php';
use \Firebase\JWT\JWT;



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

$ID_COTIZACION = $objeto->ID_COTIZACION;
valida_parametro_and_die($ID_COTIZACION, "Es necesario el ID COTIZACION");

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario el ID CURSO");

$ID_CURSO_PROGRAMADO = $objeto->ID_CURSO_PROGRAMADO;
valida_parametro_and_die($ID_CURSO_PROGRAMADO, "Es necesario el ID CURSO PROGRAMADO");

$ID_CLIENTE= $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Es necesario  el cliente");

$CLIENTE_PROSPECTO = $objeto->CLIENTE_PROSPECTO;
valida_parametro_and_die($CLIENTE_PROSPECTO, "Es necesario indicar si es cliente o prospecto");

$SOLO_PARA_CLIENTE= $objeto->SOLO_PARA_CLIENTE;
valida_parametro_and_die($SOLO_PARA_CLIENTE, "Es necesario saber si es solo para el cliente");

$CANTIDAD_PARTICIPANTES= $objeto->CANTIDAD_PARTICIPANTES;
valida_parametro_and_die($CANTIDAD_PARTICIPANTES, "Es necesario la cantidad de participantes");


$ID_FINAL = 0;
if($CLIENTE_PROSPECTO == 'prospecto'){
    //Si es prospecto buscar el id_cliente asociado
    $id_cliente_asociado = $database->get("PROSPECTO","ID_CLIENTE",["ID"=>$ID_CLIENTE]);
    //Validar que tenga cliente asociado
    if($id_cliente_asociado == 0 || !$id_cliente_asociado){
        $respuesta["resultado"] = "error\n";
        $respuesta["mensaje"] = "El prospecto debe tener un cliente asociado";
        print_r(json_encode($respuesta));
        die();
    } else {
        $ID_FINAL = $id_cliente_asociado;
    }
} else {
    $ID_FINAL = $ID_CLIENTE;
}

$count = $database->count("CLIENTE_CURSOS_PROGRAMADOS",["ID_CLIENTE"],["AND"=>["ID_CLIENTE"=>$ID_FINAL,"ID_CURSO_PROGRAMADO"=>$ID_CURSO_PROGRAMADO]]);

    if($count == 0)
    {
        //payload
        $data = [
            'ID_CLIENTE' => $ID_FINAL,
            'MODALIDAD' => "programado",
            'ID_CURSO' => $ID_CURSO,
            'ID_PROGRAMACION' => $ID_CURSO_PROGRAMADO
        ];

        /*
        iss = issuer, servidor que genera el token
        data = payload del JWT
        */
        $token = array(
            'iss' => $global_apiserver,
            'aud' => $global_apiserver,
            'exp' => time() + $duration,
            'data' => $data
        );

        //Codifica la información usando el $key definido en jwt.php
        $jwt = JWT::encode($token, $key);

        //GUARDAR EL URL SCE_CURSOS
        $url = $insertar_participantes . "?token=" . $jwt;

        $idp = $database->insert("CLIENTE_CURSOS_PROGRAMADOS", [
            "ID_CLIENTE" => $ID_FINAL,
            "ID_CURSO_PROGRAMADO"=>	$ID_CURSO_PROGRAMADO,
            "CANTIDAD_PARTICIPANTES"=>	$CANTIDAD_PARTICIPANTES,
            "SOLO_PARA_CLIENTE" => $SOLO_PARA_CLIENTE,
            "URL_PARTICIPANTES" =>$url
        ]);
        valida_error_medoo_and_die();


            //INSERTA ID CP EN COTIZACION DETALLES
            $idcp = $database->update("COTIZACION_DETALLES", [
                "VALOR" => $ID_CURSO_PROGRAMADO,
            ],["AND"=>["DETALLE"=>"TIENE_SERVICIO","ID_COTIZACION"=>$ID_COTIZACION]]);
            valida_error_medoo_and_die();



    }
    else
        valida_parametro_and_die(null, "Ya ese cliente esta inscrito");



$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
