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
		die();
	}
}
function redondeado ($numero, $decimales) { 
   $factor = pow(10, $decimales); 
   return (round($numero*$factor)/$factor); }
   
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

	$servicio = $database->get("SERVICIOS", "*", ["ID"=>$cotizacion[0]["ID_SERVICIO"]]);
	valida_error_medoo_and_die();
	$tipos_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$cotizacion[0]["ID_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die();
	$normas = $database->select("COTIZACION_NORMAS", "*", ["ID_COTIZACION"=>$id]);
	valida_error_medoo_and_die();
	$estado = $database->get("PROSPECTO_ESTATUS_SEGUIMIENTO", "*", ["ID"=>$cotizacion[0]["ESTADO_COTIZACION"]]);
	valida_error_medoo_and_die();
//Codigo si el tipo de servicio pertenece a certificacion de sistemas de gestion	
if($cotizacion[0]["ID_SERVICIO"] == 1){
	
	
	if($cotizacion[0]["BANDERA"] != "0"){
		//$id_cliente = $database->get("PROSPECTO", "ID_CLIENTE", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		$cliente = $database->get("CLIENTES", "*", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		valida_error_medoo_and_die();
		$cotizacion[0]["CLIENTE"] = $cliente;
	} else {
		$prospecto = $database->get("PROSPECTO", "*", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		valida_error_medoo_and_die();
		$cotizacion[0]["PROSPECTO"] = $prospecto;
	
		//Buscar el nivel de integración para Integrales
		$cotizacion[0]["FACTOR_REDUCCION_INTEGRAL"] = 0;
		if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){
	
			//Necesito el id del producto
			$id_producto = $database->get("PROSPECTO_PRODUCTO", "*", 
			[
				"AND" => [
					"ID_PROSPECTO"=>$cotizacion[0]["ID_PROSPECTO"],
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
	}	


	$tarifa = $database->get("TARIFA_COTIZACION", "*", ["ID"=>$cotizacion[0]["TARIFA"]]);
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
		"I_SG_AUDITORIAS_TIPOS.TIPO"
	];
	$tramites =  $database->select("COTIZACIONES_TRAMITES", 
	["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_ETAPA_PROCESO" => "ID"]], $campos_tramite,
		["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
	valida_error_medoo_and_die();



	$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
	$cotizacion[0]["TARIFA_COMPLETA"] = $tarifa;

	$CONSECUTIVO = str_pad("".$cotizacion[0]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $cotizacion[0]["FOLIO_INICIALES"].$cotizacion[0]["FOLIO_SERVICIO"].$CONSECUTIVO.$cotizacion[0]["FOLIO_MES"].$cotizacion[0]["FOLIO_YEAR"];
	if( !is_null($cotizacion[0]["FOLIO_UPDATE"]) && $cotizacion[0]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$cotizacion[0]["FOLIO_UPDATE"];
	}
	$cotizacion[0]["FOLIO"] = $FOLIO;

	$total_cotizacion = 0;
	$total_dias_cotizacion = 0;
	foreach ($tramites as $key => $tramite_item) {
		//En vez de usar la etapa usar el tipo de auditoria
		//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
		$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_ETAPA_PROCESO"]]);
		//Sustituyo $etapa["ETAPA"] por nombre_auditoria
		$nombre_auditoria = $etapa["TIPO"];
		valida_error_medoo_and_die();
		$etapa_para_sgen = "VIGILANCIA";
		//Multiplicador para el calculo de sitios
		$const_dias = 1; //Default - Etapa certificacion
		if(strpos($nombre_auditoria, 'Vigilancia') !== false || strpos($nombre_auditoria, 'VIGILANCIA')){ // Vigilancia
			$const_dias = 0.33;
			$etapa_para_sgen = "VIGILANCIA";
		}
		else if(strpos($nombre_auditoria, 'Renovación') !== false || strpos($nombre_auditoria, 'Renovacion') !== false || strpos($nombre_auditoria, 'RENOVACIÓN')){ // Renovacion
			$const_dias = 0.66;
			$etapa_para_sgen = "RENOVACION";
		}


		$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
		"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
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
			$dias = 0;
			
			//Si es integral es diferente
			/* 
				Es necesario buscar la cantidad de días para cada una de las normas asociadas al servicio
				Por esto hay que buscar los tipos de servicio dependiendo de las normas
				y hacer lo mismo que para una auditoría normal
			*/
			if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){
				foreach ($normas as $index => $norma) {
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
					$normas[$index]["DIAS"] = $dias_norma;
					$dias += $dias_norma;				
				}
			} else {
				//Para el caso de auditorías simples
				if($cotizacion[0]["ID_TIPO_SERVICIO"] == 21){
					$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad,
					[
						"AND"=>[
									"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
									"ETAPA"=>$etapa_para_sgen,
									"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
									"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
								]
					]);
				} else {
					$dias = $database->get("COTIZACION_EMPLEADOS_DIAS", "DIAS_AUDITORIA".$complejidad,
					[
						"AND"=>[
									"ID_TIPO_SERVICIO"=>$cotizacion[0]["ID_TIPO_SERVICIO"],
									"TOTAL_EMPLEADOS_MINIMO[<=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
									"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$cotizacion_sitios[$i]["TOTAL_EMPLEADOS"],
								]
					]);
				}
			}
				$dias_reduccion = round($dias * (1 - ($cotizacion_sitios[$i]["FACTOR_REDUCCION"]/100) + ($cotizacion_sitios[$i]["FACTOR_AMPLIACION"]/100) ));
				$dias_subtotal = round($dias_reduccion * $const_dias);	

				$total_dias_auditoria += $dias_subtotal;
		}
		//Si es integral hay que aplicar el factor de reducción para integrales
		if($cotizacion[0]["ID_TIPO_SERVICIO"] == 20){
			//Primero calcular el factor de integración
			//Leer el nivel de integración de prospectos
			$total_dias_auditoria = round( $total_dias_auditoria * (1 - ($cotizacion[0]["FACTOR_REDUCCION_INTEGRAL"]/100)) );
		}
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
		if($es_vigilancia === false && $es_renovacion === false && $es_etapa_2 === false){
			$total_dias_auditoria = 1;
		}
		//Estapa 2 es la cantidad de días de etapa 1 menos 1
		if($es_etapa_2 !== false){
			if ($total_dias_auditoria > 0) {
				$total_dias_auditoria--;
			}
		}
		$costo_inicial = ($total_dias_auditoria * floatval($tarifa['TARIFA']) );
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

}
//Codigo si el tipo de servicio pertenece a evaluacion de la conformidad
if($cotizacion[0]["ID_SERVICIO"] == 2){
	if($cotizacion[0]["BANDERA"] != "0"){
		//$id_cliente = $database->get("PROSPECTO", "ID_CLIENTE", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		$cliente = $database->get("CLIENTES", "*", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		valida_error_medoo_and_die();
		$cotizacion[0]["CLIENTE"] = $cliente;
	} else {
		$prospecto = $database->get("PROSPECTO", "*", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		valida_error_medoo_and_die();
		$cotizacion[0]["PROSPECTO"] = $prospecto;
	}
	$tarifa = $database->get("TARIFA_COTIZACION", "*", ["ID"=>$cotizacion[0]["TARIFA"]]);
	valida_error_medoo_and_die();
	$cotizacion[0]["TARIFA_COMPLETA"] = $tarifa;
	
	$CONSECUTIVO = str_pad("".$cotizacion[0]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $cotizacion[0]["FOLIO_INICIALES"].$cotizacion[0]["FOLIO_SERVICIO"].$CONSECUTIVO.$cotizacion[0]["FOLIO_MES"].$cotizacion[0]["FOLIO_YEAR"];
	if( !is_null($cotizacion[0]["FOLIO_UPDATE"]) && $cotizacion[0]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$cotizacion[0]["FOLIO_UPDATE"];
	}
	$cotizacion[0]["FOLIO"] = $FOLIO;
	
			
	
	switch($cotizacion[0]["ID_TIPO_SERVICIO"]){
		case 13:
			
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_PIND.ID",
				"COTIZACIONES_TRAMITES_PIND.VIATICOS",
				"COTIZACIONES_TRAMITES_PIND.DESCUENTO",
				"COTIZACIONES_TRAMITES_PIND.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_PIND.AUMENTO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_PIND", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion=0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				
				
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_PIND", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				// Aqui calculo el costo de la auditoria que sin importar el tramite seria tarifa*cantidad_de_dias
				$costo_inicial = 0;
				$costo_total_visita_inspeccion =0;
				$costo_total_ensayos = 0;
				for ($i=0; $i < count($cotizacion_sitios) ; $i++) {
					$productos = [];
					$costo_visita_inspeccion =0;
					$costo_ensayos = 0;
					//Aqui se buscan los productos de la cotizacion
					$productos = $database->select("PROD_IND_SITIO", ["[>]PRODUCTOS_INDUSTRIALES" => ["ID_PI" => "ID"]], ["PRODUCTOS_INDUSTRIALES.NOMBRE","PRODUCTOS_INDUSTRIALES.VALOR"], ["AND"=>["ID_SITIO_PIND"=>$cotizacion_sitios[$i]["ID"],"ID_TRAMITE"=>$tramite_item["ID"]]]);
					valida_error_medoo_and_die();
					$productos1="";
					for($j=0;$j<count($productos);$j++){
						$productos1 .= $productos[$j]["NOMBRE"].",";
						if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1 && $etapa["ID"]!=15) {
							$costo_ensayos += $productos[$j]["VALOR"];
						}
					} 
		
					$cotizacion_sitios[$i]["PRODUCTOS_LISTA"] = $productos1;
					$cotizacion_sitios[$i]["PRODUCTOS"] = $productos;
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
			
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
					//	$total_dias_auditoria=$dias_base;
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
					//	$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = ($costo_inicial *  floatval(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $cotizacion_sitios[0]["CANTIDAD_DIAS"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = redondeado ($costo_inicial,2);
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = redondeado ($costo_desc,2);
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"];
		
						//$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				//$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = redondeado ($total_cotizacion,2);
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = redondeado ($total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100)),2);
			break;
		case 14:
			
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_DH.ID",
				"COTIZACIONES_TRAMITES_DH.VIATICOS",
				"COTIZACIONES_TRAMITES_DH.DESCUENTO",
				"COTIZACIONES_TRAMITES_DH.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_DH.AUMENTO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_DH", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion=0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				
				
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_DH", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				// Aqui calculo el costo de la auditoria que sin importar el tramite seria tarifa*cantidad_de_dias
				$costo_inicial = 0;
			
					if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
						$costo_inicial = $tarifa['TARIFA']*$cotizacion_sitios[0]["CANTIDAD_DIAS"];  
					}
					else{
						$costo_inicial = 0;
					}	
			
			
				$costo_inicial = $costo_inicial;
			
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
					//	$total_dias_auditoria=$dias_base;
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
					//	$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = ($costo_inicial *  floatval(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $cotizacion_sitios[0]["CANTIDAD_DIAS"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						//$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				//$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
			break;
		case 15:
			
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_HM.ID",
				"COTIZACIONES_TRAMITES_HM.VIATICOS",
				"COTIZACIONES_TRAMITES_HM.DESCUENTO",
				"COTIZACIONES_TRAMITES_HM.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_HM.AUMENTO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_HM", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion=0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				
				
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_HM", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				// Aqui calculo el costo de la auditoria que sin importar el tramite seria tarifa*cantidad_de_dias
				$costo_inicial = 0;
				for ($i=0; $i < count($cotizacion_sitios) ; $i++) {
					if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
						if($cotizacion_sitios[$i]["OPCION_8H_O_4H"] == 0){
							$costo_inicial += $tarifa['TARIFA']*0.5;  
						}
						else{
							$costo_inicial += $tarifa['TARIFA']; 
						} 
					}
					else{
						$costo_inicial += 0;
					}	
			
				}
				$costo_inicial = $costo_inicial;
			
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
					//	$total_dias_auditoria=$dias_base;
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
					//	$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = ($costo_inicial *  floatval(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $cotizacion_sitios[0]["CANTIDAD_DIAS"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						//$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				//$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
			break;	
		case 16:
		//Si la cotizacion tiene detalles es necesario cargar los datos de la tabla COTIZACION_DETALLES
			$cotizacion_detalles = $database->select("COTIZACION_DETALLES","*", [
				"ID_COTIZACION" => $id,
				]);
			valida_error_medoo_and_die();
			$cotizacion[0]["DETALLES"] = $cotizacion_detalles;
			
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_CIL.ID",
				"COTIZACIONES_TRAMITES_CIL.VIATICOS",
				"COTIZACIONES_TRAMITES_CIL.DESCUENTO",
				"COTIZACIONES_TRAMITES_CIL.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_CIL.AUMENTO",
			//	"COTIZACIONES_TRAMITES_CIL.REDUCCION",
				"COTIZACIONES_TRAMITES_CIL.DIAS_MULTISITIO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_CIL", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion = 0;
			$total_dias_cotizacion = 0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				$etapa_para_sgen = "VIGILANCIA";
				//Multiplicador para el calculo de sitios
				$const_dias = 1; //Default - Etapa certificacion
				if(strpos($nombre_auditoria, 'Vigilancia') !== false || strpos($nombre_auditoria, 'VIGILANCIA')){ // Vigilancia
					//$const_dias = 0.33;
					//$etapa_para_sgen = "VIGILANCIA";
				}
				else if(strpos($nombre_auditoria, 'Renovación') !== false || strpos($nombre_auditoria, 'Renovacion') !== false || strpos($nombre_auditoria, 'RENOVACIÓN')){ 
						// 		Renovacion
					//$const_dias = 0.66;
					//$etapa_para_sgen = "RENOVACION";
				}
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_CIL", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				$total_dias_auditoria = 0;
				$total_personas_tramite =0;
				
						for ($i=0; $i < count($cotizacion_sitios) ; $i++) {
							if ($cotizacion_sitios[$i]["SELECCIONADO"] == 1) {
								$total_personas_tramite +=$cotizacion_sitios[$i]["CANTIDAD_PERSONAS"];
								
							}
					
						}
						$dias = 0;
						//AQUI SE DEBE CALCULAR LA CANTIDAD DE DIAS BASE SEGUN LAS TABLAS QUE NOS DIERON
						$dias = $database->get("COTIZACION_EMPLEADOS_DIAS_CIL", "DIAS_AUDITOR_BASE",
								[
									"AND"=>[
												"ACTIVIDAD_ECONOMICA"=>$cotizacion[0]["DETALLES"][0]["VALOR"],
												"TOTAL_EMPLEADOS_MINIMO[<=]"=>$total_personas_tramite,
												"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$total_personas_tramite,
											]
									]);
						valida_error_medoo_and_die();
						$dias_base = round($dias * $const_dias);
						//A PARTIR DE AQUI CALCULO LOS DIAS PARA ENCUESTA
						//$n ES EL TAMANO DE LA MUESTRA Y SU FORMULA ES
						//$n = N*Z*Z*P*Q/(E*E*(N-1)+Z*Z*P*Q)
						// NECESITAMOS LOS VALORES DE Z,E,P Y Q
						$datos_encuesta = $database->get("COTIZACION_CONSTANTES_ENCUESTA_TIPO_AUDITORIA_CIL", "*", ["ID_TIPO_AUDITORIA"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
						valida_error_medoo_and_die();
						$tam_muestra = round(($total_personas_tramite*$datos_encuesta['Z']*$datos_encuesta['Z']*$datos_encuesta['P']*$datos_encuesta['Q'])/($datos_encuesta['E']*$datos_encuesta['E']*($total_personas_tramite-1)+$datos_encuesta['Z']*$datos_encuesta['Z']*$datos_encuesta['P']*$datos_encuesta['Q']));
							
						$dias_encuesta = $database->get("COTIZACION_EMPLEADOS_DIAS_ENCUESTA_CIL", "DIAS_AUDITOR",
								[
									"AND"=>[
												"TOTAL_EMPLEADOS_MINIMO[<=]"=>$tam_muestra,
												"TOTAL_EMPLEADOS_MAXIMO[>=]"=>$tam_muestra,
											]
									]);
						valida_error_medoo_and_die();	
						$dias_encuesta = round($dias_encuesta * $const_dias);
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						$total_dias_auditoria=$dias_base+$dias_encuesta+$tramite_item["DIAS_MULTISITIO"];
						//$total_dias_auditoria = round($total_dias_auditoria * (1 - ($tramite_item["REDUCCION"]/100) + ($tramite_item["AUMENTO"]/100) ));
						$costo_inicial = (($total_dias_auditoria - $dias_encuesta) * floatval($tarifa['TARIFA'])+$dias_encuesta*2000);
						//$costo_reducc = ($costo_inicial * (1 - ($tramite_item["REDUCCION"]/100) + ($tramite_item["AUMENTO"]/100) ));
						$costo_desc = (($total_dias_auditoria - $dias_encuesta)*  floatval($cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"]) +$dias_encuesta*2000);
						//$costo_total_red_amp = $costo_desc*(1-($tramite_item["REDUCCION"]/100) + ($tramite_item["AUMENTO"]/100));
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_ENCUESTA"] = $dias_encuesta;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_MULTISITIO"] = $tramite_item["DIAS_MULTISITIO"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $total_dias_auditoria;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["PERSONAS_ENCUESTA"] = $tam_muestra;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TOTAL_EMPLEADOS_TRAMITE"] = $total_personas_tramite;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
	
			
			break;
		case 17:
			
			
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_TUR.ID",
				"COTIZACIONES_TRAMITES_TUR.VIATICOS",
				"COTIZACIONES_TRAMITES_TUR.DESCUENTO",
				"COTIZACIONES_TRAMITES_TUR.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_TUR.AUMENTO",
			//	"COTIZACIONES_TRAMITES_CIL.REDUCCION",
			//	"COTIZACIONES_TRAMITES_TUR.DIAS_MULTISITIO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_TUR", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			
			$total_cotizacion = 0;
			$total_dias_cotizacion = 0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				$etapa_para_sgen = "VIGILANCIA";
				//Multiplicador para el calculo de sitios
				$const_dias = 1; //Default - Etapa certificacion
				if(strpos($nombre_auditoria, 'Vigilancia') !== false || strpos($nombre_auditoria, 'VIGILANCIA')){ // Vigilancia
					//$const_dias = 0.33;
					//$etapa_para_sgen = "VIGILANCIA";
				}
				else if(strpos($nombre_auditoria, 'Renovación') !== false || strpos($nombre_auditoria, 'Renovacion') !== false || strpos($nombre_auditoria, 'RENOVACIÓN')){ 
						// 		Renovacion
					//$const_dias = 0.66;
					//$etapa_para_sgen = "RENOVACION";
				}
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_TUR", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				$total_dias_auditoria = 0;
				if($normas[0]["ID_NORMA"]=='NMX-AA-120-SCFI-2006' || $normas[0]["ID_NORMA"]=='NMX-AA-120-SCFI-2016'){
					$dias = 0;
						//AQUI SE DEBE CALCULAR LA CANTIDAD DE DIAS BASE SEGUN LAS TABLAS QUE NOS DIERON
						$dias = $database->get("COTIZACION_LONGITUD_PLAYA_DIAS_TUR", ["DIAS","AUDITORES"],
							[
									"AND"=>[
												"LONGITUD_PLAYA_MIN[<=]"=>$cotizacion_sitios[0]["LONGITUD_PLAYA"],
												"LONGITUD_PLAYA_MAX[>=]"=>$cotizacion_sitios[0]["LONGITUD_PLAYA"],
											]
									]);
						valida_error_medoo_and_die();
						if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
							$dias_base = $dias["DIAS"]*$dias["AUDITORES"];
						}
						else{
							$dias_base=0;
						}	
						
				}
				if($normas[0]["ID_NORMA"]=='NMX-AA-133-SCFI-2013'){
					if($etapa["ID"]>=17 && $etapa["ID"]<=23 ){
						if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
							$dias_base = 2;
						}
						else{
							$dias_base=0;
						}	
					}
					else{
						if($etapa["ID"]==15 ){
							if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
								$dias_base = 1;
							}
							else{
								$dias_base=0;
							}	
						}
						else{
							if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
								$dias_base = 3;
							}
							else{
								$dias_base=0;
							}
						}
					}
				}
				if($normas[0]["ID_NORMA"]=='NMX-TT-009-IMNC-2004'){
					if($etapa["ID"]>=17 && $etapa["ID"]<=23 ){
						if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
							$dias_base = 1;
						}
						else{
							$dias_base=0;
						}	
					}
					else{
						if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
							$dias_base = 1;
						}
						else{
							$dias_base=0;
						}	
					}
				}	
						
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						$total_dias_auditoria=$dias_base;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
						$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = (($total_dias_auditoria) *  floatval($cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"]));
						
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $total_dias_auditoria;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["LONGITUD_PLAYA"] = $cotizacion_sitios[0]["LONGITUD_PLAYA"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
	
			break;
		case 18:
			//Si la cotizacion tiene detalles es necesario cargar los datos de la tabla COTIZACION_DETALLES
			$cotizacion_detalles = $database->select("COTIZACION_DETALLES","*", [
				"ID_COTIZACION" => $id,
				]);
			valida_error_medoo_and_die();
			$cotizacion[0]["DETALLES"] = $cotizacion_detalles;
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_INF_COM.ID",
				"COTIZACIONES_TRAMITES_INF_COM.VIATICOS",
				"COTIZACIONES_TRAMITES_INF_COM.DESCUENTO",
				"COTIZACIONES_TRAMITES_INF_COM.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_INF_COM.AUMENTO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_INF_COM", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion=0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				
				
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_INF_COM", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				// Aqui calculo el costo de la auditoria dependiendo de la etapa por la que esta pasando, teniendo en cuenta que el costo de la auditoria es fijo para cada etapa.
				$costo_inicial = 0;
			
					if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
						$costo_inicial = $tarifa['TARIFA'];  
					}
					else{
						$costo_inicial = 0;
					}	
			
			
				$costo_inicial = $costo_inicial;
			
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
					//	$total_dias_auditoria=$dias_base;
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
					//	$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = ($costo_inicial *  floatval(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						
						//$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $total_dias_auditoria;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						//$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				//$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
			break;
		case 19:
			$campos_tramite = [
				"COTIZACIONES_TRAMITES_CPER.ID",
				"COTIZACIONES_TRAMITES_CPER.VIATICOS",
				"COTIZACIONES_TRAMITES_CPER.DESCUENTO",
				"COTIZACIONES_TRAMITES_CPER.ID_TIPO_AUDITORIA",
				"COTIZACIONES_TRAMITES_CPER.AUMENTO",
				"I_SG_AUDITORIAS_TIPOS.TIPO"
			];
			$tramites =  $database->select("COTIZACIONES_TRAMITES_CPER", 
				["[>]I_SG_AUDITORIAS_TIPOS" => ["ID_TIPO_AUDITORIA" => "ID"]], $campos_tramite,
				["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
				valida_error_medoo_and_die();
			$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;
			$total_cotizacion=0;
			foreach ($tramites as $key => $tramite_item) {
				//En vez de usar la etapa usar el tipo de auditoria
				//$etapa = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$tramite_item["ID_ETAPA_PROCESO"]]);
				$etapa = $database->get("I_SG_AUDITORIAS_TIPOS", "*", ["ID"=>$tramite_item["ID_TIPO_AUDITORIA"]]);
				//Sustituyo $etapa["ETAPA"] por nombre_auditoria
				$nombre_auditoria = $etapa["TIPO"];
				valida_error_medoo_and_die();
				$etapa_para_sgen = "VIGILANCIA";
				
				//AQUI VENDRIA LA PARTE DE CODIGO QUE TIENE Q VER CON TARIFA ADICIONAL PERO COMO NO LO HAN PEDIDO PUES LO DEJAMOS EN BLANCO
				//{..........}		
				
				$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
						"*", ["AND"=>["ID_TRAMITE"=>$tramite_item["ID"],"ID_COTIZACION"=>$cotizacion[0]["ID"]]]);
				valida_error_medoo_and_die();

				$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
				//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
				$cotizacion_sitios = $database->select("COTIZACION_SITIOS_CPER", "*", ["ID_COTIZACION"=>$tramite_item["ID"]]);
				valida_error_medoo_and_die();

				// Aqui calculo el costo de la auditoria dependiendo de la etapa por la que esta pasando, teniendo en cuenta que el costo de la auditoria es fijo para cada etapa.
				$costo_inicial = 0;
				if($etapa["ID"]>=17 && $etapa["ID"]<=23 ){// Este es el costo inicial y fijo para Vigilancias
					if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
						$costo_inicial = 2000;  
					}
					else{
						$costo_inicial = 0;
					}	
				}
			if($etapa["ID"]==16  ){// Este es el costo inicial y fijo para Renovacion
				if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
					$costo_inicial = 5000;  
				}
				else{
					$costo_inicial = 0;
				}	
			}
			if($etapa["ID"]==14  ){// Este es el costo inicial y fijo para Certificacion
				if ($cotizacion_sitios[0]["SELECCIONADO"] == 1) {
					$costo_inicial = 9300;  
				}
				else{
					$costo_inicial = 0;
				}	
			}
			$costo_inicial = $costo_inicial*$cotizacion_sitios[0]["CANTIDAD_PERSONAS"];
			
						
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_DES"] = (floatval($tarifa['TARIFA'])*(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
					//	$total_dias_auditoria=$dias_base;
					//	$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_BASE"] = $dias_base;
					//	$costo_inicial = (($total_dias_auditoria) * floatval($tarifa['TARIFA']));
						$costo_desc = ($costo_inicial *  floatval(1-($tramite_item["DESCUENTO"]/100)+($tramite_item["AUMENTO"]/100)));
						
						//$cotizacion[0]["COTIZACION_TRAMITES"][$key]["DIAS_AUDITORIA"] = $total_dias_auditoria;
						//$cotizacion[0]["COTIZACION_TRAMITES"][$key]["LONGITUD_PLAYA"] = $cotizacion_sitios[0]["LONGITUD_PLAYA"];
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TARIFA_ADICIONAL"] = $total_tarifa_adicional;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO"] = $costo_inicial;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_DES"] = $costo_desc;
						$cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"] = $costo_desc + $tramite_item["VIATICOS"]+$total_tarifa_adicional;
		
						//$total_dias_cotizacion += $total_dias_auditoria;
						$total_cotizacion += $cotizacion[0]["COTIZACION_TRAMITES"][$key]["TRAMITE_COSTO_TOTAL"];
				}
				//$cotizacion[0]["TOTAL_DIAS_COTIZACION"] = $total_dias_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION"] = $total_cotizacion;
				$cotizacion[0]["TOTAL_COTIZACION_DES"] = $total_cotizacion * (1-($cotizacion[0]["DESCUENTO"]/100)+($cotizacion[0]["AUMENTO"]/100));
			break;
		default: 
			break;
	}
}
//Codigo si el tipo de servicio pertenece a CIFA
if($cotizacion[0]["ID_SERVICIO"] == 3){
	//Necesito de COTIZACION_DETALLE
	//MODALIDAD, ID_CURSO
	$modalidad = "";
	$id_curso = 0;
	$cantidad_participantes = 0;
	$solo_cliente = 0;
	$tiene_servicio = 0;
	$meta = $database->select("COTIZACION_DETALLES","*",["ID_COTIZACION" => $id]);
	foreach($meta as $data){
		if($data["DETALLE"] == "MODALIDAD"){
			$modalidad = $data["VALOR"];
		}
		if($data["DETALLE"] == "ID_CURSO"){
			$id_curso = $data["VALOR"];
		}
		if($data["DETALLE"] == "CANT_PARTICIPANTES"){
			$cantidad_participantes = $data["VALOR"];
		}
		if($data["DETALLE"] == "SOLO_CLIENTE"){
			$solo_cliente = $data["VALOR"];
		}
		if($data["DETALLE"] == "TIENE_SERVICIO"){
			$tiene_servicio = $data["VALOR"];
		}
	}
	$cotizacion[0]["MODALIDAD"] = $modalidad;
	if($modalidad == 'programado'){		
		$query = "SELECT C.ID_CURSO,C.NOMBRE,C.PRECIO_PROGRAMADO,C.DIAS_PROGRAMADO FROM CURSOS C INNER JOIN CURSOS_PROGRAMADOS CP ON C.ID_CURSO = CP.ID_CURSO WHERE CP.ID =" . $id_curso;
		$NOMBRE_CURSO = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
		$cotizacion[0]["NOMBRE_CURSO"] = $NOMBRE_CURSO[0]["NOMBRE"];
		$cotizacion[0]["ID_CURSO_PROGRAMADO"] = $id_curso;
		$cotizacion[0]["ID_CURSO"] = $NOMBRE_CURSO[0]["ID"];
		$cotizacion[0]["CANT_PARTICIPANTES"] = $cantidad_participantes;
		$cotizacion[0]["SOLO_CLIENTE"] = $solo_cliente;
		$cotizacion[0]["TIENE_SERVICIO"] = $tiene_servicio;
		$cotizacion[0]["CURSOS"] = $NOMBRE_CURSO;
		//Aqui la parte que tiene que ver con TARIFA ADICIONAL
		
		$campos_t = [
				"COTIZACION_TARIFA_ADICIONAL.ID",
				"COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE",
				"COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL",
				"COTIZACION_TARIFA_ADICIONAL.CANTIDAD",
				"TARIFA_COTIZACION_ADICIONAL.DESCRIPCION",
				"TARIFA_COTIZACION_ADICIONAL.TARIFA"
		];
		$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
		$campos_t, ["AND"=>["ID_TRAMITE"=>0,"ID_COTIZACION"=>$id]]);
		valida_error_medoo_and_die();
		
		$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
		//FINAL DE LA PARTE TARIFA ADICIONAL
		//Aqui se calcula el costo que seria tarifa*cantidad_de_dias
		$total_cotizacion = $NOMBRE_CURSO[0]["PRECIO_PROGRAMADO"]*$cantidad_participantes + $total_tarifa_adicional;
		
	} else if($modalidad == 'insitu'){
		$data = $database->get("CURSOS", "*", ["ID_CURSO"=>$id_curso]);
		$cotizacion[0]["NOMBRE_CURSO"] = $data["NOMBRE"];
		$cotizacion[0]["ID_CURSO"] = $id_curso;
		$cotizacion[0]["CANT_PARTICIPANTES"] = $cantidad_participantes;
		$cotizacion[0]["SOLO_CLIENTE"] = $solo_cliente;
        $cotizacion[0]["TIENE_SERVICIO"] = $tiene_servicio;
		$cotizacion[0]["CURSOS"] = $data;
		
		//AQUI LA PARTE QUE TIENE QUE VER CON LA COTIZACION POR SITIOS
		$const_sitio = 0;
		$const_sitio = $database->count("COTIZACION_SITIOS_CIFA", ["AND"=>["ID_COTIZACION"=>$id,"SELECCIONADO"=>1]]);
		valida_error_medoo_and_die();
		//Aqui la parte que tiene que ver con TARIFA ADICIONAL
		
		$campos_t = [
				"COTIZACION_TARIFA_ADICIONAL.ID",
				"COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE",
				"COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL",
				"COTIZACION_TARIFA_ADICIONAL.CANTIDAD",
				"TARIFA_COTIZACION_ADICIONAL.DESCRIPCION",
				"TARIFA_COTIZACION_ADICIONAL.TARIFA"
		];
		$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
		$campos_t, ["AND"=>["ID_TRAMITE"=>0,"ID_COTIZACION"=>$id]]);
		valida_error_medoo_and_die();
		
		$total_tarifa_adicional = 0;
				for ($i=0; $i < count($cotizacion_tarifa_adicional); $i++) {
					$subtotal = $cotizacion_tarifa_adicional[$i]["TARIFA"] * $cotizacion_tarifa_adicional[$i]["CANTIDAD"];
					$total_tarifa_adicional += $subtotal;
				}
		//FINAL DE LA PARTE TARIFA ADICIONAL
		//Aqui se calcula el costo que seria tarifa*cantidad_de_dias
		$total_cotizacion = $data["PRECIO_INSITU"]*$data["DIAS_INSITU"]*$const_sitio + $total_tarifa_adicional;
	}
	$cotizacion[0]["COTIZACION_TARIFA_ADICIONAL"] = $cotizacion_tarifa_adicional;
	$cotizacion[0]["TOTAL_COTIZACION"] = redondeado($total_cotizacion,2);
}
$cotizacion[0]["SERVICIO"] = $servicio;
$cotizacion[0]["TIPOS_SERVICIO"] = $tipos_servicio;
$cotizacion[0]["NORMAS"] = $normas;
$cotizacion[0]["ESTADO"] = $estado;


print_r(json_encode($cotizacion));
?>
