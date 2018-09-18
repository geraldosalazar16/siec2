<?php

include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include  '../../common/common_functions.php';


$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ID_CLIENTE = $objeto->ID_CLIENTE; 
valida_parametro_and_die($ID_CLIENTE, "Falta ID_CLIENTE");

$OTRA_RAZON_SOCIAL = $objeto->OTRA_RAZON_SOCIAL; 
valida_parametro_and_die($OTRA_RAZON_SOCIAL, "Es necesario capturar la razón social");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");


$id = $database->insert("CLIENTES_RAZONES_SOCIALES", 
[ 
	"ID_CLIENTE" => $ID_CLIENTE, 
	"OTRA_RAZON_SOCIAL" => $OTRA_RAZON_SOCIAL, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 	



?> 
