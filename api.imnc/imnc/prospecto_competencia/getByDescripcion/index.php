<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_COMPETENCIA";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$descripcion = $_REQUEST["descripcion"]; 
	$cantidad_descripcion = $database->count($nombre_tabla, "*", ["COMPETENCIA"=>$descripcion]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$cantidad_descripcion.'"}';
?> 