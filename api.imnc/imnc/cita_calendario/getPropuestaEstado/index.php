<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "PROSPECTO_PROPUESTA_ESTADO";
$correo = "arlette.roman@dhttecno.com";


$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,[
		"ID(id_estado)",
		"ESTADO(estado)",
		]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta));
 ?>