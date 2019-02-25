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

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario introducir una referencia");

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario introducir un id de curso");

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


$id_cp = $database->insert("CURSOS_PROGRAMADOS", [
	"ID_CURSO" => $ID_CURSO,
	"FECHAS"=>	$FECHAS,
	"ID_INSTRUCTOR" => $ID_INSTRUCTOR,
	"PERSONAS_MINIMO" => $PERSONAS_MINIMO,
	"REFERENCIA"=>$REFERENCIA,
    "ETAPA"=>$ETAPA
]); 
valida_error_medoo_and_die();

if($id_cp	!=	0) {
	$etapa = $database->get("ETAPAS_PROCESO","ETAPA",["ID_ETAPA"=>$ETAPA]);
	$ETAPA = $etapa["ETAPA"];
    $id1 = $database->insert("CURSOS_PROGRAMADOS_HISTORICO", [
        "ID_CURSO_PROGRAMADO" => $id_cp,
        "MODIFICACION" => "NUEVO CURSO",
        "ESTADO_ANTERIOR" => "",
        "ESTADO_ACTUAL" => " Referencia: " . $REFERENCIA . ", Curso: " . $NOMBRE_CURSO . ", Fecha: " . $FECHAS . ", Instructor: " . strtoupper($NOMBRE_INSTRUCTOR) . ", Mínimo: " . $PERSONAS_MINIMO . ", Etapa: " . $ETAPA,
        "ID_USUARIO" => $ID_USUARIO_CREACION,
        "FECHA" => date("Ymd"),

    ]);
}
valida_error_medoo_and_die();

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
