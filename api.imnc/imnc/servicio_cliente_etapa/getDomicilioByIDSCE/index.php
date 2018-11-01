<?php

	include  '../../ex_common/query.php';
	
	$nombre_tabla = "SERVICIO_CLIENTE_ETAPA";
	$correo = "lqc347@gmail.com";	
	$id = $_REQUEST["id"]; 
	
	//$query = "SELECT CLIENTES_DOMICILIOS.ID,CLIENTES_DOMICILIOS.ID AS ID_DOMICILIO, CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO FROM CLIENTES_DOMICILIOS INNER JOIN SERVICIO_CLIENTE_ETAPA ON CLIENTES_DOMICILIOS.ID_CLIENTE = SERVICIO_CLIENTE_ETAPA.ID_CLIENTE WHERE SERVICIO_CLIENTE_ETAPA.ID =".$database->quote($id);;
	/*$query = "select cd.ID as ID_DOMICILIO,NOMBRE_DOMICILIO  ";
	$query .= "from CLIENTES_DOMICILIOS as cd,SERVICIO_CLIENTE_ETAPA as sce ";
	$query .= "where sce.ID_CLIENTE = cd.ID_CLIENTE AND sce.ID =  ".$database->quote($id);*/
	//$selectFill = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);	
	$selectFill = $database->select("CLIENTES_DOMICILIOS",["[><]SERVICIO_CLIENTE_ETAPA"=>["CLIENTES_DOMICILIOS.ID_CLIENTE"=>"ID_CLIENTE"]],["CLIENTES_DOMICILIOS.ID","CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO"],["SERVICIO_CLIENTE_ETAPA.ID"=>$id]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($selectFill));
	
	?>