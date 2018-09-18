<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_TIPO_EXPEDIENTE";
	$correo = "lqc347@gmail.com";
	

$respuesta = $database->select($nombre_tabla, "*",["ID[!]" => "ID_EXP_ANT"]); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>