<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "PAISES";
	$correo = "arlette.roman@dhttecno.com";

$nombre = $_REQUEST['namePattern'];

$respuesta = $database->select($nombre_tabla, "*",["NOMBRE[~]"=> $nombre]); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>