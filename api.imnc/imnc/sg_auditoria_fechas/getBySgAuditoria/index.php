<?php 
  error_reporting(E_ALL);
  ini_set("display_errors",1);

include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php';


$ID_SG_AUDITORIA = $_REQUEST["id_sg_auditoria"]; 

$SG_AUDITORIA_FECHAS["SG_AUDITORIA_FECHAS"] = $database->select("SG_AUDITORIA_FECHAS", "*", ["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA]); 
valida_error_medoo_and_die();

$SG_AUDITORIA_FECHAS["FECHAS_MULTIDATEPICKER"] = array();
for ($i=0; $i < count($SG_AUDITORIA_FECHAS["SG_AUDITORIA_FECHAS"]) ; $i++) { 
	$dt = DateTime::createFromFormat('Ymd', $SG_AUDITORIA_FECHAS["SG_AUDITORIA_FECHAS"][$i]["FECHA"]);
	$fecha_multidatepicker = $dt->format('d/m/Y');
	array_push($SG_AUDITORIA_FECHAS["FECHAS_MULTIDATEPICKER"], $fecha_multidatepicker);
}


print_r(json_encode($SG_AUDITORIA_FECHAS)); 
?> 
