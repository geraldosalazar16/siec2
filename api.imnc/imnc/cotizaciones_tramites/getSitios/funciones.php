<?php
	
	function count_sitios($ID_COTIZACION, $CONST){
		global $database;
		$SITIO_MATRIZ =  $database->query("
			SELECT COUNT(*) AS COUNT_MATRIZ
			FROM COTIZACION_SITIOS
			WHERE MATRIZ_PRINCIPAL = 'si' AND SELECCIONADO = 1 AND ID_COTIZACION =". $database->quote($ID_COTIZACION))->fetchAll(PDO::FETCH_ASSOC);

		valida_error_medoo_and_die(); 
		$mensaje_actividad_faltante = "";
		if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
			$mensaje_actividad_faltante = "Falta sitio matriz. ";
		}
			//Obtener numero de sitios por actividad
			$SITIO_TOTAL_ACTIVIDAD = $database->query("
				SELECT COUNT(*) AS COUNT_ACTIVIDAD, ID_ACTIVIDAD, ACTIVIDAD
				FROM COTIZACION_SITIOS AS COT
				INNER JOIN SG_ACTIVIDAD AS SAC
				ON COT.ID_ACTIVIDAD = SAC.ID
				WHERE MATRIZ_PRINCIPAL = 'no'  AND ID_COTIZACION =". $database->quote($ID_COTIZACION)
				."GROUP BY ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);

			$COUNT_SITIOS = array(); 
			$COUNT_SITIOS["SITIOS_A_VISITAR"] = 1; //el sitio matriz

			$COUNT_SITIOS["TOTAL_SITIOS"] = $database->count("COTIZACION_SITIOS", ["AND" => ["ID_COTIZACION"=>$ID_COTIZACION, "SELECCIONADO"=>1]]); 
			valida_error_medoo_and_die(); 
			
			$SITIO_AUX_ACTIVIDAD = $database->query("
				SELECT COUNT(*) AS COUNT_ACTIVIDAD, ID_ACTIVIDAD
				FROM COTIZACION_SITIOS AS COT
				WHERE MATRIZ_PRINCIPAL = 'no' AND SELECCIONADO = 1 AND ID_COTIZACION =". $database->quote($ID_COTIZACION)
				."GROUP BY ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);
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