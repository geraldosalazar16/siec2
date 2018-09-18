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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id = $_REQUEST["id"]; 
$servicio = $_REQUEST["servicio"]; 
$respuesta = [];
$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "ID_TIPO_SERVICIO", ["ID"=>$servicio]);

$cotizacion = $database->select("COTIZACIONES_TRAMITES", ["[>]COTIZACIONES" => ["ID_COTIZACION" => "ID"]], "*", 
	["AND" => ["ID_TIPO_SERVICIO"=>$tipo_servicio, "ID_SERVICIO_CLIENTE" => $id ]], ["ORDER" => ["FECHA_CREACION" => "DESC", "HORA_CREACION" => "DESC"]]); 
$cotizacion_id = $database->select("COTIZACIONES_TRAMITES", ["[>]COTIZACIONES" => ["ID_COTIZACION" => "ID"]], "COTIZACIONES_TRAMITES.ID", 
	["AND" => ["ID_TIPO_SERVICIO"=>$tipo_servicio, "ID_SERVICIO_CLIENTE" => $id ]], ["ORDER" => ["FECHA_CREACION" => "DESC", "HORA_CREACION" => "DESC"]]); 
valida_error_medoo_and_die(); 

$complejidad = $cotizacion[0]["COMPLEJIDAD"]; 
$complejidades_validas = array("alta", "media", "baja", "limitada");
if (!in_array($complejidad, $complejidades_validas)) {
	$complejidad = "media";
}
$complejidad = "_" . strtoupper($complejidad);

$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$cotizacion[0]["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die(); 

if($etapa["ETAPA"] != 'Inicial'){
	$respuesta["INICIAL"] = 0;
	$cantidad_de_sitios = $database->count("COTIZACION_SITIOS", ["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
	valida_error_medoo_and_die(); 

	$cotizacion_sitios = $database->select("COTIZACION_SITIOS", "*", ["ID_COTIZACION"=>$cotizacion_id[0]]);
	valida_error_medoo_and_die();

	//Multiplicador para el calculo de sitios
	$const_dias = 1;
	if(strpos($etapa["ETAPA"], 'Vigilancia') !== false){ // Vigilancia
		$const_dias = 0.33;
	}
	else if(strpos($etapa["ETAPA"], 'Renovación') !== false || strpos($etapa["ETAPA"], 'Renovacion') !== false){ // Renovacion
		$const_dias = 0.66;
	}
	$total_dias_auditoria = 0;
	for ($i=0; $i < count($cotizacion_sitios) ; $i++) { 
		$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
			[
				"AND"=>[
							"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
							"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
							"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
						]
			]);
		$dias_reduccion = ceil($dias * (1 - ($cotizacion_sitios[$i]["FACTOR_REDUCCION"]/100) 
			+ ($cotizacion_sitios[$i]["FACTOR_AMPLIACION"]/100)) );
		$dias_subtotal = ceil($dias_reduccion * $const_dias);

		if ($cotizacion_sitios[$i]["SELECCIONADO"] != 1) {
			continue;
		}
		$total_dias_auditoria += $dias_subtotal;
	}
	$respuesta["DIAS"] = $total_dias_auditoria;
	if($cotizacion[0]["SG_INTEGRAL"] == "si"){
		$total_dias_auditoria = ceil($total_dias_auditoria * (1 - ($cotizacion[0]["FACTOR_INTEGRACION"]/100)) );
		$respuesta["DIAS"] = $total_dias_auditoria;
	}
}
else{
	$respuesta["INICIAL"] = 1;
	$respuesta["DIAS"] = 0;
}

print_r(json_encode($respuesta)); 
?> 
