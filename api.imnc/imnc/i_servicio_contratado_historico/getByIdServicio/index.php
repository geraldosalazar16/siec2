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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$servicio_cliente_etapa_historico = $database->select("SERVICIO_CLIENTE_ETAPA_HISTORICO",
														[
															"[><]USUARIOS"=>["SERVICIO_CLIENTE_ETAPA_HISTORICO.USUARIO"=>"ID"],
															
														],
														[
															"SERVICIO_CLIENTE_ETAPA_HISTORICO.ID_SERVICIO_CONTRATADO",
															"SERVICIO_CLIENTE_ETAPA_HISTORICO.MODIFICACION",
															"SERVICIO_CLIENTE_ETAPA_HISTORICO.ESTADO_ANTERIOR",
															"SERVICIO_CLIENTE_ETAPA_HISTORICO.ESTADO_ACTUAL",
															"SERVICIO_CLIENTE_ETAPA_HISTORICO.FECHA_USUARIO",
															"USUARIOS.NOMBRE(NOMBRE_USUARIO)"
														], ["ID_SERVICIO_CONTRATADO"=>$id]); 
valida_error_medoo_and_die(); 
print_r(json_encode($servicio_cliente_etapa_historico)); 
?> 
