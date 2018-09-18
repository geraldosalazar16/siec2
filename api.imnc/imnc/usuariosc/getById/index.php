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
		$mailerror->send("USUARIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$id = $_REQUEST["id"]; 
$usuario = $database->get("USUARIOS", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 
$perfil = $database->query("SELECT PMU.ID_MODULO,M.MODULO,PMU.ID_PERFIL FROM PERFIL_MODULO_USUARIO AS PMU,MODULOS AS M WHERE M.ID = PMU.ID_MODULO AND PMU.ID_USUARIO = ".$id)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die(); 

$usuario["PERFIL"] = $perfil;

unset($usuario["PASSWORD"]);

print_r(json_encode($usuario)); 
?> 
