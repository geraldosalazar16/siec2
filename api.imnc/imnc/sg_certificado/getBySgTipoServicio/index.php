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
		$mailerror->send("SG_CERTIFICADO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$id_sg_tipo_servicio = $_REQUEST["id_sg_tipo_servicio"]; 
$sg_certificado = $database->get("SG_CERTIFICADO", "*", ["ID_SG_TIPOS_SERVICIO"=>$id_sg_tipo_servicio]); 

if(!file_exists("../../".$sg_certificado["RUTA_ARCHIVO"])) {
    $sg_certificado["EXISTE_ARCHIVO"] = 'no';
}
else {
    $sg_certificado["EXISTE_ARCHIVO"] = 'si';
}

valida_error_medoo_and_die(); 
print_r(json_encode($sg_certificado)); 
?> 
