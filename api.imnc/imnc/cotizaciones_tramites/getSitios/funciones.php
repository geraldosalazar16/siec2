<?php
	
	function count_sitios($ID_TRAMITE,$ID_COTIZACION, $CONST){
		global $database;
		$SITIO_MATRIZ =  $database->query("
			SELECT COUNT(*) AS COUNT_MATRIZ
			FROM COTIZACION_TRAMITES_SITIOS AS CTS
			JOIN COTIZACION_SITIOS AS CS ON CTS.ID_SITIO = CS.ID
			WHERE CS.MATRIZ_PRINCIPAL = 'si' AND CTS.SELECCIONADO = 1 AND CTS.ID_TRAMITE =". $database->quote($ID_TRAMITE)." AND CS.ID_COTIZACION = ".$database->quote($ID_COTIZACION))->fetchAll(PDO::FETCH_ASSOC);

		valida_error_medoo_and_die(); 
		$mensaje_actividad_faltante = "";
		if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
			$mensaje_actividad_faltante = "Falta sitio matriz. ";
		}
			//Obtener numero de sitios por actividad
			$SITIO_TOTAL_ACTIVIDAD = $database->query("
				SELECT COUNT(*) AS COUNT_ACTIVIDAD, CS.ID_ACTIVIDAD, SAC.ACTIVIDAD
				FROM COTIZACION_TRAMITES_SITIOS AS CTS
				JOIN COTIZACION_SITIOS AS CS ON CTS.ID_SITIO = CS.ID
				INNER JOIN SG_ACTIVIDAD AS SAC
				ON CS.ID_ACTIVIDAD = SAC.ID
				WHERE CS.MATRIZ_PRINCIPAL = 'no'  AND CTS.ID_TRAMITE =". $database->quote($ID_TRAMITE)." AND CS.ID_COTIZACION = ".$database->quote($ID_COTIZACION)
				." GROUP BY CS.ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);

			$COUNT_SITIOS = array(); 
			$COUNT_SITIOS["SITIOS_A_VISITAR"] = 1; //el sitio matriz
			$SITIO_COUNT =  $database->query("
			SELECT COUNT(*) AS COUNT_SITIOS
			FROM COTIZACION_TRAMITES_SITIOS AS CTS
			JOIN COTIZACION_SITIOS AS CS ON CTS.ID_SITIO = CS.ID
			WHERE  CTS.SELECCIONADO = 1 AND CTS.ID_TRAMITE =". $database->quote($ID_TRAMITE)." AND CS.ID_COTIZACION = ".$database->quote($ID_COTIZACION))->fetchAll(PDO::FETCH_ASSOC);

			valida_error_medoo_and_die(); 
			$COUNT_SITIOS["TOTAL_SITIOS"] = $SITIO_COUNT[0]["COUNT_SITIOS"];
			//$COUNT_SITIOS["TOTAL_SITIOS"] = $database->count("COTIZACION_TRAMITES_SITIOS", ["AND" => ["ID_TRAMITE"=>$ID_TRAMITE, "SELECCIONADO"=>1]]); 
			valida_error_medoo_and_die(); 
			
			$SITIO_AUX_ACTIVIDAD = $database->query("
				SELECT COUNT(*) AS COUNT_ACTIVIDAD, ID_ACTIVIDAD
				FROM COTIZACION_TRAMITES_SITIOS AS CTS
				JOIN COTIZACION_SITIOS AS CS ON CTS.ID_SITIO = CS.ID
				WHERE CS.MATRIZ_PRINCIPAL = 'no' AND CTS.SELECCIONADO = 1 AND CTS.ID_TRAMITE =". $database->quote($ID_TRAMITE)." AND CS.ID_COTIZACION = ".$database->quote($ID_COTIZACION)
				."GROUP BY CS.ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);
			$SITIO_ASOCIADO_ACTIVIDAD = array();

			foreach ($SITIO_AUX_ACTIVIDAD as $key => $actividad) {
				$SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]] = $actividad["COUNT_ACTIVIDAD"];
			}
			// Restricción de raiz cuadrada de total de sitios por actividad
			foreach ($SITIO_TOTAL_ACTIVIDAD as $key => $actividad) {
				$aux_actividad = ceil(sqrt($actividad["COUNT_ACTIVIDAD"]) * $CONST);
				$COUNT_SITIOS["SITIOS_A_VISITAR"] += $aux_actividad;
				if( !array_key_exists($actividad["ID_ACTIVIDAD"], $SITIO_ASOCIADO_ACTIVIDAD)){
					$mensaje_actividad_faltante .= "\nFaltan ".$aux_actividad." sitios de la actividad ".$actividad["ACTIVIDAD"].". ";
				}
				else if( array_key_exists($actividad["ID_ACTIVIDAD"], $SITIO_ASOCIADO_ACTIVIDAD) && 
					$SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]] < $aux_actividad){
					$actv_faltante = $aux_actividad - $SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]];
					$mensaje_actividad_faltante .= "\nFaltan ".$actv_faltante." sitios de la actividad ".$actividad["ACTIVIDAD"].". ";
				}
			}

		$COUNT_SITIOS["RESTRICCIONES_SITIOS"] = $mensaje_actividad_faltante;
		return $COUNT_SITIOS;
	}

?>