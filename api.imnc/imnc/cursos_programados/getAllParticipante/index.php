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

$participantes = $database->select("CURSOS_PROGRAMADOS_PARTICIPANTES",["[><]PARTICIPANTES"=>["ID_PARTICIPANTE"=>"ID"]], ["CURSOS_PROGRAMADOS_PARTICIPANTES.ID_CLIENTE","PARTICIPANTES.ID","NOMBRE","EMAIL","TELEFONO","CURP","PERFIL","ID_ESTADO"] , ["ID_CURSO_PROGRAMADO"=>$id]);
valida_error_medoo_and_die();




//print_r(json_encode($eventos, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
print_r(json_encode($participantes, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
?>
