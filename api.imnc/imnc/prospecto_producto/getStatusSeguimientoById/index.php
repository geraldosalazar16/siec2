<?php
	include  '../../ex_common/query.php'; 
	
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$tabla_cliente = ["[>]PROSPECTO_ESTATUS_SEGUIMIENTO(PES)"=>["ID_ESTATUS_SEGUIMIENTO"=>"ID"]];
	$correo = "leovardo.quintero@dhttecno.com";
	$campos = ["PROSPECTO_PRODUCTO.ID_ESTATUS_SEGUIMIENTO","PES.ESTATUS_SEGUIMIENTO"];
	
	$id = $_REQUEST["id"]; 
	$prospecto_producto = $database->get($nombre_tabla,$tabla_cliente , $campos, ["PROSPECTO_PRODUCTO.ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla, $correo ); 
	print_r(json_encode($prospecto_producto)); 
?> 
