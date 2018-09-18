<?php 
include  '../../ex_common/query.php'; 

	function encriptar($cadena){
		$key='EXPEDIENTEDHT2016CMLJJJAI';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		return urlencode($encrypted); //Devuelve el string encriptado
	}

$nombre_tabla = "PROSPECTO_CITAS";
$join_usuario = ["[>]USUARIOS(users)" => [ "USUARIO_ASIGNADO" => "ID"], "[>]PROSPECTO_PORCENTAJE(porcentaje)" => [ "FACTIBILIDAD" => "ID"]];
$correo = "arlette.roman@dhttecno.com";

$id = $_REQUEST["id"]; 
	$cita = $database->select($nombre_tabla, $join_usuario, [
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ESTATUS_COTIZACION(estatus_cotizacion)",
		"FACTIBILIDAD(factibilidad)",
		"porcentaje.DESCRIPCION(descripcion_porcentaje)",
		"USUARIO_ASIGNADO(usuario_asignado)",
		"ID_COTIZACION(id_cotizacion)",
		"users.NOMBRE(nombre_usuario_asignado)",
		] ,[
		"PROSPECTO_CITAS.ID"=>$id
		]);
	$cita = $cita[0];
	$cita_historial = $database->select("PROSPECTO_CITA_HISTORIAL",
		"*"
		,
		["AND"=>["ID_CITA"=>$id]]
		);
	$cita_archivos =  $database->select("PROSPECTO_CITAS_ARCHIVOS", [
		"ID(id)",
		"NOMBRE_ARCHIVO(nombre_archivo)",
		] ,[
		"ID_CITA_HISTORIAL"=> $cita_historial[sizeof($cita_historial) -1]["ID"]
		]);
	foreach ($cita_archivos as $key => $value) {
		$cita_archivos[$key]["id_encriptado"] = encriptar($value["id"]);
		$cita_archivos[$key]["id_noencriptado"] = $value["id"];
	}
	$cita["archivos"] = $cita_archivos;
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($cita)); 

 ?>