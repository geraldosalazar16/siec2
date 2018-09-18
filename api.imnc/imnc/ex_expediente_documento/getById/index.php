<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_DOCUMENTO";
	$correo = "lqc347@gmail.com";	
	
	$id = $_REQUEST["id"]; 
	$expediente_documento = $database->select($nombre_tabla, 
		["[><]EX_TIPO_DOCUMENTO" => ["EX_EXPEDIENTE_DOCUMENTO.ID_DOCUMENTO" => "ID"]]	, 
		["EX_EXPEDIENTE_DOCUMENTO.ID",
		"EX_TIPO_DOCUMENTO.NOMBRE", 
		"EX_EXPEDIENTE_DOCUMENTO.OBLIGATORIO", 
		"EX_EXPEDIENTE_DOCUMENTO.HABILITADO"], 
		["EX_EXPEDIENTE_DOCUMENTO.ID"=>$id ]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($expediente_documento[0])); 
?> 
