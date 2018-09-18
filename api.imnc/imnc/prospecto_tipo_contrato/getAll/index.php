<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO_TIPO_CONTRATO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,["ID","TIPO_CONTRATO(DESCRIPCION)"]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
