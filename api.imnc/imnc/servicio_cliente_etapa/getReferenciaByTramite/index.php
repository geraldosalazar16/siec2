<?php 
include  '../../ex_common/query.php'; 

$tramite = $_REQUEST["tramite"];
$cliente = $_REQUEST["cliente"];
$referencia = $database->select("SERVICIO_CLIENTE_ETAPA", "*", ["AND" => ["ID_ETAPA_PROCESO"=>$tramite, "ID_CLIENTE"=>$cliente]]); 
valida_error_medoo_and_die("SERVICIO_CLIENTE_ETAPA", "lqc347@gmail.com"); 
print_r(json_encode($referencia)); 
?> 
