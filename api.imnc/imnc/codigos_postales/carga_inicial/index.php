<?php  
error_reporting(E_ALL);
ini_set("display_errors",1);

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

ini_set('memory_limit', '1024M');

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
		$mailerror->send("CODIGOS_POSTALES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 


$myfile = fopen("CPdescarga.txt", "r") or die("Unable to open file!");
while(!feof($myfile)) {
	$campos = explode("|", fgets($myfile));
	$fila = array();
	$fila["CP"] = $campos[0];
	$fila["COLONIA_BARRIO"] = $campos[1];
	$fila["DELEGACION_MUNICIPIO"] = $campos[3];
	$fila["ENTIDAD_FEDERATIVA"] = $campos[4];

	$id = $database->insert("CODIGOS_POSTALES", [ 
		"CP" => $fila["CP"], 
		"COLONIA_BARRIO" => $fila["COLONIA_BARRIO"], 
		"DELEGACION_MUNICIPIO" => $fila["DELEGACION_MUNICIPIO"], 
		"ENTIDAD_FEDERATIVA" => $fila["ENTIDAD_FEDERATIVA"], 
	]); 
	valida_error_medoo_and_die(); 
}
fclose($myfile);	
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
