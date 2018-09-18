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
		$mailerror->send("SG_AUDITORIA_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
$ID_SG_SITIO = $objeto->ID_SG_SITIO; 

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
/*
$auditoria = $database->get("SG_AUDITORIAS", "*", ["ID"=>$ID_SG_AUDITORIA]);
valida_error_medoo_and_die();
$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$auditoria["ID_SG_TIPO_SERVICIO"]]);
valida_error_medoo_and_die();
if($tipo_servicio["ID_TIPO_SERVICIO"] == "CSGA" || $tipo_servicio["ID_TIPO_SERVICIO"] == "CSGC"){
	if (in_array($tipo_servicio["COMPLEJIDAD"], array("alta", "media", "baja", "limitada"))) {
	$complejidad = "DIAS_AUDITORIA_".strtoupper($tipo_servicio["COMPLEJIDAD"]);
	}
	else{
		$complejidad = "DIAS_AUDITORIA";
	}
	$sitio = $database->get("SG_SITIOS", "*", ["ID"=>$ID_SG_SITIO]);
	valida_error_medoo_and_die(); 
	$DIAS = $database->get("COTIZACION_EMPLEADOS_DIAS", $complejidad , 
					[
						"AND"=>[
									"ID_TIPO_SERVICIO"=>$tipo_servicio["ID_TIPO_SERVICIO"],
									"TOTAL_EMPLEADOS_MINIMO[<=]"=>$sitio["NUMERO_TOTAL_EMPLEADOS"],
									"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$sitio["NUMERO_TOTAL_EMPLEADOS"],
								]
	]);
	$DIAS_AUDITORIA = $auditoria["DURACION_DIAS"] + $DIAS;
	$id_aud = $database->update("SG_AUDITORIAS", [ 
	"DURACION_DIAS" => $DIAS_AUDITORIA, 
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	], ["ID"=>$ID_SG_AUDITORIA]);
}
else{
	$DIAS = 0;
}*/

$id = $database->insert("SG_AUDITORIA_SITIOS", [ 
	"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
	"ID_SG_SITIO" => $ID_SG_SITIO, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id; 
print_r(json_encode($respuesta)); 
?> 
