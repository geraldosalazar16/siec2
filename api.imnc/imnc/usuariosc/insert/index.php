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


$NOMBRE = $objeto->NOMBRE; 
$USUARIO = $objeto->USUARIO; 
$EMAIL = $objeto->EMAIL; 
$PASSWORD = $objeto->PASSWORD; 
$ID_PERFIL = "ADM1"; 


$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$MODULOS = json_decode(json_encode($objeto->MODULOS), True);


$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");


$id = $database->insert("USUARIOS", [ 
	"NOMBRE" => $NOMBRE, 
	"USUARIO" => $USUARIO, 
	"EMAIL" => $EMAIL, 
	"PASSWORD" => $PASSWORD, 
	"ID_PERFIL" => $ID_PERFIL,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION,
	"FECHA_MODIFICACION" => $FECHA_CREACION,
	"HORA_MODIFICACION" => $HORA_CREACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_CREACION
]); 

valida_error_medoo_and_die(); 
$FECHA_CREACION = date('Y/m/d H:i:s');
$modulos_list = $database->select("MODULOS","*");

for($i = 0 ; $i < sizeof($modulos_list); $i++){

$id_perfil_modulo = $database->insert("PERFIL_MODULO_USUARIO", [ 
	"ID_PERFIL" => $MODULOS[$i]["ID"], 
	"ID_USUARIO" => $id, 
	"ID_MODULO" => $modulos_list[$i]["ID"], 
	"FECHA_CREACION" => $FECHA_CREACION,
	"FECHA_MODIFICACION" => $FECHA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_CREACION
]); 
valida_error_medoo_and_die(); 
}
$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 

?> 
