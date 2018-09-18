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



$respuesta=array();
$listawhere = "";
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP


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
if(isset($objeto->ETAPA)){
	$ETAPA = $objeto->ETAPA;
}else{
	$ETAPA = "";
}
if(isset($objeto->SECCION)){
	$SECCION = $objeto->SECCION;
}else{
	$SECCION = "";
}
if(isset($objeto->ESTADO)){
	$ESTADO = $objeto->ESTADO;
}else{
	$ESTADO = "";
}

if ($DOCUMENTO != "") {
//	$listawhere .= " AND CATALOGO_DOCUMENTOS.ID =".$DOCUMENTO." ";
	$listawhere .= " AND CAT_NOMBRE_DOCUMENTOS.ID =".$DOCUMENTO." ";
}
if ($CLIENTE != "") {
	
		$listawhere .= " AND SERVICIO_CLIENTE_ETAPA.ID_CLIENTE =".$CLIENTE." ";
}
if ($SERVICIO != "") {
	
		$listawhere .= " AND SERVICIO_CLIENTE_ETAPA.ID_SERVICIO ='".$SERVICIO."' ";
}
if ($ETAPA != "") {
	
		$listawhere .= " AND SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO =".$ETAPA." ";
}
if ($SECCION != "") {
	$listawhere .= " AND CATALOGO_DOCUMENTOS.ID_SECCION =".$SECCION." ";
}
if ($ESTADO != "") {
	$listawhere .= " AND BASE_DOCUMENTOS.ESTADO_DOCUMENTO ='".$ESTADO."' ";
}


$resp	=	$database->select("SERVICIO_CLIENTE_ETAPA",["ID","REFERENCIA"]);
for($i=0;$i<count($resp);$i++){
	
		$AA	=	explode("-",$resp[$i]["REFERENCIA"]);
		$ciclo	=	substr($AA[0],1);
		if(settype($ciclo,"integer")!=true)
			$ciclo="";
		
	
	
	$strQuery	=	"SELECT
						SERVICIO_CLIENTE_ETAPA.ID,
						( CASE 
						CATALOGO_DOCUMENTOS.NOMBRE 
                        WHEN 0
                        THEN CATALOGO_DOCUMENTOS.NOMBRE
                        ELSE 'No se asignado documentos a esta etapa'
                        END) AS NOMBRE_DOCUMENTO ,
						SERVICIOS.NOMBRE AS NOMBRE_SERVICIO,
						CLIENTES.NOMBRE AS NOMBRE_CLIENTE,
						BASE_DOCUMENTOS.CICLO,
						ETAPAS_PROCESO.ETAPA AS NOMBRE_ETAPA,
						CATALOGO_SECCIONES.NOMBRE_SECCION,
						BASE_DOCUMENTOS.ESTADO_DOCUMENTO
    
    
				FROM 
						SERVICIO_CLIENTE_ETAPA 
						LEFT OUTER JOIN CATALOGO_DOCUMENTOS ON SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO = CATALOGO_DOCUMENTOS.ID_ETAPA
						LEFT OUTER JOIN SERVICIOS ON ID_SERVICIO = SERVICIOS.ID  
						LEFT OUTER JOIN CLIENTES ON ID_CLIENTE = CLIENTES.ID
						LEFT OUTER JOIN ETAPAS_PROCESO ON ID_ETAPA_PROCESO = ETAPAS_PROCESO.ID_ETAPA
						LEFT OUTER JOIN CATALOGO_SECCIONES ON CATALOGO_DOCUMENTOS.ID_SECCION = CATALOGO_SECCIONES.ID
						LEFT OUTER JOIN CAT_NOMBRE_DOCUMENTOS ON CATALOGO_DOCUMENTOS.NOMBRE = CAT_NOMBRE_DOCUMENTOS.NOMBRE
						LEFT OUTER JOIN 
							BASE_DOCUMENTOS ON CATALOGO_DOCUMENTOS.ID = BASE_DOCUMENTOS.ID_CATALOGO_DOCUMENTOS AND SERVICIO_CLIENTE_ETAPA.ID = BASE_DOCUMENTOS.ID_SERVICIO AND BASE_DOCUMENTOS.CICLO = ".$ciclo."
						WHERE	SERVICIO_CLIENTE_ETAPA.ID	=	".$resp[$i]["ID"].$listawhere."	 
						ORDER BY SERVICIO_CLIENTE_ETAPA.ID";
	$tareas[$i]	=	$database->query($strQuery)->fetchAll(PDO::FETCH_ASSOC);					
		
}

print_r(json_encode($tareas)); 
?> 
