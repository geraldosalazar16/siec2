<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "MODULOS";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$modulo = $_REQUEST["modulo"]; 
	$cont_modulo = $database->count($nombre_tabla, "*", ["MODULO"=>$permiso]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"PERMISO" : "'.$cont_modulo.'"}';
?> 
