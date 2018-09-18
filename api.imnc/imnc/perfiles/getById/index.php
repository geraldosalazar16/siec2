<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PERFILES";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$perfil = $database->get($nombre_tabla, ["ID","PERFIL(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($perfil)); 
?> 
