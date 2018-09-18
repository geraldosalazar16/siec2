<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "TIPOS_SERVICIO";
	$correo = "lqc347@gmail.com";
	
	$acronimo = $_REQUEST["acronimo"]; 
	$id_servicio = $_REQUEST["id_servicio"]; 
	$tipo_documento = $database->count($nombre_tabla, "*", ["AND"=>["ACRONIMO"=>$acronimo,"ID_SERVICIO"=>$id_servicio]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$tipo_documento.'"}';
?> 
