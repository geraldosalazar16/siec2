<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "SERVICIO_COTIZACION_CAMBIO";
	$correo = "lqc347@gmail.com";
	
	$id = $_REQUEST["id"]; 
	$SERVICIO_CAMBIO = $database->select($nombre_tabla, "*", ["ID_TRAMITE"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($SERVICIO_CAMBIO)); 
?> 
