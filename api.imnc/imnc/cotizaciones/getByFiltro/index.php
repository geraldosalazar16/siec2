<?php 
 	include  '../../ex_common/query.php'; 
	$correo = "jesus.popocatl@dhttecno.com";
	
	header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=clientes.csv");

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

if(isset($_REQUEST["EJECUTIVO"])){
	$EJECUTIVO = $_REQUEST["EJECUTIVO"];
}else{
	$EJECUTIVO = "";
}
if(isset($_REQUEST["CONTRATO"])){
	$CONTRATO = $_REQUEST["CONTRATO"];
}else{
	$CONTRATO = "";
}

if(isset($_REQUEST["ESTATUS"])){
	$ESTATUS = $_REQUEST["ESTATUS"];
}else{
	$ESTATUS = "";
}

if(isset($_REQUEST["NOMBRE"])){
	$NOMBRE = $_REQUEST["NOMBRE"];
}else{
	$NOMBRE = "";
}
$FECHA_INICIO = $_REQUEST["FECHA_INICIO"];
$FECHA_FIN = $_REQUEST["FECHA_FIN"];
$BANDERA = $_REQUEST["BANDERA"];

$lista_where = [];

$lista_join = [
	"[>]COTIZACIONES_TRAMITES" => ["COTIZACIONES.ID" => "ID_COTIZACION"], 
	"[>]ETAPAS_PROCESO" => ["COTIZACIONES_TRAMITES.ID_ETAPA_PROCESO" => "ID"],
	"[>]PROSPECTO_ESTATUS_SEGUIMIENTO" => ["COTIZACIONES.ESTADO_COTIZACION" => "ID"],
	"[>]SERVICIOS" => ["COTIZACIONES.ID_SERVICIO" => "ID"],
	"[>]TIPOS_SERVICIO" => ["COTIZACIONES.ID_TIPO_SERVICIO" => "ID"],

];

$lista_campos = [
	"COTIZACIONES.ID",
	"COTIZACIONES.FOLIO_CONSECUTIVO",
	"COTIZACIONES.FOLIO_INICIALES",
	"COTIZACIONES.FOLIO_SERVICIO",
	"COTIZACIONES.FOLIO_MES",
	"COTIZACIONES.FOLIO_YEAR",
	"COTIZACIONES.FOLIO_UPDATE",
	"COTIZACIONES.ID_TIPO_SERVICIO",
	"COTIZACIONES.SG_INTEGRAL",
	"COTIZACIONES.TARIFA",
	"COTIZACIONES.DESCUENTO",
	"COTIZACIONES.COMPLEJIDAD",
	"COTIZACIONES.FECHA_MODIFICACION",
	"CLI.NOMBRE(NOMBRE_CLIENTE)",
	"PROSPECTO_ESTATUS_SEGUIMIENTO.ESTATUS_SEGUIMIENTO",
	"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
	"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPOS_SERVICIO)",
];

if($BANDERA == 1){
	$lista_join["[>]CLIENTES(CLI)"] = ["ID_PROSPECTO" => "ID"];
}
else{
	$lista_join["[>]PROSPECTO(CLI)"] = ["ID_PROSPECTO" => "ID"];
	$lista_join["[>]PROSPECTO_ORIGEN"] = ["CLI.ORIGEN" => "ID"];
	$lista_join["[>]PROSPECTO_TIPO_CONTRATO"] = ["CLI.ID_TIPO_CONTRATO" => "ID"];
	array_push($lista_campos,"PROSPECTO_ORIGEN.ORIGEN");
	array_push($lista_campos,"TIPO_CONTRATO");

	if(!empty($CONTRATO)){
		$lista_where["PROSPECTO_TIPO_CONTRATO.TIPO_CONTRATO[~]"] = $CONTRATO;
	}
}
if(!empty($FECHA_INICIO)){
	$lista_where["COTIZACIONES.FECHA_MODIFICACION[>=]"] = $FECHA_INICIO;
}
if(!empty($FECHA_FIN)){
	$lista_where["COTIZACIONES.FECHA_MODIFICACION[<]"] = $FECHA_FIN;
}
if(!empty($NOMBRE)){
	$lista_where["CLI.NOMBRE[~]"] = $NOMBRE;
}
if(!empty($EJECUTIVO)){
	$lista_where["COTIZACIONES.FOLIO_INICIALES[~]"] = $EJECUTIVO;
}
if(!empty($ESTATUS)){
	$lista_where["COTIZACIONES.ESTADO_COTIZACION"] = $ESTATUS;
}
if(count($lista_where) > 1){
	$lista_where = ["AND" => $lista_where];
}
$respuesta = $database->select("COTIZACIONES", $lista_join, $lista_campos, $lista_where); 
valida_error_medoo_and_die("COTIZACIONES" ,$correo );

