<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO_ESTATUS_SEGUIMIENTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,["ID","ESTATUS_SEGUIMIENTO(DESCRIPCION)"]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
