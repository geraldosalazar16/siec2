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
$servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$id]); 

$servicio_nombre = $database->get("SERVICIOS", "*", ["ID"=>$servicio_cliente_etapa["ID_SERVICIO"]]);
$servicio_cliente_etapa["NOMBRE_SERVICIO"] = $servicio_nombre["NOMBRE"];
$servicio_cliente_etapa["CLAVE_SERVICIO"] =  $servicio_nombre["ID"];


$cliente_nombre = $database->get("CLIENTES", "NOMBRE", ["ID"=>$servicio_cliente_etapa["ID_CLIENTE"]]);
$servicio_cliente_etapa["NOMBRE_CLIENTE"] = $cliente_nombre;

if($servicio_cliente_etapa["ID_SERVICIO"] == 3)
{
    $sce_curso = $database->get("SCE_CURSOS", ["ID_CURSO","CANTIDAD_PARTICIPANTES","URL_PARTICIPANTES"], ["ID_SCE"=>$servicio_cliente_etapa["ID"]]);
    $curso = $database->get("CURSOS", ["ID_CURSO","NOMBRE"], ["ID_CURSO"=>$sce_curso["ID_CURSO"]]);
    $servicio_cliente_etapa["NOMBRE_CURSO"] = $curso["NOMBRE"];
    $servicio_cliente_etapa["ID_CURSO"] = $curso["ID_CURSO"];
    $servicio_cliente_etapa["CANTIDAD_PARTICIPANTES"] = $sce_curso["CANTIDAD_PARTICIPANTES"];

}



//$id_tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "ID_TIPO_SERVICIO", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id]);
//$servicio_cliente_etapa["ID_TIPO_SERVICIO"] = $id_tipo_servicio;
/*
if ($_REQUEST["domicilios"] == "true") {
	$domicilios_cliente = $database->select("CLIENTES_DOMICILIOS", "*", ["ID_CLIENTE"=>$servicio_cliente_etapa["ID_CLIENTE"]]); 
	valida_error_medoo_and_die(); 
	$servicio_cliente_etapa["DOMICILIOS_CLIENTE"] = $domicilios_cliente;
}
*/
$normas = $database->query("SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`= ".$id)->fetchAll(PDO::FETCH_ASSOC);
$servicio_cliente_etapa["NORMAS"] = $normas;
$etapa_nombre = $database->get("ETAPAS_PROCESO", "ETAPA", ["ID_ETAPA"=>$servicio_cliente_etapa["ID_ETAPA_PROCESO"]]);
$servicio_cliente_etapa["NOMBRE_ETAPA"] = $etapa_nombre;
$desde_cotizacion = $database->count("COTIZACIONES_TRAMITES", ["ID_SERVICIO_CLIENTE"=>$id]); 
$servicio_cliente_etapa["COTIZACION"] = $desde_cotizacion;
valida_error_medoo_and_die(); 
print_r(json_encode($servicio_cliente_etapa)); 
?> 
