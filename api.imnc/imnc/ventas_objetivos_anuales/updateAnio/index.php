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
		$mailerror->send("USUARIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
valida_parametro_and_die($ID,"Debe seleccionar un año");
$ANIO = $objeto->ANIO; 
valida_parametro_and_die($ANIO,"Debe seleccionar un valor para el año");
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

//validar que no se sobrepase el objetivo anual
$suma_actual = $database->sum("VENTAS_OBJETIVOS_MENSUALES","MONTO",["ID_ANIO" => $mes["ID_ANIO"]]);
valida_error_medoo_and_die(); 
if($MONTO < $suma_actual){
    $respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "El objetivo anual no puede ser inferior a la suma de los objetivos mensuales"; 
	print_r(json_encode($respuesta)); 
	die();
}
$id = $database->update("VENTAS_OBJETIVOS_ANUALES", [ 
    "ANIO" => $ANIO,
	"MONTO" => $MONTO,
],["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 
?> 
