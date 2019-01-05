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


$query = "SELECT *,(SELECT U.NOMBRE FROM USUARIOS U WHERE U.ID = CPH.ID_USUARIO) AS NOMBRE_USUARIO FROM CURSOS_PROGRAMADOS_HISTORICO CPH WHERE CPH.MODIFICACION = 'ELIMINO CURSO' ORDER BY CPH.ID DESC";
$historico = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();


print_r(json_encode($historico));
?> 
