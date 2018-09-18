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
		$mailerror->send("COTIZACIONES_TRAMITES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id = $_REQUEST["id"]; 
$id_cotizacion = $_REQUEST["cotizacion"]; 

$query = "SELECT * FROM TABLA_ENTIDADES,COTIZACIONES WHERE ID_PROSPECTO = ID_VISTA AND BANDERA_VISTA = BANDERA AND ID =".$database->quote($id_cotizacion);
$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die();
$cotizacio_tramite = $database->get("COTIZACIONES_TRAMITES", "*", ["ID"=>$id]); 
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
$norma = $database->get("NORMAS", "*", ["ID"=>$tipos_servicio["ID_NORMA"]]);
valida_error_medoo_and_die(); 
$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$cotizacio_tramite["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die(); 

$cantidad_de_sitios = $database->count("COTIZACION_SITIOS", ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die(); 

$campos = [
	"COTIZACION_SITIOS.ID",
	"COTIZACION_SITIOS.ID_COTIZACION",
	"COTIZACION_SITIOS.ID_DOMICILIO_SITIO",
	"COTIZACION_SITIOS.TOTAL_EMPLEADOS",
	"COTIZACION_SITIOS.NUMERO_EMPLEADOS_CERTIFICACION",
	"COTIZACION_SITIOS.CANTIDAD_TURNOS",
	"COTIZACION_SITIOS.CANTIDAD_DE_PROCESOS",
	"COTIZACION_SITIOS.TEMPORAL_O_FIJO",
	"COTIZACION_SITIOS.MATRIZ_PRINCIPAL",
	"COTIZACION_SITIOS.ID_ACTIVIDAD",
	"COTIZACION_SITIOS.SELECCIONADO",
	"COTIZACION_SITIOS.FACTOR_REDUCCION",
	"COTIZACION_SITIOS.FACTOR_AMPLIACION",
	"COTIZACION_SITIOS.JUSTIFICACION",
	"SG_ACTIVIDAD.ACTIVIDAD",
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

$cotizacion_sitios = $database->select("COTIZACION_SITIOS", ["[>]".$tabla_entidad => ["ID_DOMICILIO_SITIO" => "ID"], 
	"[>]SG_ACTIVIDAD" => ["ID_ACTIVIDAD" => "ID"] ], $campos, ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
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
if(strpos($etapa["ETAPA"], 'Vigilancia') !== false){ // Vigilancia
	$const_sitio = 0.6;
	$const_dias = 0.33;
}
else if(strpos($etapa["ETAPA"], 'Renovación') !== false || strpos($etapa["ETAPA"], 'Renovacion') !== false){ // Renovacion
	$const_sitio = 0.8;
	$const_dias = 0.66;
}
$obj_cotizacion = [];
$obj_cotizacion["ETAPA"] = $etapa["ETAPA"];

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
	for ($i=0; $i < count($obj_cotizacion["COTIZACION_SITIOS"]) ; $i++) { 
		$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
			[
				"AND"=>[
							"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
							"TOTAL_EMPLEADOS_MINIMO[<=]"=>$obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"],
							"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"],
						]
			]);
		$dias_reduccion = ceil($dias * (1 - ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_REDUCCION"]/100) 
			+ ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_AMPLIACION"]/100)) );
		$dias_subtotal = ceil($dias_reduccion * $const_dias);
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA"] = $dias;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_RED"] = $dias_reduccion;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_SUBTOTAL"] = $dias_subtotal;

		if ($obj_cotizacion["COTIZACION_SITIOS"][$i]["SELECCIONADO"] != 1) {
			continue;
		}
		$total_dias_auditoria += $dias_subtotal;
		$total_empleados += $obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"];
	}

	$obj_cotizacion["TOTAL_EMPLEADOS"] = $total_empleados;
	$obj_cotizacion["TOTAL_DIAS_AUDITORIA"] = $total_dias_auditoria;
	if($cotizacion[0]["SG_INTEGRAL"] == "si"){
		$total_dias_auditoria = ceil($total_dias_auditoria * (1 - ($cotizacio_tramite["FACTOR_INTEGRACION"]/100)) );
		$obj_cotizacion["TOTAL_DIAS_AUDITORIA_INTG"] = $total_dias_auditoria;
	}
	$obj_cotizacion["VIATICOS"] = $cotizacio_tramite["VIATICOS"];
	$obj_cotizacion["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
	$obj_cotizacion["FACTOR_INTEGRACION"] = $cotizacio_tramite["FACTOR_INTEGRACION"];
	$obj_cotizacion["TARIFA_DES"] = (floatval($cotizacion[0]["TARIFA"]) * (1-($cotizacio_tramite["DESCUENTO"]/100)) );

	$costo_inicial = ($total_dias_auditoria * floatval($cotizacion[0]["TARIFA"]) );
	$costo_desc = ($costo_inicial * (1-($cotizacio_tramite["DESCUENTO"]/100)) );
	$obj_cotizacion["COSTO_INICIAL"] = $costo_inicial;
	$obj_cotizacion["COSTO_DESCUENTO"] = $costo_desc;
	$obj_cotizacion["COSTO_TOTAL"] = $costo_desc + $cotizacio_tramite["VIATICOS"] + $total_tarifa_adicional;

print_r(json_encode($obj_cotizacion)); 
?> 
