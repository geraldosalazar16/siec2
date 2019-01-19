<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "TARIFA_COTIZACION";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$innerjoin = ["[><]TIPOS_SERVICIO"=>["TARIFA_COTIZACION.ID_TIPO_SERVICIO"=>"ID"]];
	$columnas = [
			"TARIFA_COTIZACION.ID",
			"TARIFA_COTIZACION.DESCRIPCION",
			"TARIFA_COTIZACION.TARIFA",
			"TARIFA_COTIZACION.ACTIVO",
			"TARIFA_COTIZACION.ID_TIPO_SERVICIO",
			"TIPOS_SERVICIO.NOMBRE"
			];
	//$respuesta = $database->select($nombre_tabla,"*");
	$respuesta = $database->select($nombre_tabla,$innerjoin,$columnas);
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
