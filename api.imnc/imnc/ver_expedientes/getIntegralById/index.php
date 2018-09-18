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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 
////////////////////////////////////////////////////////////////////////////////
//			AQUI BUSCO NOMBRE ETAPAS
////////////////////////////////////////////////////////////////////////////////
$servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$id]); 
////////////////////////////////////////////////////////////////////////////
//Para filtrar segun la cantidad de etapas por la que ha pasado el servicio
////////////////////////////////////////////////////////////////////////////
$id_serv=$servicio_cliente_etapa["ID_SERVICIO"];
$refer=explode('-',$servicio_cliente_etapa["REFERENCIA"]);
//if(count($refer)!=4){
	$limit_et=$database->max("ETAPAS_PROCESO", "ID_ETAPA");
	//$ciclo=0;
//}
//else{
//	$limit_et=$database->get("ETAPAS_PROCESO", "id_etapa", ["ID"=>$refer[3]]);
	//$ciclo=$refer[0];
//}

//////////////////////////////////////////////////
$servicio_nombre_etapa = $database->select("ETAPAS_PROCESO", "*", ["AND"=>["ID_SERVICIO"=>$id_serv,"ID_ETAPA[<=]" => $limit_et]]);
valida_error_medoo_and_die(); 
///////////////////////////////////////////////////////////////////////////////////////////////////////
//				AHORA VOY BUSCAR DATOS SERVICIO
///////////////////////////////////////////////////////////////////////////////////////////////////////

$servicio_nombre = $database->get("SERVICIOS", "*", ["ID"=>$servicio_cliente_etapa["ID_SERVICIO"]]);
$servicio_cliente_etapa["NOMBRE_SERVICIO"] = $servicio_nombre["NOMBRE"];
$servicio_cliente_etapa["CLAVE_SERVICIO"] =  $servicio_nombre["ID"];


$cliente_nombre = $database->get("CLIENTES", "NOMBRE", ["ID"=>$servicio_cliente_etapa["ID_CLIENTE"]]);
$servicio_cliente_etapa["NOMBRE_CLIENTE"] = $cliente_nombre;

$etapa_nombre = $database->get("ETAPAS_PROCESO", "ETAPA", ["ID_ETAPA"=>$servicio_cliente_etapa["ID_ETAPA_PROCESO"]]);
$servicio_cliente_etapa["NOMBRE_ETAPA"] = $etapa_nombre;
valida_error_medoo_and_die(); 
///////////////////////////////////////////////////////////////////////////////////////////////////////
//		AHORA A BUSCAR NOMBRE DE LAS SECCIONES
///////////////////////////////////////////////////////////////////////////////////////////////////////
$respuesta=array();
	$nombre_secciones = $database->select("CATALOGO_SECCIONES", "*", ["ORDER"=>"ID"]);
valida_error_medoo_and_die();
///////////////////////////////////////////////////////////////////////////////////////////////////////
//		AHORA A BUSCAR TODOS DOCUMENTOS
///////////////////////////////////////////////////////////////////////////////////////////////////////
$respuesta=array();
$nombre_etapa = "todas";
$nombre_seccion=$nombre_secciones[0]["NOMBRE_SECCION"];	
if($nombre_etapa == "todas"){
	//$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS", "*",["SECCION"=>$nombre_seccion,"ORDER"=>"ID"]);
	$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS",["[><]ETAPAS_PROCESO"=>["CATALOGO_DOCUMENTOS.ID_ETAPA"=>"ID_ETAPA"],"[><]CATALOGO_SECCIONES"=>["CATALOGO_DOCUMENTOS.ID_SECCION"=>"ID"]],["CATALOGO_DOCUMENTOS.ID","CATALOGO_DOCUMENTOS.NOMBRE","CATALOGO_DOCUMENTOS.DESCRIPCION","ETAPAS_PROCESO.ETAPA","CATALOGO_SECCIONES.NOMBRE_SECCION"] ,["AND"=>["CATALOGO_SECCIONES.NOMBRE_SECCION"=>$nombre_seccion],"ORDER"=>"CATALOGO_DOCUMENTOS.ID"]);
}
else{
	//$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS", "*",["AND"=>["ETAPA"=>$nombre_etapa,"SECCION"=>$nombre_seccion],"ORDER"=>"ID"]);
	$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS",["[><]ETAPAS_PROCESO"=>["CATALOGO_DOCUMENTOS.ID_ETAPA"=>"ID_ETAPA"],"[><]CATALOGO_SECCIONES"=>["CATALOGO_DOCUMENTOS.ID_SECCION"=>"ID"]],["CATALOGO_DOCUMENTOS.ID","CATALOGO_DOCUMENTOS.NOMBRE","CATALOGO_DOCUMENTOS.DESCRIPCION","ETAPAS_PROCESO.ETAPA","CATALOGO_SECCIONES.NOMBRE_SECCION"] ,["AND"=>["ETAPAS_PROCESO.ETAPA"=>$nombre_etapa,"CATALOGO_SECCIONES.NOMBRE_SECCION"=>$nombre_seccion],"ORDER"=>"CATALOGO_DOCUMENTOS.ID"]);
}

for($i=0;$i<count($DOCUMENTOS);$i++){
	$ESTADO_DOCUMENTO = $database->select("BASE_DOCUMENTOS","ESTADO_DOCUMENTO",["AND"=>["ID_CATALOGO_DOCUMENTOS"=>$DOCUMENTOS[$i]["ID"],"ID_SERVICIO"=>$id]]);
	$DOCUMENTOS[$i]["ESTADO"]=$ESTADO_DOCUMENTO;

}

valida_error_medoo_and_die();
///////////////////////////////////////////////////////////////////////////////////////////////////////
$prueba[0]=$servicio_nombre_etapa;
$prueba[1]=$servicio_cliente_etapa;
$prueba[2]=$nombre_secciones;
$prueba[3]=$DOCUMENTOS;

print_r(json_encode($prueba)); 
?> 
