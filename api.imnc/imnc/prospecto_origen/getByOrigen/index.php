<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_ORIGEN";
	$correo = "juliocesar.gallarza@dhttecno.com";
	
	$origen = $_REQUEST["origen"]; 
	$prospecto_origen = $database->count($nombre_tabla, "*", ["ORIGEN"=>$origen]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$prospecto_origen.'"}';
?> 
