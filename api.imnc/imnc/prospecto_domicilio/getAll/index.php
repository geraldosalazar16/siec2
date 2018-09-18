<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO_DOMICILIO";
	$correo = "isaurogaleana19@gmail.com";
	$id = $_REQUEST["id"];
	$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,"*",["ID_PROSPECTO"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
