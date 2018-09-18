<?php
	include  '../common/conn-apiserver.php'; 
	include  '../common/conn-medoo.php'; 
	include  '../common/conn-sendgrid.php'; 
	include  './ex_valida.php';

$respuesta=array(); 
$ID = $_REQUEST["id"]; 
$CATALOGO = $_REQUEST["catalogo"]; 

$id = $database->delete($CATALOGO, ["ID"=>$ID]); 
valida_error_medoo_and_die($CATALOGO, "jesus.popocatl@dhttecno.com"); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?>