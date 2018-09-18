<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);

include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php';
	
$id = $_REQUEST["id"]; 

$sg_auditoria_notificacion = $database->get("SG_AUDITORIA_NOTIFICACION", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 

print_r(json_encode($sg_auditoria_notificacion)); 
?> 
