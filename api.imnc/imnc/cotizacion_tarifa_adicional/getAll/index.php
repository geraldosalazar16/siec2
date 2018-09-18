<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "COTIZACION_TARIFA_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$tarifas_adicionales = $database->select("COTIZACION_TARIFA_ADICIONAL",["[><]TARIFA_COTIZACION_ADICIONAL"=>["COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL"=>"ID"]], ["TARIFA_COTIZACION_ADICIONAL.DESCRIPCION","TARIFA_COTIZACION_ADICIONAL.TARIFA","COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE","COTIZACION_TARIFA_ADICIONAL.ID"]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tarifas_adicionales)); 
?> 
