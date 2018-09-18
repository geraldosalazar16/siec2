<?php 

include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php';   

$id = $_REQUEST["id"]; 

$database->delete("SG_AUDITORIA_GRUPO_FECHAS", ["ID_SG_AUDITORIA_GRUPO"=>$id]); 
valida_error_medoo_and_die(); 

$database->delete("SG_AUDITORIA_GRUPOS", ["ID"=>$id]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 

?> 
