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
valida_parametro_and_die($ID, "Es necesario un ID para poder eliminar");

$ID_CURSO= $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO,"Falta ID de CURSO PROGRAMADO");

$id_p = $database->delete("PARTICIPANTES", ["ID"=>$ID]);
valida_error_medoo_and_die();

if($id_p	!=	0) {
    $id_p = $database->delete("CURSOS_PROGRAMADOS_PARTICIPANTES", ["ID_PARTICIPANTE"=>$ID]);
}

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
