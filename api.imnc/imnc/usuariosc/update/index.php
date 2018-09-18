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
$NOMBRE = $objeto->NOMBRE; 
$USUARIO = $objeto->USUARIO; 
$EMAIL = $objeto->EMAIL; 
$PASSWORD = $objeto->PASSWORD; 

if ($PASSWORD == "") {
	$PASSWORD = $database->get("USUARIOS", "PASSWORD", ["ID"=>$ID]);
}

$ID_PERFIL = "ADM1";  
$MODULOS = json_decode(json_encode($objeto->MODULOS), True);


$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("USUARIOS", [ 
	"NOMBRE" => $NOMBRE, 
	"USUARIO" => $USUARIO, 
	"EMAIL" => $EMAIL, 
	"PASSWORD" => $PASSWORD, 
	"ID_PERFIL" => $ID_PERFIL,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
],["ID"=>$ID]); 
valida_error_medoo_and_die(); 

$FECHA_MODIFICACION = date('Y/m/d H:i:s');
$modulos_list = $database->select("MODULOS","*");
for($i = 0 ; $i < sizeof($modulos_list); $i++){
$index = $modulos_list[$i]["ID"];
print_r($index);
echo "-_-";
	if(isset($MODULOS[$index - 1])){
		print_r($MODULOS[$index - 1]);
		echo ":";
$id_perfil_modulo = $database->update("PERFIL_MODULO_USUARIO", [ 
	"ID_PERFIL" => $MODULOS[$index - 1]["ID"], 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION],
	["AND" => ["ID_USUARIO" => $ID, 
	"ID_MODULO" => $index]]
); 
valida_error_medoo_and_die();
	}
}


$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
