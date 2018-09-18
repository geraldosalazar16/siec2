<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "SERVICIO_ROL";
	$correo = "lqc347@gmail.com";
	
	$tipo_servicio = $_REQUEST["tipo_servicio"]; 
	$rol = $_REQUEST["rol"];
	$tiempo = $database->get($nombre_tabla, "TIEMPO", ["AND" => ["ID_TIPO_SERVICIO"=>$tipo_servicio, "ID_ROL"=> $rol ]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tiempo));
?> 
