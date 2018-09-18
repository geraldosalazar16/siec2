<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "PROSPECTO_CITAS";
$correo = "leovardo.quintero@dhttecno.com";

$respuesta=array(); 
	/*$respuesta = $database->select($nombre_tabla,["[>]TIPO_ASUNTO"=>["TIPO_ASUNTO"=>"ID"], "[>]USUARIOS" => [ "USUARIO_ASIGNADO" => "ID"]],
		[
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ID_COTIZACION(id_cotizacion)",
		"COLOR(color)",
		"USUARIOS.NOMBRE(usuario)",
		],
		[
	"entidad" => 1
]); */

$query = "(SELECT PROSPECTO_CITAS.ID AS id_calendario, ASUNTO AS asunto, FECHA_INICIO AS fecha_inicio, FECHA_FIN AS fecha_fin,
				TIPO_ASUNTO AS tipo_asunto, RECORDATORIO AS recordatorio, ID_PROSPECTO AS id_prospecto, OBSERVACIONES AS observaciones, ID_COTIZACION AS id_cotizacion,
				COLOR AS color,USUARIOS.NOMBRE AS usuario
		FROM PROSPECTO_CITAS,TIPO_ASUNTO,USUARIOS
		WHERE PROSPECTO_CITAS.USUARIO_ASIGNADO = USUARIOS.ID AND PROSPECTO_CITAS.TIPO_ASUNTO = TIPO_ASUNTO.ID AND ENTIDAD = 1)
		UNION
		(SELECT PROSPECTO_CITAS.ID AS id_calendario, ASUNTO AS asunto, FECHA_INICIO AS fecha_inicio, FECHA_FIN AS fecha_fin,
				TIPO_ASUNTO AS tipo_asunto, RECORDATORIO AS recordatorio, ID_PROSPECTO AS id_prospecto, OBSERVACIONES AS observaciones, ID_COTIZACION AS id_cotizacion,
				COLOR AS color,USUARIOS.NOMBRE AS usuario
		FROM PROSPECTO_CITAS,TIPO_ASUNTO,USUARIOS
		WHERE PROSPECTO_CITAS.USUARIO_ASIGNADO = USUARIOS.ID AND PROSPECTO_CITAS.TIPO_ASUNTO = TIPO_ASUNTO.ID AND ENTIDAD = 2)";
		$respuesta=$database->query($query)->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta));
 ?>