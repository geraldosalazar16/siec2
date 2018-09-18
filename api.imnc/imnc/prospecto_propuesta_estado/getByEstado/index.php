<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_PROPUESTA_ESTADO";
	$correo = "juliocesar.gallarza@dhttecno.com";
	
	$estado = $_REQUEST["estado"]; 
	$propspecto_propuesta_estado = $database->count($nombre_tabla, "*", ["ESTADO"=>$estado]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"Estado" : "'.$prospecto_propuesta_estado.'"}';
?> 
