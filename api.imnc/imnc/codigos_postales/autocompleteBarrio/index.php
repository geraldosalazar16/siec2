<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "CODIGOS_POSTALES";
	$correo = "arlette.roman@dhttecno.com";

$COL = $_REQUEST['namePattern'];
$CP = $_REQUEST['CP'];

$respuesta = $database->select($nombre_tabla, "*", ["AND"=>["COLONIA_BARRIO[~]"=> $COL,"CP"=>$CP]]); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>