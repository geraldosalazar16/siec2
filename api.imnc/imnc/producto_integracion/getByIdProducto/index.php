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
$id = $_REQUEST["id"];
$query = "SELECT 
PI.ID_PRODUCTO,
IP.PREGUNTA,
IPR.VALOR,
PI.ID_PREGUNTA,
PI.RESPUESTA
FROM PRODUCTO_INTEGRACION PI
INNER JOIN INTEGRACION_PREGUNTAS IP
ON IP.ID = PI.ID_PREGUNTA
INNER JOIN INTEGRACION_PREGUNTAS_RESPUESTAS IPR
ON IPR.ID_PREGUNTA = PI.ID_PREGUNTA
AND IPR.RESPUESTA = PI.RESPUESTA
WHERE PI.ID_PRODUCTO = ".$id; 
$producto_integracion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die(); 
print_r(json_encode($producto_integracion)); 
?> 
