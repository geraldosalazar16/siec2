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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();

$ID_PROSPECTO = $_REQUEST["id_prospecto"];
$consulta = "SELECT PT.ID as id_tarea,P.NOMBRE as nombre_prospecto,PT.FECHA_INICIO as fecha_inicio,PT.HORA_INICIO as hora_inicio,PT.FECHA_FIN as fecha_fin, PT.HORA_FIN as hora_fin,
TA.ID as tipo_asunto,TA.DESCRIPCION AS desc_asunto,P.ID_USUARIO_PRINCIPAL as usuario,PT.DESCRIPCION as desc_tarea, PT.ESTADO as estado_tarea
FROM PROSPECTO_TAREAS PT
INNER JOIN PROSPECTO P ON PT.ID_PROSPECTO = P.ID
INNER JOIN TIPO_ASUNTO TA 
ON TA.ID = PT.ID_TIPO_ASUNTO WHERE P.ID = ".$ID_PROSPECTO." ORDER BY PT.FECHA_INICIO ASC";
$tareas = $database->query($consulta)->fetchAll();
valida_error_medoo_and_die();

print_r(json_encode($tareas));


//-------- FIN --------------
?>