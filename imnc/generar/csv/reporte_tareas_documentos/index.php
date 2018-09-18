<?php 

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=Reporte_tareas_documentos.csv");

include  '../../../../api.imnc/imnc/common/conn-apiserver.php'; 
include  '../../../../api.imnc/imnc/common/conn-medoo.php'; 
include  '../../../../api.imnc/imnc/common/conn-sendgrid.php'; 
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
		$mailerror->send("TAREAS_DOCUMENTO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$INNERJOIN = [
				"[><]CATALOGO_DOCUMENTOS"=>["TAREAS_DOCUMENTO.ID_CATALOGO_DOCUMENTOS"=>"ID"],
				"[><]SERVICIO_CLIENTE_ETAPA"=>["TAREAS_DOCUMENTO.ID_SERVICIO"=>"ID"],
				"[><]ETAPAS_PROCESO"=>["CATALOGO_DOCUMENTOS.ID_ETAPA"=>"ID_ETAPA"],
				"[><]CATALOGO_SECCIONES"=>["CATALOGO_DOCUMENTOS.ID_SECCION"=>"ID"],
				"[><]SERVICIOS"=>["SERVICIO_CLIENTE_ETAPA.ID_SERVICIO"=>"ID"],
				"[><]CLIENTES"=>["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE"=>"ID"]
				];
				
$COLUMNAS = [
			
			"TAREAS_DOCUMENTO.NOMBRE_TAREA",
			"CATALOGO_DOCUMENTOS.NOMBRE(NOMBRE_DOCUMENTO)",
			"CLIENTES.NOMBRE(NOMBRE_CLIENTE)",
			"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
			"TAREAS_DOCUMENTO.CICLO",
			"ETAPAS_PROCESO.ETAPA(NOMBRE_ETAPA)",
			"CATALOGO_SECCIONES.NOMBRE_SECCION",
			"TAREAS_DOCUMENTO.FECHA_FIN",
			"TAREAS_DOCUMENTO.HORA_FIN",
			"TAREAS_DOCUMENTO.ESTADO"
			
			
			
			

];

$order=["ORDER"=>"TAREAS_DOCUMENTO.ESTADO"];

///////////////////////////////////////////////////////////


$tareas = $database->select("TAREAS_DOCUMENTO",$INNERJOIN,$COLUMNAS,$order);
/////////////////////////////////////////////////////////////////////////////////////////

$csv	=	"Nombre Tarea;Nombre Documento;Cliente;Servicio;Ciclo;Etapa;Seccion;Fecha Final;Estado\r\n";

for($i=0;$i<count($tareas);$i++){
	$csv	.=	$tareas[$i]["NOMBRE_TAREA"].";";
	$csv	.=	$tareas[$i]["NOMBRE_DOCUMENTO"].";";
	$csv	.=	$tareas[$i]["NOMBRE_CLIENTE"].";";
	$csv	.=	$tareas[$i]["NOMBRE_SERVICIO"].";";
	$csv	.=	$tareas[$i]["CICLO"].";";
	$csv	.=	$tareas[$i]["NOMBRE_ETAPA"].";";
	$csv	.=	$tareas[$i]["NOMBRE_SECCION"].";";
	$csv	.=	$tareas[$i]["FECHA_FIN"]." ".$tareas[$i]["HORA_FIN"].";";
	if($tareas[$i]["ESTADO"] == "-1")
		$csv	.=	"Incumplida\r\n";
	if($tareas[$i]["ESTADO"] == "0")
		$csv	.=	"En Tiempo\r\n";
	if($tareas[$i]["ESTADO"] == "1")
		$csv	.=	"Cumplida\r\n";	
	
}


print_r($csv);
/////////////////////////////////////////////////////////////////////////////////////////
 
?> 
