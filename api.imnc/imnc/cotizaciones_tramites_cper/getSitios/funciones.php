<?php
	
	function count_sitios($ID_COTIZACION, $CONST){
		global $database;
		$SITIO_MATRIZ =  $database->query("
			SELECT COUNT(*) AS COUNT_MATRIZ
			FROM COTIZACION_SITIOS_CPER
			WHERE SELECCIONADO = 1 AND ID_COTIZACION =". $database->quote($ID_COTIZACION))->fetchAll(PDO::FETCH_ASSOC);

		valida_error_medoo_and_die(); 
		$mensaje_actividad_faltante = "";
	/*	if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
			$mensaje_actividad_faltante = "Falta sitio matriz. ";
		}*/
	
			$COUNT_SITIOS = array(); 
			$COUNT_SITIOS["SITIOS_A_VISITAR"] = 1; //el sitio matriz
			//AQUI VAMOS A BUSCAR TODOS LOS SITIOS ASOCIADOS A ESTA COTIZACION
			$COUNT_SITIOS["SITIOS_A_VISITAR"] = $database->count("COTIZACION_SITIOS_CPER", ["ID_COTIZACION"=>$ID_COTIZACION]); 
			valida_error_medoo_and_die(); 
			//
			$COUNT_SITIOS["TOTAL_SITIOS"] = $database->count("COTIZACION_SITIOS_CPER", ["AND" => ["ID_COTIZACION"=>$ID_COTIZACION, "SELECCIONADO"=>1]]); 
			valida_error_medoo_and_die(); 
			if($COUNT_SITIOS["TOTAL_SITIOS"]>1){
				$COUNT_SITIOS["SITIOS_ID"] = $database->select("COTIZACION_SITIOS_CPER","*", ["AND" => ["ID_COTIZACION"=>$ID_COTIZACION, "SELECCIONADO"=>1]]); 
			}

		$COUNT_SITIOS["RESTRICCIONES_SITIOS"] = $mensaje_actividad_faltante;
		return $COUNT_SITIOS;
	}

?>