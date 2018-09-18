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
		$mailerror->send("SG_AUDITORIA_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
$ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
$ID_SG_SITIO = $objeto->ID_SG_SITIO; 

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

/*
$auditoria = $database->get("SG_AUDITORIAS", "*", ["ID"=>$ID_SG_AUDITORIA]);
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
	$sitio = $database->get("SG_SITIOS", "*", ["ID"=>$ID_SG_SITIO]);
	valida_error_medoo_and_die(); 
	$DIAS = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
					[
						"AND"=>[
									"ID_TIPO_SERVICIO"=>$tipo_servicio["ID_TIPO_SERVICIO"],
									"TOTAL_EMPLEADOS_MINIMO[<=]"=>$sitio["NUMERO_TOTAL_EMPLEADOS"],
									"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$sitio["NUMERO_TOTAL_EMPLEADOS"],
								]
	]);
	$sitio_auditoria = $database->get("SG_AUDITORIA_SITIOS", "*", ["ID"=>$ID]);
	valida_error_medoo_and_die();
	$DIA_ANT = $sitio_auditoria["DIAS_AUDITORIAS"];
	$DIAS_AUDITORIA = $auditoria["DURACION_DIAS"] + $DIAS - $DIA_ANT;
	$id_aud = $database->update("SG_AUDITORIAS", [ 
	"DURACION_DIAS" => $DIAS_AUDITORIA, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	], ["ID"=>$ID_SG_AUDITORIA]);
}

else{
	$DIAS = 0;
}
*/
$id = $database->update("SG_AUDITORIA_SITIOS", [ 
	"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
	"ID_SG_SITIO" => $ID_SG_SITIO, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
