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

/*$id = $objeto->ID; 
valida_parametro_and_die($id, "Falta ID");
*/
$UBICACION_DOCUMENTOS =""; //$objeto->UBICACION_DOCUMENTOS; 
//valida_parametro_and_die($ubicacion_documentos, "Falta ubicacion_documentos");

$ID_CATALOGO_DOCUMENTOS = $objeto->ID_CATALOGO_DOCUMENTOS; 
valida_parametro_and_die($ID_CATALOGO_DOCUMENTOS, "Falta el Id de catalogo_documentos");

$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el ciclo");

$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO, "Falta ID_SERVICIO");

$ESTADO_DOCUMENTO = $objeto->ESTADO_DOCUMENTO; 
valida_parametro_and_die($ESTADO_DOCUMENTO, "Falta ESTADO DOCUMENTO");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION			=	date("YmdHis");
//			$ID_USUARIO_CREACION	=	$_POST['ID_USUARIO'];
			$FECHA_MODIFICACION		=	"";
			$ID_USUARIO_MODIFICACION=	"";
///////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////
$nombre_tabla = "BASE_DOCUMENTOS";
$id1 = $database->insert($nombre_tabla, [ 
				"UBICACION_DOCUMENTOS" => $UBICACION_DOCUMENTOS, 
				"ID_CATALOGO_DOCUMENTOS" => $ID_CATALOGO_DOCUMENTOS, 
				"CICLO" => $CICLO,
				"ID_SERVICIO" => $ID_SERVICIO,
				"ESTADO_DOCUMENTO" => $ESTADO_DOCUMENTO,
				"FECHA_CREACION" => $FECHA_CREACION, 
				"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
				"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
				"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION 
				]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
