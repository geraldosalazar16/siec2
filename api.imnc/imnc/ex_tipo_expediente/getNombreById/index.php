<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_TIPO_EXPEDIENTE";
	$correo = "lqc347@gmail.com";
	
	$id = $_REQUEST["id_expediente"]; 
	$tipo_expediente = $database->select($nombre_tabla, ["NOMBRE","VIGENTE"], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tipo_expediente));
?> 
