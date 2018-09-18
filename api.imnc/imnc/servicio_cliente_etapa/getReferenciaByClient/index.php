<?php 
include  '../../ex_common/query.php'; 

if( $_REQUEST["cliente"] != null){
	$cliente = $_REQUEST["cliente"];
	$referencia_seg = $database->select("SERVICIO_CLIENTE_ETAPA", "*", ["ID_CLIENTE"=>$cliente]); 
	valida_error_medoo_and_die("SERVICIO_CLIENTE_ETAPA", "lqc347@gmail.com"); 
	
}
else{
	$referencia_seg = array();
}
print_r(json_encode($referencia_seg)); 
?> 
