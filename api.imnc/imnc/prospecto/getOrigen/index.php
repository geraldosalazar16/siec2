<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "PROSPECTO_ORIGEN";
$correo = "isaurogaleana19@gmail.com";


$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,[
		"ID",
		"ORIGEN",
		]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta));
 ?>