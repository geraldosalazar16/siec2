<?php 
	include  '../../ex_common/query.php'; 
	$nombre_tabla = "SERVICIO_COTIZACION_CAMBIO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 

	foreach ($objeto as $key => $value) {
		$database->delete($nombre_tabla, ["ID" => $value->ID]);
	}
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta));
?>
