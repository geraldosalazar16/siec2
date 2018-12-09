<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
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
$id = $_REQUEST["id"]; 

$eventos = $database->get("CURSOS_PROGRAMADOS", "*" , ["ID"=>$id]);
valida_error_medoo_and_die();


    $fechas = $eventos["FECHAS"];
    $array = explode("-",$fechas);
    /// separo las fechas
    $eventos["FECHA_INICIO"] = $array[0];
    $eventos["FECHA_FIN"] = $array[1];


    ///resto las fechas para saber la cantidad de dias
    $fi = DateTime::createFromFormat('d/m/Y', $array[0]);
    $ff = DateTime::createFromFormat('d/m/Y', $array[1]);
    $intervalo = date_diff($fi,$ff);
    $out = $intervalo->format("%d");
    $eventos["DIAS"] = $out;

    $curso = $database->get("CURSOS", "NOMBRE", ["ID_CURSO"=>$eventos["ID_CURSO"]]);
    valida_error_medoo_and_die();
    $eventos["NOMBRE_CURSO"] = $curso;

    $auditor= $database->get("PERSONAL_TECNICO", ["NOMBRE","APELLIDO_MATERNO","APELLIDO_PATERNO"], ["ID"=>$eventos["ID_INSTRUCTOR"]]);
    valida_error_medoo_and_die();
    $eventos["NOMBRE_AUDITOR"] = $auditor;



print_r(json_encode($eventos, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
?> 