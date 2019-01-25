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

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO, "Es necesario introducir un id de curso");

$REFERENCIA = file_get_contents($global_apiserver . '/cursos/getReferencia/?id=3 &tipo=P');

$PERSONAS_MINIMO = 1;

$ETAPA	= 49; //Inscrito

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");


$id_cp = $database->insert("CURSOS_PROGRAMADOS", [
	"ID_CURSO" => $ID_CURSO,
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
        "ESTADO_ACTUAL" => " Referencia: " . $REFERENCIA . ", Mínimo: " . $PERSONAS_MINIMO . ", Etapa: " . $ETAPA . ", Creado desde el cotizador",
        "ID_USUARIO" => $ID_USUARIO_CREACION,
        "FECHA" => date("Ymd"),

    ]);
}
valida_error_medoo_and_die();

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
