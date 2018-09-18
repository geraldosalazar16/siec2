<?php 
include  '../../ex_common/query.php';  
	
	$nombre_tabla = "TIPO_ASUNTO";
	$correo = "arlette.roman@dhttecno.com";
	
	$desc = $_REQUEST["desc"]; 
	$tipo_asunto = $database->count($nombre_tabla, [
		"ID(id_tipo_asunto)",
		"DESCRIPCION(descripcion)",
		"USUARIO_CREACION(id_usuario_registro)",
		"FECHA_CREACION(fecha_registro)",
		"USUARIO_MODIFICACION(id_usuario_modificacion)",
		"FECHA_MODICACION(fecha_modificacion)",
		"COLOR(color)"
		],[
		"DESCRIPCION"=>$desc
		]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$tipo_asunto.'"}';
 ?>