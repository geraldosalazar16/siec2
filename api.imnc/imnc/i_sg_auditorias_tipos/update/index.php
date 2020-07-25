<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
valida_parametro_and_die($ID,"Falta ID");
$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO,"Falta ID_SERVICIO");
$ACRONIMO = $objeto->ACRONIMO; 
valida_parametro_and_die($ACRONIMO,"Falta ACRONIMO");
$TIPO = $objeto->TIPO; 
valida_parametro_and_die($TIPO,"Falta TIPO"); 
$ETAPA = $objeto->ETAPA; 
valida_parametro_and_die($ETAPA,"Falta ETAPA");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("I_SG_AUDITORIAS_TIPOS", [ 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ACRONIMO" => $ACRONIMO,
	"TIPO" => $TIPO ,
	"ID_ETAPA" => $ETAPA
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
