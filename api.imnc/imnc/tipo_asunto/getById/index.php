<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "TIPO_ASUNTO";
	$correo = "arlette.roman@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$tipo_asunto = $database->get($nombre_tabla, [
		"ID(id_tipo_asunto)",
		"DESCRIPCION(descripcion)",
		"USUARIO_CREACION(id_usuario_registro)",
		"FECHA_CREACION(fecha_registro)",
		"USUARIO_MODIFICACION(id_usuario_modificacion)",
		"FECHA_MODIFICACION(fecha_modificacion)",
		"COLOR(color)"
		], [
		"ID"=>$id
		]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tipo_asunto)); 
?> 
