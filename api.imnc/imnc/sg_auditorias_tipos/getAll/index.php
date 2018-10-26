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
$query = "SELECT 
SGAT.ID AS ID,
SGAT.ACRONIMO AS ACRONIMO_AUDITORIA,
SGAT.TIPO AS TIPO_AUDITORIA,
S.NOMBRE AS NOMBRE_SERVICIO,
S.ID AS ID_SERVICIO
FROM SG_AUDITORIAS_TIPOS SGAT
INNER JOIN SERVICIOS S
ON S.ID = SGAT.ID_SERVICIO"; 
$tipos_auditoria = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die(); 
print_r(json_encode($tipos_auditoria)); 
?> 
