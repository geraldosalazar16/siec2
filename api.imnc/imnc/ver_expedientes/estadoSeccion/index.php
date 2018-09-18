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


$nombre_seccion = $objeto->SECCION; 
valida_parametro_and_die($nombre_seccion, "Falta nombre_seccion");

$nombre_etapa = $objeto->ETAPA; 
valida_parametro_and_die($nombre_etapa, "Falta el nombre_etapa");

$ciclo = $objeto->CICLO; 
valida_parametro_and_die($ciclo, "Falta el ciclo");

$id_servicio = $objeto->ID_SERVICIO; 
valida_parametro_and_die($id_servicio, "Falta ID_SERVICIO");

$estado_seccion = $objeto->ESTADO_SECCION; 
valida_parametro_and_die($estado_seccion, "Falta ESTADO SECCION");

///////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////
if($estado_seccion == "Aprobada")
$id = $database->insert("ESTADO_SECCIONES", [ 
	"SECCION" => $nombre_seccion, 
	"ETAPA" => $nombre_etapa, 
	"CICLO" => $ciclo, 
	"ID_SERVICIO" => $id_servicio,
	"ESTADO_SECCION" => $estado_seccion, 	
	
]);
else
$id = $database->delete("ESTADO_SECCIONES",["AND"=> [ 
	"SECCION" => $nombre_seccion, 
	"ETAPA" => $nombre_etapa, 
	"CICLO" => $ciclo, 
	"ID_SERVICIO" => $id_servicio,
	]]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
