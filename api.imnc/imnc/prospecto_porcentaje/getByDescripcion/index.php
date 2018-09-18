<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_PORCENTAJE";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$descripcion = $_REQUEST["descripcion"]; 
	echo 123;
	$cantidad_descripcion = $database->count($nombre_tabla, "*", ["PORCENTAJE"=>$descripcion]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$cantidad_descripcion.'"}';
?> 