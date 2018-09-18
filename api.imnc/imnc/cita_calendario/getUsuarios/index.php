<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "USUARIOS";
$correo = "arlette.roman@dhttecno.com";


$respuesta=array(); 
	$respuesta = $database->select($nombre_tabla,[
		"ID(id_usuarios)",
		"NOMBRE(nombre)",
		]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta));
 ?>