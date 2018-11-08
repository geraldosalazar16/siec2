<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_PRODUCTO = $objeto->ID_PRODUCTO;
valida_parametro_and_die($ID_PRODUCTO, "Falta ID_PRODUCTO");; 

$ID_PREGUNTA = $objeto->ID_PREGUNTA; 
valida_parametro_and_die($ID_PREGUNTA, "Falta ID_PREGUNTA");

$RESPUESTA = $objeto->RESPUESTA; 
valida_parametro_and_die($RESPUESTA, "Falta RESPUESTA");

$id = $database->update("PRODUCTO_INTEGRACION", [ 
		"RESPUESTA" => $RESPUESTA
	], 
	[
        "AND" => [
            "ID_PRODUCTO"=>$ID_PRODUCTO,
            "ID_PREGUNTA"=>$ID_PREGUNTA
        ]
    ]
); 
valida_error_medoo_and_die();
 
$respuesta["resultado"] = "ok";
print_r(json_encode($respuesta)); 
?> 