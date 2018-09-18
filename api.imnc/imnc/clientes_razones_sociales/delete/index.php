<?php

include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include  '../../common/common_functions.php';


$respuesta=array(); 

$ID = $_REQUEST["id"]; 
valida_parametro_and_die($ID, "Falta ID");


$id = $database->delete("CLIENTES_RAZONES_SOCIALES", ["ID" => $ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 	



?> 
