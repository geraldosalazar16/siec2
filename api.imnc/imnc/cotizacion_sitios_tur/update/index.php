<?php 
 	include  '../../common/conn-apiserver.php';  
 	include  '../../common/conn-medoo.php';  
 	include  '../../common/conn-sendgrid.php';  
 	function valida_parametro_and_die($parametro, $mensaje_error){ 
 		$parametro = "" . $parametro; 
 		if ($parametro == "") { 
 			$respuesta["resultado"] = "error\n"; 
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
 			$mailerror->send("COTIZACION_SITIOS_TUR", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
 			die(); 
 		} 
 	} 
 	$respuesta=array(); 
 	$json = file_get_contents("php://input"); 
 	$objeto = json_decode($json); 
 	$ID = $objeto->ID; 

 	$LONGITUD_PLAYA = $objeto->LONGITUD_PLAYA; 
	valida_parametro_and_die($LONGITUD_PLAYA,"Falta CANTIDAD_PERSONAS");
	$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
	valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");
/*	$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO;
	valida_parametro_and_die($TEMPORAL_O_FIJO,"Falta seleccionar si es temporal o fijo");
	$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
	valida_parametro_and_die($MATRIZ_PRINCIPAL,"Falta seleccionar si es matriz principal");
	$FACTOR_REDUCCION = $objeto->FACTOR_REDUCCION;
	valida_parametro_and_die($FACTOR_REDUCCION,"Falta el factor de reducción");
	$FACTOR_AMPLIACION = $objeto->FACTOR_AMPLIACION;
	valida_parametro_and_die($FACTOR_AMPLIACION,"Falta el factor de ampliación");
	$JUSTIFICACION = $objeto->JUSTIFICACION;
	valida_parametro_and_die($JUSTIFICACION,"Falta justificación del factor de reducción y ampliación");
	
	}*/

 	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
	$FECHA_MODIFICACION = date("Ymd");
//	$HORA_MODIFICACION = date("His");

 	$id = $database->update("COTIZACION_SITIOS_TUR", [
		"LONGITUD_PLAYA" => $LONGITUD_PLAYA,
		"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
//		"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO,
//		"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL,
//		"FACTOR_REDUCCION" => $FACTOR_REDUCCION,
//		"FACTOR_AMPLIACION" => $FACTOR_AMPLIACION,
//		"JUSTIFICACION" => $JUSTIFICACION,
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
 	], ["ID"=>$ID]); 
 	valida_error_medoo_and_die(); 
 	$respuesta["resultado"]="ok"; 
 	print_r(json_encode($respuesta)); 
?> 
