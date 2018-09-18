<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "USUARIOS";
$correo = "arlette.roman@dhttecno.com";

$id = $_REQUEST["id"]; 
$respuesta=array(); 
	$respuesta = $database->get($nombre_tabla,[
		"ID(id_usuarios)",
		"NOMBRE(nombre)",
		"USUARIO(usuario)",
		"PERMISOS(permisos)"
		],
		["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta));
 ?>