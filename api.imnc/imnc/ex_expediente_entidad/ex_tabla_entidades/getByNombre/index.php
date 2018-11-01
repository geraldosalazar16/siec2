<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_TABLA_ENTIDADES";
	$correo = "lqc347@gmail.com";
	
	$nombre = $_REQUEST["nombre"]; 
	$tipo_documento = $database->count($nombre_tabla, "*", ["TABLA"=>$nombre]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$tipo_documento.'"}';
?> 
