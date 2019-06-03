<?php

	include  '../../ex_common/query.php';
	
	$nombre_tabla = "SERVICIO_CLIENTE_ETAPA";
	$correo = "lqc347@gmail.com";	
	$id = $_REQUEST["id"]; 
	$selectFill = $database->select("CLIENTES_DOMICILIOS",["[><]SERVICIO_CLIENTE_ETAPA"=>["ID_CLIENTE"=>"ID_CLIENTE"]],["CLIENTES_DOMICILIOS.ID","CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO"],["SERVICIO_CLIENTE_ETAPA.ID"=>$id]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($selectFill));
	
	?>
