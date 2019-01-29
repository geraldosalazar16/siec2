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

function redondeado ($numero, $decimales) { 
   $factor = pow(10, $decimales); 
   return (round($numero*$factor)/$factor); }

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
$cotizacio_tramite = $database->get("COTIZACIONES_TRAMITES_PIND", "*", ["ID"=>$id]); 
valida_error_medoo_and_die(); 

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
$cantidad_de_sitios = $database->count("COTIZACION_SITIOS_PIND", ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die(); 



$campos = [
	"COTIZACION_SITIOS_PIND.ID",
	"COTIZACION_SITIOS_PIND.ID_COTIZACION",
	"COTIZACION_SITIOS_PIND.ALCANCE",
	"COTIZACION_SITIOS_PIND.MATRIZ_PRINCIPAL",
	"COTIZACION_SITIOS_PIND.TEMPORAL_O_FIJO",
	"COTIZACION_SITIOS_PIND.ID_DOMICILIO_SITIO",
	"COTIZACION_SITIOS_PIND.SELECCIONADO",
	
	
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

$cotizacion_sitios = $database->select("COTIZACION_SITIOS_PIND", ["[>]".$tabla_entidad => ["ID_DOMICILIO_SITIO" => "ID"]], $campos, ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
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
	$campos_t, ["AND"=>["ID_TRAMITE"=>$cotizacio_tramite["ID"],"ID_COTIZACION"=>$id_cotizacion]]);
valida_error_medoo_and_die();


//Multiplicador para el calculo de sitios
$const_sitio = 1; //Default - Etapa certificacion 
$const_dias = 1;

$obj_cotizacion = [];
$obj_cotizacion["TIPOS_SERVICIO"] = $tipos_servicio;
$obj_cotizacion["ETAPA"] = $nombre_auditoria;
$obj_cotizacion["TARIFA_TOTAL"] = $tarifa;

$obj_cotizacion["COUNT_SITIOS"] = count_sitios($id, $const_sitio);

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

		
			
	// Aqui calculo el costo de la auditoria que sin importar el tramite seria igual a la suma de la tarifa para cada sitio
	$costo_inicial = 0;
	$costo_total_visita_inspeccion =0;
	$costo_total_ensayos = 0;
	$total_productos = 0;
	for ($i=0; $i < count($cotizacion_sitios) ; $i++) {
		$productos = [];
		$costo_visita_inspeccion =0;
		$costo_ensayos = 0;
		//Aqui se buscan los productos de la cotizacion
		$productos = $database->select("PROD_IND_SITIO", ["[>]PRODUCTOS_INDUSTRIALES" => ["ID_PI" => "ID"]], ["PRODUCTOS_INDUSTRIALES.NOMBRE","PRODUCTOS_INDUSTRIALES.VALOR"], ["AND"=>["ID_SITIO_PIND"=>$cotizacion_sitios[$i]["ID"],"ID_TRAMITE"=>$cotizacio_tramite["ID"]]]);
		valida_error_medoo_and_die();
		$productos1="";
		for($j=0;$j<count($productos);$j++){
			$productos1 .= $productos[$j]["NOMBRE"].",";
			if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1 && $etapa["ID"]!=15) {
				$costo_ensayos += $productos[$j]["VALOR"];
				$total_productos++;
			}
		} 
		
		$cotizacion_sitios[$i]["PRODUCTOS_LISTA"] = $productos1;
		$cotizacion_sitios[$i]["PRODUCTOS"] = $productos;
		
		//Aqui se calcula los valores de la cotizacion
		/*	if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
				if($cotizacion_sitios[$i]["OPCION_8H_O_4H"] == 0){
					$costo_inicial += $tarifa['TARIFA']*0.5;  
				}
				else{
					$costo_inicial += $tarifa['TARIFA']; 
				}
			}
			else{
				$costo_inicial += 0;
			}	*/
		if($etapa["ID"]==14  ){// Este es el costo de inspeccion y fijo para Certificacion
			if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
				$costo_visita_inspeccion = $tarifa['TARIFA'];  
			}
				
		}
		if($etapa["ID"]==15  ){// Este es el costo de inspeccion y fijo para Renovacion
			if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
				$costo_visita_inspeccion = 3200;  
			}
				
		}
		if($etapa["ID"]==16  ){// Este es el costo de inspeccion y fijo para Renovacion
			if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
				$costo_visita_inspeccion = $tarifa['TARIFA'];  
			}
				
		}
		if($etapa["ID"]>=17 && $etapa["ID"]<=23 ){// Este es el costo de inspeccion y fijo para Vigilancias
			if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
				$costo_visita_inspeccion = $tarifa['TARIFA'];  
			}
		}
		$costo_total_visita_inspeccion +=$costo_visita_inspeccion;
		$costo_total_ensayos += $costo_ensayos;
		$cotizacion_sitios[$i]["COSTO_VISITA_INSPECCION"] = $costo_visita_inspeccion;
		$cotizacion_sitios[$i]["COSTO_ENSAYOS"] = $costo_ensayos;
	
	}								
	$costo_inicial = $costo_total_visita_inspeccion+$costo_total_ensayos + $total_tarifa_adicional;

	$obj_cotizacion["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
	
	$obj_cotizacion["VIATICOS"] = $cotizacio_tramite["VIATICOS"];
	//$obj_cotizacion["TARIFA_DES"] = (floatval($tarifa['TARIFA']) * (1-($cotizacio_tramite["DESCUENTO"]/100)+($cotizacio_tramite["AUMENTO"]/100)) );
	//$obj_cotizacion["TARIFA"] = $tarifa['TARIFA'];
	
	//$costo_inicial = ($total_dias_auditoria * floatval($tarifa['TARIFA']) );
	//$costo_inicial = (($total_dias_auditoria)* floatval($tarifa['TARIFA']) );
	//$costo_desc = ($costo_inicial * (1-($cotizacio_tramite["DESCUENTO"]/100) + ($cotizacio_tramite["AUMENTO"]/100) ) );
	$costo_desc = ($costo_inicial* floatval(1-($cotizacio_tramite["DESCUENTO"]/100)+($cotizacio_tramite["AUMENTO"]/100)) );
	$obj_cotizacion["TOTAL_PRODUCTOS"] = $total_productos;
	$obj_cotizacion["COSTO_TOTAL_VISITA_INSPECCION"] = redondeado ($costo_total_visita_inspeccion,2) ;
	$obj_cotizacion["COSTO_TOTAL_ENSAYOS"] = redondeado ($costo_total_ensayos,2);
	$obj_cotizacion["COSTO_INICIAL"] = redondeado ($costo_inicial,2);
	$obj_cotizacion["COSTO_DESCUENTO"] = redondeado ($costo_desc,2);
	$obj_cotizacion["COSTO_TOTAL"] = $costo_desc + $cotizacio_tramite["VIATICOS"];
	
	$obj_cotizacion["NORMAS"] = $normas;
	$obj_cotizacion["COTIZACION_SITIOS"] = $cotizacion_sitios;
print_r(json_encode($obj_cotizacion)); 
?> 
