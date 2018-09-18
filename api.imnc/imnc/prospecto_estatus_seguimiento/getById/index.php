<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_ESTATUS_SEGUIMIENTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$prospecto_origen = $database->get($nombre_tabla, ["ID","ESTATUS_SEGUIMIENTO(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_origen)); 
?> 
