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
		$mailerror->send("TIPOS_PERFILES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
$PERFIL = $objeto->PERFIL; 
$PERMISOS = $objeto->PERMISOS; 
$DESCRIPCION = $objeto->DESCRIPCION; 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id = $database->insert("TIPOS_PERFILES", [ 
	"ID" => $ID, 
	"PERFIL" => $PERFIL, 
	"PERMISOS" => $PERMISOS, 
	"DESCRIPCION" => $DESCRIPCION, 
	"FECHA_CREACION" => $FECHA_CREACION, 
	"HORA_CREACION" => $HORA_CREACION,  
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 

?> 
