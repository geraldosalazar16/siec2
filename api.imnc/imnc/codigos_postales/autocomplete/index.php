<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "CODIGOS_POSTALES";
	$correo = "arlette.roman@dhttecno.com";

$CP = $_REQUEST['namePattern'];
$query = "SELECT DISTINCT CP FROM CODIGOS_POSTALES  WHERE CP LIKE '".$CP."%'";
$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>