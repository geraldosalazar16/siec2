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
				"[><]CLIENTES"=>["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE"=>"ID"],
				////////////////////////////////////////////////////////////////////////
				"[><]CAT_NOMBRE_DOCUMENTOS"=>["CATALOGO_DOCUMENTOS.NOMBRE"=>"NOMBRE"],
				////////////////////////////////////////////////////////////////////////
				];
				
$COLUMNAS = [
			"TAREAS_DOCUMENTO.ID",
			"TAREAS_DOCUMENTO.ID_SERVICIO",
			"TAREAS_DOCUMENTO.ID_CATALOGO_DOCUMENTOS",
			"TAREAS_DOCUMENTO.NOMBRE_TAREA",
			"TAREAS_DOCUMENTO.CICLO",
			"TAREAS_DOCUMENTO.ESTADO",
			"TAREAS_DOCUMENTO.FECHA_INICIO",
			"TAREAS_DOCUMENTO.HORA_INICIO",
			"TAREAS_DOCUMENTO.FECHA_FIN",
			"TAREAS_DOCUMENTO.HORA_FIN",
			"CATALOGO_DOCUMENTOS.NOMBRE(NOMBRE_DOCUMENTO)",
			"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
			"CLIENTES.NOMBRE(NOMBRE_CLIENTE)",
			"ETAPAS_PROCESO.ETAPA(NOMBRE_ETAPA)",
			"CATALOGO_SECCIONES.NOMBRE_SECCION"

];

$respuesta=array();
$lista_where = [];
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

if(isset($objeto->NOMBRE_TAREA)){
	$NOMBRE_TAREA = $objeto->NOMBRE_TAREA;
}else{
	$NOMBRE_TAREA = "";
}
if(isset($objeto->DOCUMENTO)){
	$DOCUMENTO = $objeto->DOCUMENTO;
	
}else{
	$DOCUMENTO = "";
}
if(isset($objeto->CLIENTE)){
	$CLIENTE = $objeto->CLIENTE;
}else{
	$CLIENTE = "";
}
if(isset($objeto->SERVICIO)){
	$SERVICIO = $objeto->SERVICIO;
}else{
	$SERVICIO = "";
}
if(isset($objeto->ESTADO)){
	$ESTADO = $objeto->ESTADO;
}else{
	$ESTADO = "";
}


///////////////////////////////////////////////////////////
if(!empty($NOMBRE_TAREA)){
	$lista_where["TAREAS_DOCUMENTO.NOMBRE_TAREA[=]"] = $NOMBRE_TAREA;
}
if(!empty($DOCUMENTO)){
//	$lista_where["TAREAS_DOCUMENTO.ID_CATALOGO_DOCUMENTOS[=]"] = $DOCUMENTO;
	$lista_where["CAT_NOMBRE_DOCUMENTOS.ID[=]"] = $DOCUMENTO;
}
if(!empty($CLIENTE)){
	$lista_where["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE[=]"] = $CLIENTE;
}
if(!empty($SERVICIO)){
	$lista_where["SERVICIO_CLIENTE_ETAPA.ID_SERVICIO[=]"] = $SERVICIO;
}
if(!empty($ESTADO)){
	$lista_where["TAREAS_DOCUMENTO.ESTADO"] = $ESTADO-2;
}
$order=["ORDER"=>"TAREAS_DOCUMENTO.ESTADO"];
if(count($lista_where) > 0){
	$lista_where = ["AND" => $lista_where,"ORDER"=>"TAREAS_DOCUMENTO.ESTADO"];
}else{
		$lista_where=$order;
}
///////////////////////////////////////////////////////////


$tareas = $database->select("TAREAS_DOCUMENTO",$INNERJOIN,$COLUMNAS,$lista_where);

valida_error_medoo_and_die(); 
$tareas["FECHA_ACTUAL"]=date('Y-m-d H:i:s');
//print_r(json_encode($COMPARACION_Y_ORDER)); 
print_r(json_encode($tareas)); 
?> 
