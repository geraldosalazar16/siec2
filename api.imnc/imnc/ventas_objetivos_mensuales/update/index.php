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
valida_parametro_and_die($ID,"Es necesario seleccionar un MES");
$MONTO = $objeto->MONTO; 
valida_parametro_and_die($MONTO,"Es necesario seleccionar un MONTO");

//validar que no se sobrepase el objetivo anual
$mes = $database->get("VENTAS_OBJETIVOS_MENSUALES","*",["ID" => $ID]);
valida_error_medoo_and_die(); 
$anio = $database->get("VENTAS_OBJETIVOS_ANUALES","*",["ID" => $mes["ID_ANIO"]]);
valida_error_medoo_and_die(); 
$suma_actual = $database->sum("VENTAS_OBJETIVOS_MENSUALES","MONTO",["ID_ANIO" => $mes["ID_ANIO"]]);
valida_error_medoo_and_die(); 
if($anio["MONTO"] < $suma_actual+$MONTO-$mes["MONTO"]){
    $respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "La suma de los objetivos mensuales no puede exceder objetivo anual"; 
	print_r(json_encode($respuesta)); 
	die();
}
$id = $database->update("VENTAS_OBJETIVOS_MENSUALES", [ 
	"MONTO" => $MONTO,
],["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok";  
print_r(json_encode($respuesta)); 
?> 
