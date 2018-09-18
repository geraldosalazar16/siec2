<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PERMISOS";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$permiso = $database->get($nombre_tabla, ["ID","PERMISO(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($permiso)); 
?> 
