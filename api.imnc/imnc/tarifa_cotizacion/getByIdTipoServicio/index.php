<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "TARIFA_COTIZACION";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$respuesta = $database->select($nombre_tabla, "*", ["ID_TIPO_SERVICIO"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($respuesta)); 
?> 
