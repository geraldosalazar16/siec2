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

$query = "SELECT DISTINCT PT.ID,PT.NOMBRE,PT.APELLIDO_PATERNO,PT.APELLIDO_MATERNO,PT.STATUS FROM PERSONAL_TECNICO_CALIF_CURSOS PTCC INNER JOIN PERSONAL_TECNICO_CALIFICACIONES PTC ON PTCC.ID_PERSONAL_TECNICO_CALIFICACION = PTC.ID INNER JOIN PERSONAL_TECNICO PT 
ON PTC.ID_PERSONAL_TECNICO = PT.ID
WHERE PTCC.ID_CURSO = ".$id." AND PTC.ID_ROL = 7 ";

$instructores = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);


valida_error_medoo_and_die();

print_r(json_encode($instructores));
?> 
