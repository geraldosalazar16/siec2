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
		$mailerror->send("SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$ID_SG_AUDITORIA = $_REQUEST["id_sg_auditoria"]; 
$SG_AUDITORIA_GRUPOS = $database->select("SG_AUDITORIA_GRUPOS", "*", ["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA]); 
valida_error_medoo_and_die();
for ($i=0; $i < count($SG_AUDITORIA_GRUPOS) ; $i++) { 
	$SG_AUDITORIA_GRUPOS[$i]["PERSONAL_TECNICO_CALIFICACIONES"] = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "*", ["ID"=>$SG_AUDITORIA_GRUPOS[$i]["ID_PERSONAL_TECNICO_CALIF"]]); 
	valida_error_medoo_and_die();

	$SG_AUDITORIA_GRUPOS[$i]["PERSONAL_TECNICO"] = $database->get("PERSONAL_TECNICO", "*", ["ID"=>$SG_AUDITORIA_GRUPOS[$i]["PERSONAL_TECNICO_CALIFICACIONES"]["ID_PERSONAL_TECNICO"]]); 
	valida_error_medoo_and_die();
	unset($SG_AUDITORIA_GRUPOS[$i]["PERSONAL_TECNICO"]["IMAGEN_BASE64"]);

	$SG_AUDITORIA_GRUPOS[$i]["FECHAS_ASIGNADAS"] = $database->select("SG_AUDITORIA_GRUPO_FECHAS", "FECHA", ["ID_SG_AUDITORIA_GRUPO"=>$SG_AUDITORIA_GRUPOS[$i]["ID"]]); 
	valida_error_medoo_and_die();
}


print_r(json_encode($SG_AUDITORIA_GRUPOS)); 
?> 