for ($i=0; $i < count($respuesta); $i++) { 

	$complejidad = $respuesta[$i]["COMPLEJIDAD"]; 
	$complejidades_validas = array("alta", "media", "baja", "limitada");
	if (!in_array($complejidad, $complejidades_validas)) {
		$complejidad = "media";
	}
	$complejidad = "_" . strtoupper($complejidad);

	$campos_tramite = [
		"COTIZACIONES_TRAMITES.ID",
		"COTIZACIONES_TRAMITES.VIATICOS",
		"COTIZACIONES_TRAMITES.DESCUENTO",
		"COTIZACIONES_TRAMITES.ID_ETAPA_PROCESO",
		"COTIZACIONES_TRAMITES.FACTOR_INTEGRACION",
		"COTIZACIONES_TRAMITES.JUSTIFICACION",
		"COTIZACIONES_TRAMITES.CAMBIO",
		"COTIZACIONES_TRAMITES.ID_SERVICIO_CLIENTE",
		"COTIZACIONES_TRAMITES.FECHA_MODIFICACION",
		"ETAPAS_PROCESO.ETAPA"
	];
	$tramites =  $database->select("COTIZACIONES_TRAMITES", ["[>]ETAPAS_PROCESO" => ["ID_ETAPA_PROCESO" => "ID_ETAPA"]], $campos_tramite,
	["ID_COTIZACION"=>$respuesta[$i]["ID"]]);
	valida_error_medoo_and_die("COTIZACIONES" ,$correo);

	$CONSECUTIVO = str_pad("".$respuesta[$i]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $respuesta[$i]["FOLIO_INICIALES"].$respuesta[$i]["FOLIO_SERVICIO"].$CONSECUTIVO.$respuesta[$i]["FOLIO_MES"].$respuesta[$i]["FOLIO_YEAR"];
	if( !empty($respuesta[$i]["FOLIO_UPDATE"]) && $respuesta[$i]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$respuesta[$i]["FOLIO_UPDATE"];
	}
	$respuesta[$i]["FOLIO"] = $FOLIO;
	$respuesta[$i]["TRAMITES"] = [];
	$total_cotizacion = 0;
	$total_cotizacion_adicional = 0;
	$total_cotizacion_des = 0;
	$total_dias_cotizacion = 0;
	foreach ($tramites as $key => $tramite_item) {
		$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
		valida_error_medoo_and_die("COTIZACIONES" ,$correo); 
		//Multiplicador para el calculo de sitios
		$const_dias = 1; //Default - Etapa certificacion 
		if(strpos($etapa["ETAPA"], 'Vigilancia') !== false){ // Vigilancia
			$const_dias = 0.33;
		}
		else if(strpos($etapa["ETAPA"], 'Renovación') !== false || strpos($etapa["ETAPA"], 'Renovacion') !== false){ // Renovacion
			$const_dias = 0.66;
		}
		$respuesta[$i]["TRAMITES"][$etapa["ID"]] =  [];
		$respuesta[$i]["TRAMITES"][$etapa["ID"]]["ETAPA"] =  $etapa["ETAPA"];
		$respuesta[$i]["TRAMITES"][$etapa["ID"]]["FECHA_FINAL"] =  $tramite_item["FECHA_MODIFICACION"];


		$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
		"*", ["ID_TRAMITE"=>$tramite_item["ID"]]);
		valida_error_medoo_and_die("COTIZACIONES" ,$correo);

		$total_tarifa_adicional = 0;
		for ($j=0; $j < count($cotizacion_tarifa_adicional); $j++) { 
			$subtotal = $cotizacion_tarifa_adicional[$j]["TARIFA"] * $cotizacion_tarifa_adicional[$j]["CANTIDAD"];
			$total_tarifa_adicional += $subtotal;
		}

		$cotizacion_sitios = $database->select("COTIZACION_SITIOS", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
		valida_error_medoo_and_die("COTIZACIONES" ,$correo); 

		$total_dias_auditoria = 0;
		for ($k=0; $k < count($cotizacion_sitios) ; $k++) { 
			if ($cotizacion_sitios[$k]["SELECCIONADO"] != 1) {
				continue;
			}
				$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
					[
						"AND"=>[
									"ID_TIPO_SERVICIO"=>$respuesta[$i]["ID_TIPO_SERVICIO"],
									"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$k]["TOTAL_EMPLEADOS"],
									"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$k]["TOTAL_EMPLEADOS"],
								]
					]);
				$dias_reduccion = ceil($dias * (1 - ($cotizacion_sitios[$k]["FACTOR_REDUCCION"]/100) + ($cotizacion_sitios[$k]["FACTOR_AMPLIACION"]/100) ));
				$dias_subtotal = ceil($dias_reduccion * $const_dias);
			
				$total_dias_auditoria += $dias_subtotal;
		}
		if($respuesta[$i]["SG_INTEGRAL"] == "si"){
			$total_dias_auditoria = ceil( $total_dias_auditoria * (1 - ($tramite_item["FACTOR_INTEGRACION"]/100)) );
		}
		
		$costo_inicial = ($total_dias_auditoria * floatval($respuesta[$i]["TARIFA"]) );	
		$costo_desc = ($costo_inicial * (1-($tramite_item["DESCUENTO"]/100)));
		$costo_adicional = $tramite_item["VIATICOS"] + $total_tarifa_adicional;

		//$respuesta[$i]["TRAMITES"][$etapa["ID"]]["DIAS_AUDITORIA"] = $total_dias_auditoria;
		//$respuesta[$i]["TRAMITES"][$etapa["ID"]]["MONTO_TOTAL"] = $costo_desc;

		$total_dias_cotizacion += $total_dias_auditoria;
		$total_cotizacion += $costo_desc;
		$total_cotizacion_adicional += $costo_adicional;
		$total_cotizacion_des += $costo_adicional + $costo_desc;
	}
	$respuesta[$i]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
	$respuesta[$i]["TOTAL_COTIZACION"] = $total_cotizacion;
	$respuesta[$i]["TOTAL_COTIZACION_ADICIONAL"] = $total_cotizacion_adicional;
	$respuesta[$i]["TOTAL_COTIZACION_DES"] = $total_cotizacion_des * (1-($respuesta[$i]["DESCUENTO"]/100));
}

$csv = "";
foreach ($respuesta[0] as $key => $item) {
		if($key == "TRAMITES"){
			continue;
		}
		$csv .= utf8_decode($key).",";
	}
	$csv .= "\r\n";
for($i = 0 ; $i < sizeof($respuesta); $i++){
	
	foreach ($respuesta[$i] as $key => $item) {
		if($key == "TRAMITES"){
			continue;
		}
		$csv .= utf8_decode(str_replace(",","",$item)).",";
	}
	$csv .= "\r\n";
}


print_r($csv);
//print_r(json_encode($respuesta)); 

?>