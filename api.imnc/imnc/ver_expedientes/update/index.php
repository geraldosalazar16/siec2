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

$ubicacion_documentos = $objeto->UBICACION_DOCUMENTOS; 
valida_parametro_and_die($ubicacion_documentos, "Falta ubicacion_documentos");

$Id_catalogo_documentos = $objeto->ID_CATALOGO_DOCUMENTOS; 
valida_parametro_and_die($Id_catalogo_documentos, "Falta el Id de catalogo_documentos");

$ciclo = $objeto->CICLO; 
valida_parametro_and_die($ciclo, "Falta el ciclo");

$id_servicio = $objeto->ID_SERVICIO; 
valida_parametro_and_die($id_servicio, "Falta ID_SERVICIO");

$estado_documento = $objeto->ESTADO_DOCUMENTO; 
valida_parametro_and_die($estado_documento, "Falta ESTADO DOCUMENTO");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("YmdHis");
///////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////

$id1 = $database->update("BASE_DOCUMENTOS", [ 
	"UBICACION_DOCUMENTOS" => $ubicacion_documentos, 
	"ID_CATALOGO_DOCUMENTOS" => $Id_catalogo_documentos, 
	"CICLO" => $ciclo, 
	"ID_SERVICIO" => $id_servicio,
	"ESTADO_DOCUMENTO" => $estado_documento, 	
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$id]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
