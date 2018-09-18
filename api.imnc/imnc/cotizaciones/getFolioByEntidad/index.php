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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id = $_REQUEST["id"]; 
$complejidad = $_REQUEST["entidad"];


$query = "SELECT * FROM COTIZACIONES WHERE ID_PROSPECTO = ".$id;
$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
for ($i=0; $i < count($respuesta); $i++) { 
	$CONSECUTIVO = str_pad("".$respuesta[$i]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $respuesta[$i]["FOLIO_INICIALES"].$respuesta[$i]["FOLIO_SERVICIO"].$CONSECUTIVO.$respuesta[$i]["FOLIO_MES"].$respuesta[$i]["FOLIO_YEAR"];
	if( !is_null($respuesta[$i]["FOLIO_UPDATE"]) && $respuesta[$i]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$respuesta[$i]["FOLIO_UPDATE"];
	}
	$respuesta[$i]["FOLIO"] = $FOLIO;
}

print_r(json_encode($respuesta)); 
?> 
