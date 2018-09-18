<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PERMISOS";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$permiso = $_REQUEST["permiso"]; 
	$cont_permiso = $database->count($nombre_tabla, "*", ["PERMISO"=>$permiso]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"PERMISO" : "'.$cont_permiso.'"}';
?> 
