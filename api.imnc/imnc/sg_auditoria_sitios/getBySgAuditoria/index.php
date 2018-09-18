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
$SG_AUDITORIA_SITIOS = $database->select("SG_AUDITORIA_SITIOS", "*", ["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA]); 
valida_error_medoo_and_die();
for ($i=0; $i < count($SG_AUDITORIA_SITIOS) ; $i++) { 
	$SG_AUDITORIA_SITIOS[$i]["SG_SITIOS"] = $database->get("SG_SITIOS", "*", ["ID"=>$SG_AUDITORIA_SITIOS[$i]["ID_SG_SITIO"]]); 
	valida_error_medoo_and_die();
	
	$domicilio_nombre = $database->get("CLIENTES_DOMICILIOS", "NOMBRE_DOMICILIO", ["ID"=>$SG_AUDITORIA_SITIOS[$i]["SG_SITIOS"]["ID_CLIENTE_DOMICILIO"]]);
	valida_error_medoo_and_die();
	$SG_AUDITORIA_SITIOS[$i]["SG_SITIOS"]["NOMBRE_DOMICILIO"] = $domicilio_nombre;

	$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "ID_TIPO_SERVICIO", ["ID"=>$SG_AUDITORIA_SITIOS[$i]["SG_SITIOS"]["ID_SG_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die(); 
	$SG_AUDITORIA_SITIOS[$i]["SG_SITIOS"]["CLAVE_TIPO_SERVICIO"] = $tipo_servicio;
}


print_r(json_encode($SG_AUDITORIA_SITIOS)); 
?> 
