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
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

$reportes = $database->select("REPORTES",["[>]AREAS"=>["ID_AREA"=>"ID_AREA"]], ["REPORTES.ID_REPORTE","REPORTES.NOMBRE","REPORTES.ID_USUARIO","REPORTES.COMPARTIDO","REPORTES.FECHA_CREACION","AREAS.ID_AREA","AREAS.NOMBRE(AREA)"] , ["OR"=>["ID_USUARIO"=>$id,"COMPARTIDO"=>1]]);
valida_error_medoo_and_die();
foreach ($reportes as $key=>$value)
{
	$sql = "SELECT  CONCAT(NOMBRE_COLUMNA,'|',TIPO_DATO) as COLUMNA FROM REPORTE_COLUMNAS WHERE ID_REPORTE = ".$value["ID_REPORTE"];
	$columnas = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
	//$columnas = $database->select("REPORTE_COLUMNAS",["ID_REPORTE_COLUMNAS","NOMBRE_COLUMNA","TIPO_DATO"],["ID_REPORTE"=>$value["ID_REPORTE"]]);
	$reportes[$key]["COLUMN"]= $columnas;
}







print_r(json_encode($reportes));
//print_r(json_encode($reportes, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
?>
