<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}


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
		$mailerror->send("SG_SECTORES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id = $objeto->ID; 
valida_parametro_and_die($id, "Falta ID");

$NOMBRE = $objeto->NOMBRE; 
valida_parametro_and_die($NOMBRE, "Falta el nombre del cambio");

$DESCRIPCION = $objeto->DESCRIPCION; 
valida_parametro_and_die($DESCRIPCION, "Falta la descripcion");

///////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////
$nombre_tabla = "I_SERVICIOS_CONTRATADOS_TIPOS_CAMBIOS";
$id1 = $database->update($nombre_tabla, [ 
	"NOMBRE" => $NOMBRE, 
	"DESCRIPCION" => $DESCRIPCION, 
	
], ["ID"=>$id]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
