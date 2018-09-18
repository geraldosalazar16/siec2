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
		$mailerror->send("PERSONAL_TECNICO_GRUPOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$id_sg_tipo_servicio = $_REQUEST["id_sg_tipo_servicio"]; 
$personal_tecnico_grupos = $database->select("PERSONAL_TECNICO_GRUPOS", "*", ["ID_SG_TIPO_SERVICIO"=>$id_sg_tipo_servicio]); 
valida_error_medoo_and_die(); 
for ($i=0; $i < count($personal_tecnico_grupos) ; $i++) { 
	$personal_tecnico = $database->get("PERSONAL_TECNICO", "*", ["ID"=>$personal_tecnico_grupos[$i]["ID_PERSONAL_TECNICO"]]);
	valida_error_medoo_and_die(); 
	$personal_tecnico_grupos[$i]["INICIALES"] = $personal_tecnico["INICIALES"];
	$personal_tecnico_grupos[$i]["NOMBRE_COMPLETO"] = $personal_tecnico["NOMBRE"] . " " . $personal_tecnico["APELLIDO_PATERNO"] . " " . $personal_tecnico["APELLIDO_MATERNO"];
}

print_r(json_encode($personal_tecnico_grupos)); 
?> 
