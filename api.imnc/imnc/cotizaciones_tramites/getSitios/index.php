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
$normas = $database->select("COTIZACION_NORMAS", "*", ["ID_COTIZACION"=>$id_cotizacion]);
valida_error_medoo_and_die();
//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$cotizacio_tramite["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die(); 
//Sustituyo $etapa["ETAPA"] por nombre_auditoria
$nombre_auditoria = $etapa["TIPO"];
$id_auditoria = $etapa["ID"];
$tarifa = $database->get("TARIFA_COTIZACION", "*", ["ID"=>$cotizacion[0]["TARIFA"]]);
valida_error_medoo_and_die(); 
$cantidad_de_sitios = $database->count("COTIZACION_TRAMITES_SITIOS", ["ID_TRAMITE"=>$cotizacio_tramite["ID"]]);//$cantidad_de_sitios = $database->count("COTIZACION_SITIOS", ["ID_COTIZACION"=>$cotizacio_tramite["ID"]]);
valida_error_medoo_and_die(); 


$campos = [
	"COTIZACION_TRAMITES_SITIOS.ID",
	"COTIZACION_SITIOS.ID_COTIZACION",
	"COTIZACION_SITIOS.ID_DOMICILIO_SITIO",
	"COTIZACION_SITIOS.TOTAL_EMPLEADOS",
	"COTIZACION_SITIOS.NUMERO_EMPLEADOS_CERTIFICACION",
	"COTIZACION_SITIOS.CANTIDAD_TURNOS",
	"COTIZACION_SITIOS.CANTIDAD_DE_PROCESOS",
	"COTIZACION_SITIOS.TEMPORAL_O_FIJO",
	"COTIZACION_SITIOS.MATRIZ_PRINCIPAL",
	"COTIZACION_SITIOS.ID_ACTIVIDAD",
	"COTIZACION_TRAMITES_SITIOS.SELECCIONADO",
	"COTIZACION_SITIOS.FACTOR_REDUCCION",
	"COTIZACION_SITIOS.FACTOR_AMPLIACION",
	"COTIZACION_SITIOS.JUSTIFICACION",
	"SG_ACTIVIDAD.ACTIVIDAD",
];

