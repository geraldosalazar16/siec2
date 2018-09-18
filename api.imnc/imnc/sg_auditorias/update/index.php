<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

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
		$mailerror->send("SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 
$ID = $objeto->ID; 

$ID_SG_TIPO_SERVICIO = $objeto->ID_SG_TIPO_SERVICIO; 
// $FECHA_INICIO = $objeto->FECHA_INICIO; 
// valida_parametro_and_die($FECHA_INICIO, "Es necesario capturar una fecha de inicio");
// if (strlen($FECHA_INICIO) != 8) {
// 	imprime_error_and_die("Verifica el formato de la fecha de inicio");
// }
// $anhio = intval(substr($FECHA_INICIO,0,4));
// $mes = intval(substr($FECHA_INICIO,4,2));
// $dia = intval(substr($FECHA_INICIO,6,2));
// if (!checkdate($mes , $dia, $anhio)){
// 	imprime_error_and_die("La fecha de inicio no es válida");
// }

$DURACION_DIAS = $objeto->DURACION_DIAS;
$SITIOS_AUDITAR = $objeto->SITIOS_AUDITAR; 
$NO_USA_METODO = $objeto->NO_USA_METODO; 
if($NO_USA_METODO){
	//valida_parametro_and_die($DURACION_DIAS, "Es necesario capturar una duración en días");
	valida_parametro_and_die($SITIOS_AUDITAR, "Es necesario capturar el número de sitios a auditar");
}

$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Es necesario seleccionar un tipo de auditoría");

$STATUS_AUDITORIA = $objeto->STATUS_AUDITORIA; 
valida_parametro_and_die($STATUS_AUDITORIA, "Es necesario seleccionar un status de auditoría");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("SG_AUDITORIAS", [ 
	"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, 
	"FECHA_INICIO" => $FECHA_INICIO, 
	"DURACION_DIAS" => $DURACION_DIAS,
	"SITIOS_AUDITAR" => $SITIOS_AUDITAR, 
	"NO_USA_METODO" => $NO_USA_METODO, 
	"TIPO_AUDITORIA" => $TIPO_AUDITORIA, 
	"STATUS_AUDITORIA" => $STATUS_AUDITORIA, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
