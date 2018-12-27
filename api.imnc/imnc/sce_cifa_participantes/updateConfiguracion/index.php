<?php
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include '../../common/common_functions.php';



$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$ID_SCE = $objeto->ID_SCE;
valida_parametro_and_die($ID_SCE, "Es necesario introducir el ID SCE");

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario introducir el ID CURSO");

$ID_SITIO = $objeto->ID_SITIO;
valida_parametro_and_die($ID_SITIO, "Es necesario introducir el ID SITIO");

$FECHA_INICIO = $objeto->FECHA_INICIO;
valida_parametro_and_die($FECHA_INICIO, "Es necesario introducir la Fecha de Inicio");

$FECHA_INICIO = explode("/",$FECHA_INICIO);
$FECHA_INICIO = date("Ymd", strtotime($FECHA_INICIO[2].$FECHA_INICIO[1].$FECHA_INICIO[0]));

$FECHA_FIN	= $objeto->FECHA_FIN;
valida_parametro_and_die($FECHA_FIN, "Es necesario introducir la Fecha de Fin");

$FECHA_FIN = explode("/",$FECHA_FIN);
$FECHA_FIN = date("Ymd", strtotime($FECHA_FIN[2].$FECHA_FIN[1].$FECHA_FIN[0]));

$ID_INSTRUCTOR	= $objeto->ID_INSTRUCTOR;
valida_parametro_and_die($ID_INSTRUCTOR, "Es necesario introducir el ID Instructor");



$id_participante = $database->update("SCE_CURSOS", [
            "ID_SITIO"=> $ID_SITIO,
            "FECHA_INICIO"=> $FECHA_INICIO,
            "FECHA_FIN" => $FECHA_FIN,
            "ID_INSTRUCTOR" => $ID_INSTRUCTOR
],["AND"=>["ID_SCE"=>$ID_SCE,"ID_CURSO"=>$ID_CURSO]]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
?> 
