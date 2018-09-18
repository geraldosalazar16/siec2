<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "SG_ACTIVIDAD";
	$correo = "jesus.popocatl@dhttecno.com";

$nombre = $_REQUEST['namePattern'];
$id_servicio = $_REQUEST['id_servicio'];

$servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$id_servicio]); 
$ID_CLIENTE = $database->get("CLIENTES", "ID", ["ID"=>$servicio_cliente_etapa["ID_CLIENTE"]]);

$respuesta = $database->select($nombre_tabla, "*",["AND" => ["ACTIVIDAD[~]"=> $nombre, "ID_CLIENTE"=> $ID_CLIENTE]]); 
valida_error_medoo_and_die($nombre_tabla,$correo); 
print_r(json_encode($respuesta)); 
?>