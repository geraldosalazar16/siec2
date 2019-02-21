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
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ANIO = $objeto->ANIO; 
valida_parametro_and_die($ANIO,"Debe seleccionar un año");
$l = strlen($ANIO);
$t = ctype_digit($ANIO);
if( !$t || $l < 4){
    $respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El año no tiene el formato correcto"; 
	print_r(json_encode($respuesta)); 
	die();
}
$MONTO = $objeto->MONTO; 
valida_parametro_and_die($MONTO,"Debe seleccionar un MONTO");

$id = $database->insert("VENTAS_OBJETIVOS_ANUALES", [ 
	"ANIO" => $ANIO, 
	"MONTO" => $MONTO
]); 
valida_error_medoo_and_die(); 
//Insertar los meses en VENTAS_OBJETIVOS_MENSUALES
$meses = [
	"ENERO",
	"FEBRERO",
	"MARZO",
	"ABRIL",
	"MAYO",
	"JUNIO",
	"JULIO",
	"AGOSTO",
	"SEPTIEMBRE",
	"OCTUBRE",
	"NOVIEMBRE",
	"DICIEMBRE"
];
for($i=0;$i<12;$i++){
	$id_mes = $database->insert("VENTAS_OBJETIVOS_MENSUALES", [ 
		"ID_ANIO" => $id, 
		"MES" => $meses[$i],
		"MONTO" => 0
	]);
	valida_error_medoo_and_die();
}
 
$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 

?> 
