<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PERFILES";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$perfil = $_REQUEST["perfil"]; 
	$cont_perfiles = $database->count($nombre_tabla, "*", ["PERFIL"=>$perfil]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"PERFIL" : "'.$cont_perfiles.'"}';
?> 
