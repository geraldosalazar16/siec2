<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "CALIFICACIONES_NORMAS";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$id= $_REQUEST['id']; 
	$respuesta = $database->select($nombre_tabla,"*",["ID_CALIFICACION"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
