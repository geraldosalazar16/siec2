<?php  
	include  '../../ex_common/query.php';
	include  '../insert/historial_evento.php';
	
	$nombre_tabla = "PROSPECTO_CITAS";
	$correo = "arlette.dhttecno.com"; 

$respuesta = array();

	$json = file_get_contents("php://input"); //obteniendo los datos del jason

	$objeto = json_decode($json); 

	$ID = $objeto-> id_calendario;
	$ASUNTO = $objeto-> asunto;
	$TIPO_ASUNTO = $objeto-> tipo_asunto;
	$RECORDATORIO = $objeto-> recordatorio;
	$OBSERVACIONES = $objeto-> observaciones;
	$ID_COTIZACION = $objeto -> id_cotizacion;
	$ESTATUS_COTIZACION = $objeto-> estatus_cotizacion;
	$FACTIBILIDAD = $objeto-> factibilidad;
	$USUARIO_ASIGNADO = $objeto-> usuario_asignado;
	$USUARIO = $objeto-> id_usuario_modificacion;

	$id = $database->update($nombre_tabla, [
		"ASUNTO" => $ASUNTO,
		"TIPO_ASUNTO" => $TIPO_ASUNTO,
		"RECORDATORIO" => $RECORDATORIO,
		"OBSERVACIONES" => $OBSERVACIONES,
		"USUARIO_ASIGNADO" => $USUARIO_ASIGNADO,
		"ESTATUS_COTIZACION" => $ESTATUS_COTIZACION,
		"FACTIBILIDAD" => $FACTIBILIDAD,
		"ID_COTIZACION" => $ID_COTIZACION,
	],["ID" =>$ID]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$id_historial = insertHistorial($database, $ID, $USUARIO);
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id_historial; 

	
	print_r(json_encode($respuesta)); //es lo que espera js 
?> 


