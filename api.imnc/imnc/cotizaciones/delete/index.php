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

$ID = $objeto->id_cotizacion;  
valida_parametro_and_die($ID,"Falta ID de cotización");

$data = $database->delete("COTIZACIONES",[
    "AND"=>[
        "ID"=>$ID
    ]
]); 
valida_error_medoo_and_die();
//Borrar de COTIZACIONES_TRAMITES
$data = $database->delete("COTIZACIONES_TRAMITES",[
    "AND"=>[
        "ID_COTIZACION"=>$ID
    ]
]);
valida_error_medoo_and_die();
//Borrar de COTIZACION_SITIOS
$data = $database->delete("COTIZACION_SITIOS",[
    "AND"=>[
        "ID_COTIZACION"=>$ID
    ]
]); 
valida_error_medoo_and_die();
//Borrar de COTIZACION_NORMAS
$data = $database->delete("COTIZACION_NORMAS",[
    "AND"=>[
        "ID_COTIZACION"=>$ID
    ]
]); 
valida_error_medoo_and_die();

$respuesta["resultado"]="ok";
print_r(json_encode($respuesta)); 
?> 
