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

$cotizacion = $database->get("COTIZACIONES", "*", ["ID"=>$id]); 
$query = "SELECT * FROM TABLA_ENTIDADES,COTIZACIONES WHERE ID_PROSPECTO = ID_VISTA AND BANDERA_VISTA = BANDERA AND ID =".$database->quote($id);
$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die(); 

$complejidad = $cotizacion[0]["COMPLEJIDAD"]; 
$complejidades_validas = array("alta", "media", "baja", "limitada");
if (!in_array($complejidad, $complejidades_validas)) {
	$complejidad = "media";
}
$complejidad = "_" . strtoupper($complejidad);

if($cotizacion[0]["BANDERA"] == 0){
	$id_cliente = $database->get("PROSPECTO", "ID_CLIENTE", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
	$cliente = $database->get("CLIENTES", "*", ["ID"=>$id_cliente]);
	$cotizacion[0]["CLIENTE"] = $cliente;
}


$servicio = $database->get("SERVICIOS", "*", ["ID"=>$cotizacion[0]["ID_SERVICIO"]]);
valida_error_medoo_and_die(); 
$tipos_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$cotizacion[0]["ID_TIPO_SERVICIO"]]);
valida_error_medoo_and_die(); 
$norma = $database->get("NORMAS", "*", ["ID"=>$tipos_servicio["ID_NORMA"]]);
valida_error_medoo_and_die();
$estado = $database->get("PROSPECTO_ESTATUS_SEGUIMIENTO", "*", ["ID"=>$cotizacion[0]["ESTADO_COTIZACION"]]);
valida_error_medoo_and_die(); 
$campos_tramite = [
	"COTIZACIONES_TRAMITES.ID",
	"COTIZACIONES_TRAMITES.VIATICOS",
	"COTIZACIONES_TRAMITES.DESCUENTO",
	"COTIZACIONES_TRAMITES.ID_ETAPA_PROCESO",
	"COTIZACIONES_TRAMITES.FACTOR_INTEGRACION",
	"COTIZACIONES_TRAMITES.JUSTIFICACION",
	"COTIZACIONES_TRAMITES.CAMBIO",
	"COTIZACIONES_TRAMITES.ID_SERVICIO_CLIENTE",
	"ETAPAS_PROCESO.ETAPA"
];
$tramites =  $database->select("COTIZACIONES_TRAMITES", ["[>]ETAPAS_PROCESO" => ["ID_ETAPA_PROCESO" => "ID_ETAPA"]], $campos_tramite,
	["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
valida_error_medoo_and_die(); 



$cotizacion[0]["SERVICIO"] = $servicio;
$cotizacion[0]["TIPOS_SERVICIO"] = $tipos_servicio;
$cotizacion[0]["NORMA"] = $norma;
$cotizacion[0]["ESTADO"] = $estado;
$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;

$CONSECUTIVO = str_pad("".$cotizacion[0]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
$FOLIO = $cotizacion[0]["FOLIO_INICIALES"].$cotizacion[0]["FOLIO_SERVICIO"].$CONSECUTIVO.$cotizacion[0]["FOLIO_MES"].$cotizacion[0]["FOLIO_YEAR"];
if( !is_null($cotizacion[0]["FOLIO_UPDATE"]) && $cotizacion[0]["FOLIO_UPDATE"] != ""){
	$FOLIO .= "-".$cotizacion[0]["FOLIO_UPDATE"];
}
$cotizacion[0]["FOLIO"] = $FOLIO;

$total_cotizacion = 0;
$total_dias_cotizacion = 0;
foreach ($tramites as $key => $tramite_item) {
	$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
	valida_error_medoo_and_die(); 
	//Multiplicador para el calculo de sitios
	$const_dias = 1; //Default - Etapa certificacion 
	if(strpos($etapa["ETAPA"], 'Vigilancia') !== false){ // Vigilancia
		$const_dias = 0.33;
	}
	else if(strpos($etapa["ETAPA"], 'Renovación') !== false || strpos($etapa["ETAPA"], 'Renovacion') !== false){ // Renovacion
		$const_dias = 0.66;
	}


	$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
	"*", ["ID_TRAMITE"=>$tramite_item["ID"]]);
	valida_error_medoo_and_die();

	$total_tarifa_adicional = 0;
	for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) { 
		$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
		$total_tarifa_adicional += $subtotal;
	}

	$cotizacion_sitios = $database->select("COTIZACION_SITIOS", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
	valida_error_medoo_and_die(); 

	$total_dias_auditoria = 0;
	for ($i=0; $i < count($cotizacion_sitios) ; $i++) { 
		if ($cotizacion_sitios[$i]["SELECCIONADO"] != 1) {
			continue;
		}
			$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
				[
					"AND"=>[
								"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
								"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
								"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
							]
				]);
			$dias_reduccion = ceil($dias * (1 - ($cotizacion_sitios[$i]["FACTOR_REDUCCION"]/100) + ($cotizacion_sitios[$i]["FACTOR_AMPLIACION"]/100) ));
			$dias_subtotal = ceil($dias_reduccion * $const_dias);
		
			$total_dias_auditoria += $dias_subtotal;
	}
	if($cotizacion[0]["SG_INTEGRAL"] == "si"){
		$total_dias_auditoria = ceil( $total_dias_auditoria * (1 - ($tramite_item["FACTOR_INTEGRACION"]/100)) );
	}
	
	$costo_inicial = ($total_dias_auditoria * floatval($cotizacion[0]["TARIFA"]) );	
	$costo_desc = ($costo_inicial * (1-($tramite_item["DESCUENTO"]/100)));
	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $total_dias_auditoria;
	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"] + $total_tarifa_adicional;

	$total_dias_cotizacion += $total_dias_auditoria;
	$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
}
$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100));
print_r(json_encode($cotizacion)); 
?> 
