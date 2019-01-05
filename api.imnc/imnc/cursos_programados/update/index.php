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

$ID= $objeto->ID;
valida_parametro_and_die($ID, "Es necesatio el ID del evento");

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario introducir un nombre de curso");

$NOMBRE_CURSO = $objeto->NOMBRE_CURSO;

$FECHAS = $objeto->FECHAS;
valida_parametro_and_die($FECHAS, "Es necesario seleccionar las fechas");

$ID_INSTRUCTOR	= $objeto->ID_INSTRUCTOR;
valida_parametro_and_die($ID_INSTRUCTOR, "Es necesario seleccionar un instructor");

$NOMBRE_INSTRUCTOR	= $objeto->NOMBRE_INSTRUCTOR;

$PERSONAS_MINIMO	= $objeto->PERSONAS_MINIMO;
valida_parametro_and_die($PERSONAS_MINIMO, "Es introducir un número mínimo de personas");

$ETAPA	= $objeto->ETAPA;
valida_parametro_and_die($ETAPA, "Es introducir una etapa");


$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$query = "SELECT *,(SELECT C.NOMBRE FROM CURSOS C WHERE C.ID_CURSO = CP.ID_CURSO) AS NOMBRE_CURSO,(SELECT CONCAT(PT.NOMBRE,' ',PT.APELLIDO_PATERNO,' ',PT.APELLIDO_MATERNO)  FROM PERSONAL_TECNICO PT WHERE PT.ID = CP.ID_INSTRUCTOR) AS NOMBRE_INSTRUCTOR  FROM CURSOS_PROGRAMADOS CP WHERE ID =".$ID;
$anterior = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

$id_cp = $database->update("CURSOS_PROGRAMADOS", [
    "ID_CURSO" => $ID_CURSO,
    "FECHAS"=>	$FECHAS,
    "ID_INSTRUCTOR" => $ID_INSTRUCTOR,
    "PERSONAS_MINIMO" => $PERSONAS_MINIMO,
    "ETAPA"=>$ETAPA
],["ID"=>$ID]);
valida_error_medoo_and_die();


if($id_cp	!=	0) {
    $etapa_antes = $database->get("ETAPAS_PROCESO","ETAPA",["ID_ETAPA"=>$anterior[0]["ETAPA"]]);
    $etapa_despues = $database->get("ETAPAS_PROCESO","ETAPA",["ID_ETAPA"=>$ETAPA]);

    $estado_anterior =  " Referencia: " . $anterior[0]["REFERENCIA"] . ", Curso: " . $anterior[0]["NOMBRE_CURSO"] . ", Fecha: " . $anterior[0]["FECHAS"] . ", Instructor: " . strtoupper($anterior[0]["NOMBRE_INSTRUCTOR"]) . ", Mínimo: " . $anterior[0]["PERSONAS_MINIMO"] . ", Etapa: " . $etapa_antes;
    $estado_actual = " Referencia: " . $anterior[0]["REFERENCIA"] . ", Curso: " . $NOMBRE_CURSO . ", Fecha: " . $FECHAS . ", Instructor: " . strtoupper($NOMBRE_INSTRUCTOR) . ", Mínimo: " . $PERSONAS_MINIMO . ", Etapa: " . $etapa_despues;
        $id1 = $database->insert("CURSOS_PROGRAMADOS_HISTORICO", [
            "ID_CURSO_PROGRAMADO" => $ID,
            "MODIFICACION" => "MODIFICANDO CURSO",
            "ESTADO_ANTERIOR" => $estado_anterior,
            "ESTADO_ACTUAL" =>  $estado_actual,
            "ID_USUARIO" => $ID_USUARIO_CREACION,
            "FECHA" => date("Ymd"),

        ]);

}

$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
?> 
