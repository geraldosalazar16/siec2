<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_TIPO_CONTRATO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$prospecto_origen = $database->get($nombre_tabla, ["ID","TIPO_CONTRATO(DESCRIPCION)"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_origen)); 
?> 
