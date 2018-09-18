<?php

include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include  '../../common/common_functions.php';


$respuesta=array(); 

$ID_CLIENTE = $_REQUEST["id_cliente"]; 
valida_parametro_and_die($ID_CLIENTE, "Falta ID_CLIENTE");


$otras_razones_sociales = $database->select("CLIENTES_RAZONES_SOCIALES", "*" ,["ID_CLIENTE" => $ID_CLIENTE]); 
valida_error_medoo_and_die(); 


print_r(json_encode($otras_razones_sociales)); 	



?> 
