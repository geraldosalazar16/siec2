<?php 
include  '../../ex_common/query.php'; 
include  '../../ex_common/archivos.php';
include  'historial_evento.php';

$nombre_tabla = "PROSPECTO_CITAS";
$correo = "arlette.roman@dhttecno.com";

$respuesta = array();

	$json = file_get_contents("php://input");
	$objeto = json_decode($json); 

	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id +1;
	$ASUNTO = $objeto-> asunto;
	/*$FECHA_INICIO = strtotime($objeto-> fecha_inicio);
	$FECHA_FIN = strtotime($objeto-> fecha_fin);
	echo $objeto-> fecha_inicio."<br>";
	echo date("y-m-d",$FECHA_FIN)."<br>";
	echo date("d",$FECHA_FIN)."<br>";
	echo date("m",$FECHA_FIN)."<br>";
	echo date("y",$FECHA_FIN)."<br>";*/
	$FECHA_INICIO = $objeto-> fecha_inicio;
	$FECHA_FIN = $objeto-> fecha_fin;
	$TIPO_ASUNTO = $objeto-> tipo_asunto;
	$RECORDATORIO = $objeto-> recordatorio;
	$OBSERVACIONES = $objeto-> observaciones;
	$ID_COTIZACION = $objeto -> id_cotizacion;
	$ID_PROSPECTO = $objeto-> id_prospecto;
	$ENTIDAD = $objeto-> entidad;
	$ESTATUS_COTIZACION = $objeto-> estatus_cotizacion;
	$FACTIBILIDAD = $objeto-> factibilidad;
	$USUARIO_ASIGNADO = $objeto-> usuario_asignado;
	$USUARIO = $objeto-> id_usuario_modificacion;


	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"ASUNTO" => $ASUNTO,
		"FECHA_INICIO" => $FECHA_INICIO,
		"FECHA_FIN" => $FECHA_FIN,
		"TIPO_ASUNTO" => $TIPO_ASUNTO,
		"RECORDATORIO" => $RECORDATORIO,
		"OBSERVACIONES" => $OBSERVACIONES,
		"ID_PROSPECTO" => $ID_PROSPECTO,
		"ENTIDAD" => $ENTIDAD,
		"USUARIO_ASIGNADO" => $USUARIO_ASIGNADO,
		"ESTATUS_COTIZACION" => $ESTATUS_COTIZACION,
		"FACTIBILIDAD" => $FACTIBILIDAD,
		"ID_COTIZACION" => $ID_COTIZACION,
	]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$id_historial = insertHistorial($database, $ID, $USUARIO);
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id_historial; 
	
	print_r(json_encode($respuesta)); //es lo que espera js
 ?>