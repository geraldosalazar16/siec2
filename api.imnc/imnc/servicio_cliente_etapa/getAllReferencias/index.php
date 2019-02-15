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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta));
		die(); 
	} 
} 
$respuesta=array(); 
$servicio_cliente_etapa = $database->select("SERVICIO_CLIENTE_ETAPA", "REFERENCIA",[
	"ORDER" => "REFERENCIA"
]); 
valida_error_medoo_and_die(); 
print_r(json_encode($servicio_cliente_etapa)); 
?> 
