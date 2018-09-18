<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "COTIZACION_NIVEL_INTEGRACION";
	$correo = "leovardo.quintero@dhttecno.com";
	$x = $_REQUEST["x"];
	$y = $_REQUEST["y"];
	$query= "SELECT VALOR FROM COTIZACION_NIVEL_INTEGRACION WHERE X_MIN_PORCENTAJE < '".$x."' AND X_MAX_PORCENTAJE >= '".$x."' AND Y_MIN_PORCENTAJE < '".$y."' AND Y_MAX_PORCENTAJE >= '".$y."';";

	$respuesta = $database->query( $query)->fetchAll(PDO::FETCH_ASSOC);

	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?>	
