<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "TARIFA_COTIZACION_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$prospecto_origen = $database->get($nombre_tabla, "*", ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_origen)); 
?> 
