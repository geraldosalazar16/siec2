<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error"; 
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
		$mailerror->send("I_SG_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA	=	$objeto->id_servicio_cliente_etapa;
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA,"Falta ID_SERVICIO_CLIENTE_ETAPA");
$ID_CLIENTE_DOMICILIO = $objeto->id_cliente_domicilio;  
valida_parametro_and_die($ID_CLIENTE_DOMICILIO,"Falta ID_CLIENTE_DOMICILIO");

$data = $database->delete("I_EC_SITIOS", [ 
	"AND"=>[
        "ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
        "ID_CLIENTE_DOMICILIO"=>$ID_CLIENTE_DOMICILIO
    ]
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 
?> 
