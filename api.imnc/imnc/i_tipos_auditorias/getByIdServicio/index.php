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
		$mailerror->send("I_SG_AUDITORIAS_TIPOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$tipos_auditorias = $database->select(
	"I_SG_AUDITORIAS_TIPOS",
	["[><]ETAPAS_PROCESO"=>["I_SG_AUDITORIAS_TIPOS.ID_ETAPA"=>"ID_ETAPA"]],
										
											["I_SG_AUDITORIAS_TIPOS.ID","I_SG_AUDITORIAS_TIPOS.ACRONIMO","I_SG_AUDITORIAS_TIPOS.TIPO","I_SG_AUDITORIAS_TIPOS.ID_ETAPA"],
											["ETAPAS_PROCESO.ID_SERVICIO"=>$id]); 
valida_error_medoo_and_die(); 
print_r(json_encode($tipos_auditorias)); 
?> 