if($cotizacion[0]["BANDERA"] == 0){
	$id_cliente = $database->get("PROSPECTO",["ID_CLIENTE"], ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
	if($id_cliente["ID_CLIENTE"] == 0){
		$total_domicilios = $database->count("PROSPECTO_DOMICILIO", ["ID_PROSPECTO"=>$cotizacion[0]["ID_PROSPECTO"]]); 
		array_push($campos, "PROSPECTO_DOMICILIO.NOMBRE");
		$tabla_entidad = "PROSPECTO_DOMICILIO";
		$id_prospecto_asociado = $cotizacion[0]["ID_PROSPECTO"];
	}
	else{
		$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$id_cliente]);
		array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
		$tabla_entidad = "CLIENTES_DOMICILIOS";
		$id_prospecto_asociado = $database->get("PROSPECTO", "ID", ["ID_CLIENTE"=>$id_cliente]);
	}
	
}
else if($cotizacion[0]["BANDERA"] != 0){
	$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
	array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
	$tabla_entidad = "CLIENTES_DOMICILIOS";
	$id_prospecto_asociado = $database->get("PROSPECTO", "ID", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
}


$cotizacion_sitios = $database->select("COTIZACION_TRAMITES_SITIOS", 
										[	"[>]COTIZACION_SITIOS"=>["COTIZACION_TRAMITES_SITIOS.ID_SITIO"=>"ID"],
											"[>]".$tabla_entidad => ["COTIZACION_SITIOS.ID_DOMICILIO_SITIO" => "ID"], 
											"[>]SG_ACTIVIDAD" => ["COTIZACION_SITIOS.ID_ACTIVIDAD" => "ID"] ],
											$campos,
											["AND"=>
												[
													"COTIZACION_TRAMITES_SITIOS.ID_TRAMITE"=>$cotizacio_tramite["ID"],"COTIZACION_SITIOS.ID_COTIZACION"=>$id_cotizacion]
											]);
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
if($id_auditoria >= 6 && $id_auditoria <= 12 ){ // Vigilancia
	$const_sitio = 0.6;
	$const_dias = 0.33;
}
else if($id_auditoria == 4){ // Renovacion
	$const_sitio = 0.8;
	$const_dias = 0.66;
}
/*
if(strpos($nombre_auditoria, 'Vigilancia') !== false || strpos($nombre_auditoria, 'VIGILANCIA') !== false){ // Vigilancia
	$const_sitio = 0.6;
	$const_dias = 0.33;
}
else if(strpos($nombre_auditoria, 'Renovación') !== false || strpos($nombre_auditoria, 'Renovacion') !== false || strpos($nombre_auditoria, 'RENOVACION') !== false){ // Renovacion
	$const_sitio = 0.8;
	$const_dias = 0.66;
}
*/
$obj_cotizacion = [];
$obj_cotizacion["TIPOS_SERVICIO"] = $tipos_servicio;
$obj_cotizacion["ETAPA"] = $nombre_auditoria;
$obj_cotizacion["TARIFA_TOTAL"] = $tarifa;

$obj_cotizacion["COUNT_SITIOS"] = count_sitios($id,$id_cotizacion, $const_sitio);
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
		$dias = 0; 
		if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){
			foreach ($normas as $key => $norma) {
				//buscar el id_tipo_servicio dependiendo de la norma
				$query = "SELECT ID_TIPO_SERVICIO 
				FROM NORMAS_TIPOSERVICIO
				WHERE ID_NORMA = '".$norma["ID_NORMA"].
				"' AND ID_TIPO_SERVICIO <> 20";
				$id_tipo_servicio = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
				valida_error_medoo_and_die();

				$dias_norma = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad,
				[
					"AND"=>[
								"ID_TIPO_SERVICIO"=>$id_tipo_servicio[0]["ID_TIPO_SERVICIO"],
								"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
								"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
							]
				]);
				valida_error_medoo_and_die();
				$normas[$key]["DIAS"] = $dias_norma;
				$dias += $dias_norma;				
			}
		} else {
			$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad, 
			[
				"AND"=>[
							"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
							"TOTAL_EMPLEADOS_MINIMO[<=]"=>$obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"],
							"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"],
						]
			]);
			valida_error_medoo_and_die();
		}		

		$dias_reduccion = round($dias * (1 - ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_REDUCCION"]/100) 
			+ ($obj_cotizacion["COTIZACION_SITIOS"][$i]["FACTOR_AMPLIACION"]/100)) );
		$dias_subtotal = round($dias_reduccion * $const_dias);
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA"] = $dias;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_RED"] = $dias_reduccion;
		$obj_cotizacion["COTIZACION_SITIOS"][$i]["DIAS_AUDITORIA_SUBTOTAL"] = $dias_subtotal;
		//Saber si es etapa 1 para modificar la visa
		//$es_etapa_1 = (strpos($nombre_auditoria, 'Etapa 1') || strpos($nombre_auditoria, 'ETAPA 1'));
		if($id_auditoria == 2){
			$es_etapa_1 = true;
		}else{
			$es_etapa_1 = false;
		}
		if($es_etapa_1 !== false){ 
			$obj_cotizacion["COTIZACION_SITIOS"][$i]["ETAPA"] = 'E1';
		} else {
			$obj_cotizacion["COTIZACION_SITIOS"][$i]["ETAPA"] = 'NO_E1';
		}
		if ($obj_cotizacion["COTIZACION_SITIOS"][$i]["SELECCIONADO"] != 1) {
			continue;
		}
		$total_dias_auditoria += $dias_subtotal;
		$total_empleados += $obj_cotizacion["COTIZACION_SITIOS"][$i]["TOTAL_EMPLEADOS"];
	}
	
	//Buscar el nivel de integración para Integrales
