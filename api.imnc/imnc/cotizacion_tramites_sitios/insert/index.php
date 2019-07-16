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
		$mailerror->send("COTIZACION_TRAMITES_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_TRAMITE = $objeto->ID_TRAMITE; 
$ID_COTIZACION = $objeto->ID_COTIZACION;
$ID_SITIO = $objeto->ID_SITIO;
//$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
//valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

// Para obtener el ID_SITIO de la tabla cotizacion sitios a partir del ID_DOMICILIO
//$ID_SITIO = $database->get("COTIZACION_SITIOS","ID",["AND"=>["ID_DOMICILIO_SITIO"=>$ID_DOMICILIO_SITIO,"ID_COTIZACION"=>$ID_COTIZACION]]);
//valida_error_medoo_and_die(); 
//CONTAR SI EXISTEN SITIOS CON ESTOS DATOS
$SITIO_COUNT =  $database->query("
			SELECT COUNT(*) AS COUNT_SITIOS
			FROM COTIZACION_TRAMITES_SITIOS AS CTS
			JOIN COTIZACION_SITIOS AS CS ON CTS.ID_SITIO = CS.ID
			WHERE  CTS.ID_SITIO = ". $database->quote($ID_SITIO)." AND CTS.ID_TRAMITE =". $database->quote($ID_TRAMITE)." AND CS.ID_COTIZACION = ".$database->quote($ID_COTIZACION))->fetchAll(PDO::FETCH_ASSOC);

		valida_error_medoo_and_die(); 

if($SITIO_COUNT[0]["COUNT_SITIOS"]==0){
	$id = $database->insert("COTIZACION_TRAMITES_SITIOS", [ 
		"ID_TRAMITE" => $ID_TRAMITE, 
		"ID_SITIO" => $ID_SITIO, 
		"SELECCIONADO"=> 0,
		"FECHA_CREACION" => $FECHA_CREACION,
		//"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
	]); 

	valida_error_medoo_and_die(); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
}
else{
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]="Este domicilio ya esta cargado para este tramite"; 
}

print_r(json_encode($respuesta)); 
?> 
