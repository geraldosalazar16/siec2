<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "TARIFA_COTIZACION_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,"*");
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
