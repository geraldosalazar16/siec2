<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PROPUESTA_ESTADO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$tipo_documento = $database->get($nombre_tabla, ["ID","ESTADO(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tipo_documento)); 
?> 
