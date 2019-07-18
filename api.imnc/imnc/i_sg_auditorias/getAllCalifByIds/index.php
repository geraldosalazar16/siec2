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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$ids = $_REQUEST["ids"];
$ids = json_decode($ids);

$datos = $database->select("PERSONAL_TECNICO_CALIFICACIONES",
	["[><]PERSONAL_TECNICO_ROLES"=>["ID_ROL"=>"ID"]],
	["PERSONAL_TECNICO_CALIFICACIONES.ID","PERSONAL_TECNICO_CALIFICACIONES.REGISTRO","PERSONAL_TECNICO_ROLES.ID(ID_ROL)","PERSONAL_TECNICO_ROLES.ROL"]
	, ["PERSONAL_TECNICO_CALIFICACIONES.ID" => $ids] );





print_r(json_encode($datos));
?> 
