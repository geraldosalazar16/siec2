<?php  
//error_reporting(E_ALL);
//ini_set("display_errors",1);

include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php';
  
$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
$TIPO_NOTIFICACION = $objeto->TIPO_NOTIFICACION; 
$TIPO_CAMBIOS = $objeto->TIPO_CAMBIOS; 
$CERTIFICACION_MANTENIMIENTO = $objeto->CERTIFICACION_MANTENIMIENTO; 
$NOTA1 = $objeto->NOTA1; 
$NOTA2 = $objeto->NOTA2; 
$NOTA3 = $objeto->NOTA3; 
$QUIEN_AUTORIZA = $objeto->QUIEN_AUTORIZA; 
$CARGO_AUTORIZA = $objeto->CARGO_AUTORIZA; 
$ID_DOMICILIO = $objeto->ID_DOMICILIO; 

$id = $database->insert("SG_AUDITORIA_NOTIFICACION", [ 
	"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
	"ID_DOMICILIO" => $ID_DOMICILIO,
	"TIPO_NOTIFICACION" => $TIPO_NOTIFICACION, 
	"TIPO_CAMBIOS" => $TIPO_CAMBIOS, 
	"CERTIFICACION_MANTENIMIENTO" => $CERTIFICACION_MANTENIMIENTO, 
	"NOTA1" => $NOTA1, 
	"NOTA2" => $NOTA2, 
	"NOTA3" => $NOTA3, 
	"QUIEN_AUTORIZA" => $QUIEN_AUTORIZA, 
	"CARGO_AUTORIZA" => $CARGO_AUTORIZA, 
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 

print_r(json_encode($respuesta)); 

?> 
