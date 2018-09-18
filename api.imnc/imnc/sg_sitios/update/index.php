<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("SG_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
$ID_SG_TIPO_SERVICIO = $objeto->ID_SG_TIPO_SERVICIO; 
$ID_CLIENTE_DOMICILIO = $objeto->ID_CLIENTE_DOMICILIO; 
$CANTIDAD_PERSONAS = $objeto->CANTIDAD_PERSONAS; 
$CANTIDAD_TURNOS = $objeto->CANTIDAD_TURNOS; 
$NUMERO_TOTAL_EMPLEADOS = $objeto->NUMERO_TOTAL_EMPLEADOS; 
$NUMERO_EMPLEADOS_CERTIFICACION = $objeto->NUMERO_EMPLEADOS_CERTIFICACION; 
$CANTIDAD_DE_PROCESOS = $objeto->CANTIDAD_DE_PROCESOS; 
$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO; 
$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
$NOMBRE_PROCESOS= $objeto->NOMBRE_PROCESOS; 

$ID_ACTIVIDAD = $objeto->ID_ACTIVIDAD;
valida_parametro_and_die($ID_ACTIVIDAD,"Falta Actividad");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");


$validacion = $database->count("SG_SITIOS", ["AND" => ["ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, "ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO]]);
$obj_validar =  $database->get("SG_SITIOS", ["ID_SG_TIPO_SERVICIO", "ID_CLIENTE_DOMICILIO"], ["ID" => $ID]);
if( !($obj_validar["ID_SG_TIPO_SERVICIO"] == $ID_SG_TIPO_SERVICIO && $obj_validar["ID_CLIENTE_DOMICILIO"] == $ID_CLIENTE_DOMICILIO) &&  $validacion > 0 ){
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]= "El sitio ya existe en este servicio.";
	print_r(json_encode($respuesta)); 
	die(); 
}
/*
$sitios_auditoria = $database->select("SG_AUDITORIA_SITIOS", "*", ["ID_SG_SITIO"=>$ID]);
valida_error_medoo_and_die();
foreach ($sitios_auditoria as $key => $sitio) {
	$auditoria = $database->get("SG_AUDITORIAS", "*", ["ID"=>$sitio["ID_SG_AUDITORIA"]]);
	valida_error_medoo_and_die();
	$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$auditoria["ID_SG_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die();
	if($tipo_servicio["ID_TIPO_SERVICIO"] == "CSGA" || $tipo_servicio["ID_TIPO_SERVICIO"] == "CSGC"){
		if (in_array($tipo_servicio["COMPLEJIDAD"], array("alta", "media", "baja", "limitada"))) {
		$complejidad = "_" . strtoupper($tipo_servicio["COMPLEJIDAD"]);
		}
		else{
			$complejidad = "";
		}
		$DIAS = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
						[
							"AND"=>[
										"ID_TIPO_SERVICIO"=>$tipo_servicio["ID_TIPO_SERVICIO"],
										"TOTAL_EMPLEADOS_MINIMO[<=]"=>$NUMERO_TOTAL_EMPLEADOS,
										"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$NUMERO_TOTAL_EMPLEADOS,
									]
		]);
		$DIA_ANT = $sitio["DIAS_AUDITORIAS"];
		$DIAS_AUDITORIA = $auditoria["DURACION_DIAS"] + $DIAS - $DIA_ANT;
		$id_aud = $database->update("SG_AUDITORIAS", [ 
		"DURACION_DIAS" => $DIAS_AUDITORIA, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"HORA_MODIFICACION" => $HORA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
		], ["ID"=>$sitio["ID_SG_AUDITORIA"]]);

		$id = $database->update("SG_AUDITORIA_SITIOS", [
			"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
			"HORA_MODIFICACION" => $HORA_MODIFICACION,
			"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
			"DIAS_AUDITORIAS" => $DIAS
		], ["ID"=>$sitio["ID"]]); 
		$respuesta["dia_auditor"]="ok"; 
	}
}
*/
$id = $database->update("SG_SITIOS", [ 
	"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, 
	"ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO, 
	"CANTIDAD_PERSONAS" => $CANTIDAD_PERSONAS, 
	"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS, 
	"NUMERO_TOTAL_EMPLEADOS" => $NUMERO_TOTAL_EMPLEADOS, 
	"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION, 
	"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS, 
	"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO, 
	"ID_ACTIVIDAD" => $ID_ACTIVIDAD, 
	"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
	"NOMBRE_PROCESOS"=>$NOMBRE_PROCESOS
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
