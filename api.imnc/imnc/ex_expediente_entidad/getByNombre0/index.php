<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "99galeanaisauro19@gmail.com";
	
	$entidad = $_REQUEST["entidad"];
	$expediente = $_REQUEST["expediente"];  
	$tipo_documento = $database->count($nombre_tabla,["AND" => 
	["ID_ENTIDAD"=>$entidad,"ID"=>$expediente]]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$tipo_documento.'"}';
?> 
