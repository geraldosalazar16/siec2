<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PORCENTAJE";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$prospecto_porcentaje = $database->get($nombre_tabla, ["ID","PORCENTAJE","DESCRIPCION"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_porcentaje));
?>