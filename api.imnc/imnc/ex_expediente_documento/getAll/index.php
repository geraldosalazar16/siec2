<?php 
include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "EX_EXPEDIENTE_DOCUMENTO";
	$correo = "lqc347@gmail.com";	
	$tipo_expediente = $database->select($nombre_tabla, 
		["[><]EX_TIPO_DOCUMENTO" => ["EX_EXPEDIENTE_DOCUMENTO.ID_DOCUMENTO" => "ID"]]	, 
		["EX_EXPEDIENTE_DOCUMENTO.ID"
		"EX_TIPO_DOCUMENTO.NOMBRE(NOMBRE_DOCUMENTO)", 
		"EX_EXPEDIENTE_DOCUMENTO.OBLIGATORIO", 
		"EX_EXPEDIENTE_DOCUMENTO.HABILITADO"]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($tipo_expediente));
?> 
