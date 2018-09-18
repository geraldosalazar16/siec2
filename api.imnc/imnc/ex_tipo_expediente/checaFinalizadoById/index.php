<?php 
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "EX_TIPO_EXPEDIENTE";
	$correo = "lqc347@gmail.com";	
	
	$id = $_REQUEST["id"]; 
	$tipo_expediente = $database->get($nombre_tabla, "FINALIZADO", ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	echo '{"finalizado" : "'.$tipo_expediente.'"}';
?> 
