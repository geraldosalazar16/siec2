<?php 
// error_reporting(E_ALL);
// ini_set("display_errors",1);

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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("CODIGOS_POSTALES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}


$cp = $_REQUEST["cp"];
$respuesta=array(); 

$strSQL = <<< EOT
SELECT DISTINCT COLONIA_BARRIO 
FROM CODIGOS_POSTALES 
WHERE CP = '$cp' 
EOT;

$sectores = $database->query($strSQL); 
valida_error_medoo_and_die(); 
$sectores = $sectores->fetchAll();
// $sectores = $database->select("CODIGOS_POSTALES", ["CP"], ["CP[~]"=>$busqueda."%"]); 

print_r(json_encode($sectores)); 
?> 
