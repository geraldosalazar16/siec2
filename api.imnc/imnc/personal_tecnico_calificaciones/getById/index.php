<?php 
include  '../../common/conn-apiserver.php'; 

include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 




function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		die();
	}
}

$id = $_REQUEST["id"];

$query = "SELECT 
PERSONAL_TECNICO_CALIFICACIONES.ID as ID,
PERSONAL_TECNICO_CALIFICACIONES.ID_TIPO_SERVICIO as ID_TIPO_SERVICIO,
PERSONAL_TECNICO_CALIFICACIONES.ID_ROL as ID_ROL,
PERSONAL_TECNICO_CALIFICACIONES.FECHA_INICIO as FECHA_INICIO,
PERSONAL_TECNICO_CALIFICACIONES.FECHA_FIN as FECHA_FIN,
PERSONAL_TECNICO_CALIFICACIONES.REGISTRO as REGISTRO,
TIPOS_SERVICIO.ID_SERVICIO as ID_SERVICIO
FROM PERSONAL_TECNICO_CALIFICACIONES LEFT JOIN TIPOS_SERVICIO ON PERSONAL_TECNICO_CALIFICACIONES.ID_TIPO_SERVICIO = TIPOS_SERVICIO.ID
WHERE PERSONAL_TECNICO_CALIFICACIONES.ID = ".$id ."
LIMIT 1";
$personal_tecnico_calificaciones = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);


valida_error_medoo_and_die();

print_r(json_encode($personal_tecnico_calificaciones[0]));


//-------- FIN --------------
?>