$cotizacion[0]["FACTOR_REDUCCION_INTEGRAL"] = 0;
if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){

	//Necesito el id del producto
	$id_producto = $database->get("PROSPECTO_PRODUCTO", "*", 
	[
		"AND" => [
			"ID_PROSPECTO"=>$id_prospecto_asociado,
			"ID_SERVICIO"=>$cotizacion[0]["ID_SERVICIO"],
			"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
		]
	]);
	valida_error_medoo_and_die();

	//Con el id del producto busco la información de integración
	$query = "SELECT 
	PI.ID_PRODUCTO,
	IP.PREGUNTA,
	IPR.VALOR,
	PI.ID_PREGUNTA,
	PI.RESPUESTA
	FROM PRODUCTO_INTEGRACION PI
	INNER JOIN INTEGRACION_PREGUNTAS IP
	ON IP.ID = PI.ID_PREGUNTA
	INNER JOIN INTEGRACION_PREGUNTAS_RESPUESTAS IPR
	ON IPR.ID_PREGUNTA = PI.ID_PREGUNTA
	AND IPR.RESPUESTA = PI.RESPUESTA
	WHERE PI.ID_PRODUCTO = ".$id_producto["ID"]; 
	$producto_integracion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();

	//Ahora necesito recorrer el arreglo obtenido para calcular la integración
	$nivel_integracion = 0;
	foreach ($producto_integracion as $key => $integracion) {
		$nivel_integracion += $integracion["VALOR"];
	}
	$cotizacion[0]["NIVEL_INTEGRACION"] = $nivel_integracion;

	//La capacidad de ejecución está en cotizacion[0]["COMBINADA"]
	$capacidad = $cotizacion[0]["COMBINADA"];
	//Con estos dos valores busco en la tabla COTIZACION_NIVEL_INTEGRACION
	//DONDE X ES CAPACIDAD Y Y ES NIVEL DE INTEGRACION
	$query = "SELECT
	VALOR FROM COTIZACION_NIVEL_INTEGRACION
	WHERE X_MIN_PORCENTAJE < ".$capacidad.
	" AND X_MAX_PORCENTAJE >= ".$capacidad.
	" AND Y_MIN_PORCENTAJE < ".$nivel_integracion.
	" AND Y_MAX_PORCENTAJE >= ".$nivel_integracion;
	$factor_reduccion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
	$cotizacion[0]["FACTOR_REDUCCION_INTEGRAL"] = $factor_reduccion[0]["VALOR"];
}
	
	//Si es integral hay que aplicar el factor de reducción para integrales
	if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){
		//Primero calcular el factor de integración
		//Leer el nivel de integración de prospectos
		$total_dias_auditoria = round( $total_dias_auditoria * (1 - ($cotizacion[0]["FACTOR_REDUCCION_INTEGRAL"]/100)) );
	}
	/*$a = strpos($nombre_auditoria, 'Vigilancia');
	$b = strpos($nombre_auditoria, 'VIGILANCIA');
	$es_vigilancia = false;
	if($a !== false || $b !== false){
		$es_vigilancia = true;
	}*/
	if($id_auditoria >= 6 && $id_auditoria <= 12 ){ // Vigilancia
		$es_vigilancia = true;
	}
	else{
		$es_vigilancia = false;
	}
	/*$a = strpos($nombre_auditoria, 'Renovacion');
	$b = strpos($nombre_auditoria, 'RENOVACIÓN');
	$c = strpos($nombre_auditoria, 'RENOVACION');
	$d = strpos($nombre_auditoria, 'Renovación');
	$es_renovacion = false;
	if($a !== false || $b !== false || $c !== false || $d !== false){
		$es_renovacion = true;
	}*/
	if($id_auditoria == 4){ // Renovacion
		$es_renovacion = true;
	}
	else{
		$es_renovacion = false;
	}
	/*$es_etapa_2 = false;
	$a = strpos($nombre_auditoria, 'Etapa 2');
	$b = strpos($nombre_auditoria, 'ETAPA 2');
	if($a !== false || $b !== false ){
		$es_etapa_2 = true;
	}*/
	if($id_auditoria == 3){ // Etapa2
		$es_etapa_2 = true;
	}
	else{
		$es_etapa_2 = false;
	}
	//Cuando es diferente de vigilancia y renovación es 1 día y de etapa 2
		if($es_vigilancia === false && $es_renovacion === false && $es_etapa_2 === false){
			$total_dias_auditoria = 1;
		}
	//Estapa 2 es la cantidad de días de etapa 1 menos 1
	if($es_etapa_2 !== false){ 
		if ($total_dias_auditoria > 0) {
			$total_dias_auditoria--;
		}
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
	$obj_cotizacion["TARIFA_DES"] = (floatval($tarifa['TARIFA']) * (1-($cotizacio_tramite["DESCUENTO"]/100)) );

	$costo_inicial = ($total_dias_auditoria * floatval($tarifa['TARIFA']) );
	$costo_desc = ($costo_inicial * (1-($cotizacio_tramite["DESCUENTO"]/100)) );
	$obj_cotizacion["COSTO_INICIAL"] = $costo_inicial;
	$obj_cotizacion["COSTO_DESCUENTO"] = $costo_desc;
	$obj_cotizacion["COSTO_TOTAL"] = $costo_desc + $cotizacio_tramite["VIATICOS"] + $total_tarifa_adicional;
	
	$obj_cotizacion["NORMAS"] = $normas;
print_r(json_encode($obj_cotizacion)); 
?> 
