<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO_PORCENTAJE";
	$correo = "juliocesar.gallarza@dhttecno.com";
	
	$porcentaje = $_REQUEST["porcentaje"]; 
	$tipo_documento = $database->count($nombre_tabla, "*", ["PORCENTAJE"=>$porcentaje]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$prospecto_porcentaje.'"}';
?> 
