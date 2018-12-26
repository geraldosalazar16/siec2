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
$ID_SCE = $_REQUEST["ID_SCE"];
$ID_CURSO = $_REQUEST["ID_CURSO"];

$participante = $database->get("SCE_CURSOS", "*" , ["AND"=>["ID_SCE"=>$ID_SCE,"ID_CURSO"=>$ID_CURSO]]);
valida_error_medoo_and_die();
if($participante["ID_SITIO"])
{
    $participante["FECHA_INICIO"] =  substr($participante["FECHA_INICIO"] ,6,8)."/".substr($participante["FECHA_INICIO"] ,-4,2)."/".substr($participante["FECHA_INICIO"] ,0,4);
    $participante["FECHA_FIN"] =  substr($participante["FECHA_FIN"] ,6,8)."/".substr($participante["FECHA_FIN"] ,-4,2)."/".substr($participante["FECHA_FIN"] ,0,4);
    $instructor = $database->get("PERSONAL_TECNICO",["NOMBRE","APELLIDO_PATERNO","APELLIDO_MATERNO"]  , ["ID"=>$participante["ID_INSTRUCTOR"]]);
    valida_error_medoo_and_die();
    $participante["NOMBRE_INSTRUCTOR"] = $instructor["NOMBRE"]." ".$instructor["APELLIDO_PATERNO"]." ".$instructor["APELLIDO_MATERNO"];
    $sitios = $database->get("CLIENTES_DOMICILIOS", ["NOMBRE_DOMICILIO","CALLE"] , ["ID"=>$participante["ID_SITIO"]]);
    valida_error_medoo_and_die();
    $participante["NOMBRE_SITIO"] = trim($sitios["NOMBRE_DOMICILIO"])." CALLE:".trim($sitios["CALLE"]);
}





print_r(json_encode($participante, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
?> 
