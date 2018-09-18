<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "MODULOS";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$modulo = $database->get($nombre_tabla, ["ID","MODULO(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($modulo)); 
?> 
