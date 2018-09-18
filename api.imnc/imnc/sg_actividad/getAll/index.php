<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "SG_ACTIVIDAD";
	$correo = "jesus.popocatl@dhttecno.com";

$respuesta = $database->select($nombre_tabla, "*"); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>