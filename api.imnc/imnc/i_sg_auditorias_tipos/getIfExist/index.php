<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "I_SG_AUDITORIAS_TIPOS";
	$correo = "lqc347@gmail.com";
	
	$etapa = $_REQUEST["etapa"]; 
	$id_servicio = $_REQUEST["id_servicio"]; 
	$tipo_documento = $database->count($nombre_tabla, "*", ["AND"=>["ID_ETAPA"=>$etapa,"ID_SERVICIO"=>$id_servicio]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$tipo_documento.'"}';
?> 
