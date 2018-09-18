<?php

	include  '../../ex_common/query.php';
	
	$nombre_tabla = "SERVICIO_CLIENTE_ETAPA";
	$correo = "lqc347@gmail.com";	
	$id = $_REQUEST["id"]; 
	
	$query = "select cd.ID as ID_DOMICILIO,NOMBRE_DOMICILIO  ";
	$query .= "from CLIENTES_DOMICILIOS as cd,SERVICIO_CLIENTE_ETAPA as sce ";
	$query .= "where sce.ID_CLIENTE = cd.ID_CLIENTE AND sce.ID =  ".$database->quote($id);
	$selectFill = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($selectFill));
	
	?>