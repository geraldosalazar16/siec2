<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include './funciones.php';

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
$id_cotizacion = $_REQUEST["cotizacion"]; 

$query = "SELECT * 
FROM TABLA_ENTIDADES,COTIZACIONES 
WHERE 
ID_PROSPECTO = ID_VISTA 
AND BANDERA_VISTA = BANDERA 
AND ID =".$database->quote($id_cotizacion);

$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die();
$cotizacio_tramite = $database->get("COTIZACIONES_TRAMITES_CIL", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 

$complejidad = $cotizacion[0]["COMPLEJIDAD"]; 
$complejidades_validas = array("alta", "media", "baja", "limitada");
if (!in_array($complejidad, $complejidades_validas)) {
	$complejidad = "media";
}
$complejidad = "_" . strtoupper($complejidad);


$servicio = $database->get("SERVICIOS", "*", ["ID"=>$cotizacion[0]["ID_SERVICIO"]]);
valida_error_medoo_and_die(); 
$tipos_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$cotizacion[0]["ID_TIPO_SERVICIO"]]);
valida_error_medoo_and_die(); 
$normas = $database->select("COTIZACION_NORMAS", "*", ["ID_COTIZACION"=>$id_cotizacion]);
valida_error_medoo_and_die();
//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$cotizacio_tramite["ID_TIPO_AUDITORIA"]]);
valida_error_medoo_and_die(); 
//Sustituyo $etapa["ETAPA"] por nombre_auditoria
$nombre_auditoria = $etapa["TIPO"];
$tarifa = $database->get("TARIFA_COTIZACION", "*", ["ID"=>$cotizacion[0]["TARIFA"]]);
valida_error_medoo_and_die(); 
$cantidad_de_sitios = $database->count("COTIZACION_SITIOS_CIL", ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die(); 

$cotizacion_detalles = $database->select("COTIZACION_DETALLES","*", [
				"ID_COTIZACION" => $id_cotizacion,
				]);
			valida_error_medoo_and_die();
			$cotizacion[0]["DETALLES"] = $cotizacion_detalles;


$campos = [
	"COTIZACION_SITIOS_CIL.ID",
	"COTIZACION_SITIOS_CIL.ID_COTIZACION",
	"COTIZACION_SITIOS_CIL.ID_DOMICILIO_SITIO",
	"COTIZACION_SITIOS_CIL.CANTIDAD_PERSONAS",
	"COTIZACION_SITIOS_CIL.TEMPORAL_O_FIJO",
	"COTIZACION_SITIOS_CIL.MATRIZ_PRINCIPAL",
	"COTIZACION_SITIOS_CIL.SELECCIONADO",
//	"COTIZACION_SITIOS_CIL.FACTOR_REDUCCION",
//	"COTIZACION_SITIOS_CIL.FACTOR_AMPLIACION",
	"COTIZACION_SITIOS_CIL.JUSTIFICACION",
	
];

if($cotizacion[0]["BANDERA"] == 0){
	$total_domicilios = $database->count("PROSPECTO_DOMICILIO", ["ID_PROSPECTO"=>$cotizacion[0]["ID_PROSPECTO"]]); 
	array_push($campos, "PROSPECTO_DOMICILIO.NOMBRE");
	$tabla_entidad = "PROSPECTO_DOMICILIO";
}
else if($cotizacion[0]["BANDERA"] != 0){
	$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
	array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
	$tabla_entidad = "CLIENTES_DOMICILIOS";
}

$cotizacion_sitios = $database->select("COTIZACION_SITIOS_CIL", ["[>]".$tabla_entidad => ["ID_DOMICILIO_SITIO" => "ID"]], $campos, ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die();

$campos_t = [
	"COTIZACION_TARIFA_ADICIONAL.ID",
	"COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE",
	"COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL",
	"COTIZACION_TARIFA_ADICIONAL.CANTIDAD",
	"TARIFA_COTIZACION_ADICIONAL.DESCRIPCION",
	"TARIFA_COTIZACION_ADICIONAL.TARIFA"
];

$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
	$campos_t, ["ID_TRAMITE"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die();


//Multiplicador para el calculo de sitios
$const_sitio = 1; //Default - Etapa certificacion 
$const_dias = 1;
if(strpos($nombre_auditoria, 'Vigilancia') !== false || strpos($nombre_auditoria, 'VIGILANCIA') !== false){ // Vigilancia
//	$const_sitio = 0.6;
//	$const_dias = 0.33;
}
else if(strpos($nombre_auditoria, 'Renovación') !== false || strpos($nombre_auditoria, 'Renovacion') !== false || strpos($nombre_auditoria, 'RENOVACION') !== false){ // Renovacion
//	$const_sitio = 0.8;
//	$const_dias = 0.66;
}
$obj_cotizacion = [];
$obj_cotizacion["TIPOS_SERVICIO"] = $tipos_servicio;
$obj_cotizacion["ETAPA"] = $nombre_auditoria;
$obj_cotizacion["TARIFA_TOTAL"] = $tarifa;

$obj_cotizacion["COUNT_SITIOS"] = count_sitios($id, $const_sitio);
$obj_cotizacion["COTIZACION_SITIOS"] = $cotizacion_sitios;
$obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"] = $cotizacion_tarifa_adicional;

//----- RESTRICCIONES -------
$obj_cotizacion["RESTRICCIONES"] = array(); 

if ($obj_cotizacion["COUNT_SITIOS"]["TOTAL_SITIOS"] <= 0) {
	array_push($obj_cotizacion["RESTRICCIONES"], "Es necesario agregar sitios para obtener una cotización");
}

if ($obj_cotizacion["COUNT_SITIOS"]["TOTAL_SITIOS"] < $obj_cotizacion["COUNT_SITIOS"]["SITIOS_A_VISITAR"]) {
	array_push($obj_cotizacion["RESTRICCIONES"], "Los sitios seleccionados deben ser por lo menos " . $obj_cotizacion["COUNT_SITIOS"]["SITIOS_A_VISITAR"]);
}
//---- FIN: RESTRICCIONES -------
	$total_tarifa_adicional = 0;
	for ($i=0; $i < count($obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"]); $i++) { 
		$subtotal = $obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["TARIFA"] * $obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["CANTIDAD"];
		$obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["SUBTOTAL"] = $subtotal;
		$total_tarifa_adicional += $subtotal;
	}

	$total_dias_auditoria = 0;
	$total_empleados = 0;
	$dias = 0;
	for ($i=0; $i < count($obj_cotizacion["COTIZACION_SITIOS"]) ; $i++) {
		 
		if ($obj_cotizacion["COTIZACION_SITIOS"][$i]["SELECCIONADO"] == 1) {
				$total_empleados +=$obj_cotizacion["COTIZACION_SITIOS"][$i]["CANTIDAD_PERSONAS"];
		}
		/*
			
		
		$dias_reduccion = round($dias * (1 - ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_REDUCCION"]/100) 
			+ ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_AMPLIACION"]/100)) );
		$dias_subtotal = round($dias_reduccion * $const_dias);
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA"] = $dias;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_ENCUESTA"] = $dias_encuesta;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_RED"] = $dias_reduccion;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_SUBTOTAL"] = $dias_subtotal;
		//Saber si es etapa 1 para modificar la visa
		$es_etapa_1 = (strpos($nombre_auditoria, 'Etapa 1') || strpos($nombre_auditoria, 'ETAPA 1'));
		if($es_etapa_1 !== false){ 
			$obj_cotizacion["COTIZACION_SITIOS"][$i]["ETAPA"] = 'E1';
		} else {
			$obj_cotizacion["COTIZACION_SITIOS"][$i]["ETAPA"] = 'NO_E1';
		}
		if ($obj_cotizacion["COTIZACION_SITIOS"][$i]["SELECCIONADO"] != 1) {
			continue;
		}
		$total_dias_auditoria += $dias_subtotal+$dias_encuesta;
		$total_empleados += $obj_cotizacion["COTIZACION_SITIOS"][$i]["CANTIDAD_PERSONAS"];*/
	}
	
	//AQUI SE DEBE CALCULAR LA CANTIDAD DE DIAS BASE SEGUN LAS TABLAS QUE NOS DIERON
	$dias = $database->get("COTIZACION_EMPLEADOS_DIAS_CIL", "DIAS_AUDITOR_BASE",
								[
									"AND"=>[
												"ACTIVIDAD_ECONOMICA"=>$cotizacion[0]["DETALLES"][0]["VALOR"],
												"TOTAL_EMPLEADOS_MINIMO[<=]"=>$total_empleados,
												"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$total_empleados,
											]
									]);
	valida_error_medoo_and_die();
	
	//A PARTIR DE AQUI CALCULO LOS DIAS PARA ENCUESTA
	//$n ES EL TAMANO DE LA MUESTRA Y SU FORMULA ES
	//$n = N*Z*Z*P*Q/(E*E*(N-1)+Z*Z*P*Q)
	// NECESITAMOS LOS VALORES DE Z,E,P Y Q
	$datos_encuesta = $database->get("COTIZACION_CONSTANTES_ENCUESTA_TIPO_AUDITORIA_CIL", "*", ["ID_TIPO_AUDITORIA"=>$cotizacio_tramite["ID_TIPO_AUDITORIA"]]);
	valida_error_medoo_and_die();
	$tam_muestra = round(($total_empleados*$datos_encuesta['Z']*$datos_encuesta['Z']*$datos_encuesta['P']*$datos_encuesta['Q'])/($datos_encuesta['E']*$datos_encuesta['E']*($total_empleados-1)+$datos_encuesta['Z']*$datos_encuesta['Z']*$datos_encuesta['P']*$datos_encuesta['Q']));
							
	$dias_encuesta = $database->get("COTIZACION_EMPLEADOS_DIAS_ENCUESTA_CIL", "DIAS_AUDITOR",
								[
									"AND"=>[
												"TOTAL_EMPLEADOS_MINIMO[<=]"=>$tam_muestra,
												"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$tam_muestra,
											]
									]);
	valida_error_medoo_and_die();	
	//TOTAL DE DIAS PARA EL TRAMITE DIAS_BASE+DIAS_ENCUESTA+DIAS_MULTISITIO
	$total_dias_auditoria=$dias+$dias_encuesta+$cotizacio_tramite["DIAS_MULTISITIO"];
	$a = strpos($nombre_auditoria, 'Vigilancia');
	$b = strpos($nombre_auditoria, 'VIGILANCIA');
	$es_vigilancia = false;
	if($a !== false || $b !== false){
		$es_vigilancia = true;
	}
	$a = strpos($nombre_auditoria, 'Renovacion');
	$b = strpos($nombre_auditoria, 'RENOVACIÓN');
	$c = strpos($nombre_auditoria, 'RENOVACION');
	$d = strpos($nombre_auditoria, 'Renovación');
	$es_renovacion = false;
	if($a !== false || $b !== false || $c !== false || $d !== false){
		$es_renovacion = true;
	}
	$es_etapa_2 = false;
	$a = strpos($nombre_auditoria, 'Etapa 2');
	$b = strpos($nombre_auditoria, 'ETAPA 2');
	if($a !== false || $b !== false ){
		$es_etapa_2 = true;
	}
	//Cuando es diferente de vigilancia y renovación es 1 día
	if($es_vigilancia === false && $es_renovacion === false && $es_renovacion1 === false && $es_etapa_2 === false){ 
		$total_dias_auditoria = 1;
	}
	//Estapa 2 es la cantidad de días de etapa 1 menos 1
	if($es_etapa_2 !== false){ 
		if ($total_dias_auditoria > 0) {
			$total_dias_auditoria--;
		}
	}
	
	//AQUI LE APLICO EL FACTOR DE REDUCCION Y AMPLIACION QUE SE CALCULA PARA EL TRAMITE
	//$total_dias_auditoria = round($total_dias_auditoria * (1 - ($cotizacio_tramite["REDUCCION"]/100) + ($cotizacio_tramite["AUMENTO"]/100) ));
	$obj_cotizacion["TOTAL_EMPLEADOS"] = $total_empleados;
	$obj_cotizacion["DIAS_BASE"] = $dias;
	$obj_cotizacion["DIAS_ENCUESTA"] = $dias_encuesta;
	$obj_cotizacion["PERSONAS_ENCUESTA"] = $tam_muestra;
	$obj_cotizacion["DIAS_MULTISITIO"] = $cotizacio_tramite["DIAS_MULTISITIO"];
	$obj_cotizacion["TOTAL_DIAS_AUDITORIA"] = $total_dias_auditoria;
	$obj_cotizacion["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
	
	$obj_cotizacion["VIATICOS"] = $cotizacio_tramite["VIATICOS"];
	$obj_cotizacion["TARIFA_DES"] = (floatval($tarifa['TARIFA']) * (1-($cotizacio_tramite["DESCUENTO"]/100)+($cotizacio_tramite["AUMENTO"]/100)) );
	$obj_cotizacion["TARIFA"] = $tarifa['TARIFA'];
	
	//$costo_inicial = ($total_dias_auditoria * floatval($tarifa['TARIFA']) );
	$costo_inicial = (($total_dias_auditoria - $dias_encuesta)* floatval($tarifa['TARIFA']) +$dias_encuesta*2000);
	//$costo_desc = ($costo_inicial * (1-($cotizacio_tramite["DESCUENTO"]/100) + ($cotizacio_tramite["AUMENTO"]/100) ) );
	$costo_desc = (($total_dias_auditoria - $dias_encuesta)* floatval($tarifa['TARIFA_DESC']) +$dias_encuesta*2000);
	$obj_cotizacion["COSTO_INICIAL"] = $costo_inicial;
	$obj_cotizacion["COSTO_DESCUENTO"] = $costo_desc;
	$obj_cotizacion["COSTO_TOTAL"] = $costo_desc + $cotizacio_tramite["VIATICOS"] + $total_tarifa_adicional;
	
	$obj_cotizacion["NORMAS"] = $normas;
print_r(json_encode($obj_cotizacion)); 
?> 
