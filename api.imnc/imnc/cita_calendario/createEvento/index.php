<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "CITA_CALENDARIO";
$correo = "arlette.roman@dhttecno.com";

$respuesta = array();

	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 

	$ultimo_id = $database->max($nombre_tabla,"id_calendario");
	$ID = $ultimo_id +1;
	$ASUNTO = $objeto-> asunto;
	$FECHA_INICIO = $objeto-> fecha_inicio;
	$FECHA_FIN = $objeto-> fecha_fin;
	$TIPO_ASUNTO = $objeto-> tipo_asunto;
	$RECORDATORIO = $objeto-> recordatorio;
	$OBSERVACIONES = $objeto-> observaciones;
	$ID_PROSPECTO = $objeto-> id_prospecto;
	$ID_USUARIO_REGISTRO = $objeto-> id_usuario_registro;
	$FECHA_REGISTRO =date('Y/m/d H:i:s');
	$ID_USUARIO_MODIFICACION = $objeto-> id_usuario_modificacion;
	$FECHA_MODIFICACION = date('Y/m/d H:i:s');

	$id = $database->insert($nombre_tabla, [
		"id_calendario" => $ID,	
		"asunto" => $ASUNTO,
		"fecha_inicio" => $FECHA_INICIO,
		"fecha_fin" => $FECHA_FIN,
		"tipo_asunto" => $TIPO_ASUNTO,
		"recordatorio" => $RECORDATORIO,
		"observaciones" => $OBSERVACIONES,
		"id_prospecto" => $ID_PROSPECTO,
		"id_usuario_registro" => $ID_USUARIO_REGISTRO, 
		"fecha_registro" => $FECHA_REGISTRO,
		"id_usuario_modificacion" => $ID_USUARIO_MODIFICACION,
		"fecha_modificacion" => $FECHA_MODIFICACION
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); //es lo que espera js
 ?>