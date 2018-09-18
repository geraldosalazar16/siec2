<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "NORMAS_TIPOSERVICIO";
	$correo = "lqc347@gmail.com";
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	$ID_NORMA = $objeto->ID_NORMA; 
	$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 	
	$exist = $database->count($nombre_tabla, "*", ["AND"=>["ID_NORMA"=>$ID_NORMA,"ID_TIPO_SERVICIO"=>$ID_TIPO_SERVICIO]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	$respuesta["resultado"]="ok"; 
	$respuesta["cantidad"]=$exist; 
	print_r($respuesta); 
?> 
