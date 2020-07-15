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
 			$mailerror->send("COTIZACION_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
 			die(); 
 		} 
 	} 
 	$respuesta=array(); 
 	$json = file_get_contents("php://input"); 
 	$objeto = json_decode($json); 
	$ID_COTIZACION = $objeto->ID_COTIZACION; 
	$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO;
 	$ID = $objeto->ID; 

 	$TOTAL_EMPLEADOS = $objeto->TOTAL_EMPLEADOS; 
	valida_parametro_and_die($TOTAL_EMPLEADOS,"Falta total de empleados");
	$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
	valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");
	if($ID_TIPO_SERVICIO != 54){
		$NUMERO_EMPLEADOS_CERTIFICACION = $objeto->NUMERO_EMPLEADOS_CERTIFICACION;
		valida_parametro_and_die($NUMERO_EMPLEADOS_CERTIFICACION,"Falta número de empleados para certificación");
		$CANTIDAD_TURNOS = $objeto->CANTIDAD_TURNOS;
		valida_parametro_and_die($CANTIDAD_TURNOS,"Falta cantidad de turnos");
		$CANTIDAD_DE_PROCESOS = $objeto->CANTIDAD_DE_PROCESOS;
		valida_parametro_and_die($CANTIDAD_DE_PROCESOS,"Falta cantidad de procesos");
		$FACTOR_REDUCCION = $objeto->FACTOR_REDUCCION;
		valida_parametro_and_die($FACTOR_REDUCCION,"Falta el factor de reducción");
		$FACTOR_AMPLIACION = $objeto->FACTOR_AMPLIACION;
		valida_parametro_and_die($FACTOR_AMPLIACION,"Falta el factor de ampliación");
		$JUSTIFICACION = $objeto->JUSTIFICACION;
		valida_parametro_and_die($JUSTIFICACION,"Falta justificación del factor de reducción y ampliación");
		$ID_ACTIVIDAD = $objeto->ID_ACTIVIDAD;
		valida_parametro_and_die($ID_ACTIVIDAD,"Falta actividad");
	}
	else{
		$NUMERO_EMPLEADOS_CERTIFICACION = null;
		$CANTIDAD_TURNOS =null;
		$CANTIDAD_DE_PROCESOS = null;
		$FACTOR_REDUCCION = 0;
		$FACTOR_AMPLIACION = 0;
		$JUSTIFICACION = 'N/A';
		$ID_ACTIVIDAD = null;
	}
	
	$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO;
	valida_parametro_and_die($TEMPORAL_O_FIJO,"Falta seleccionar si es temporal o fijo");
	$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
	valida_parametro_and_die($MATRIZ_PRINCIPAL,"Falta seleccionar si es matriz principal");
	

	if(!is_numeric($FACTOR_REDUCCION)){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de reducción debe ser un número"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}
	if($FACTOR_REDUCCION < 0 || $FACTOR_REDUCCION > 30){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de reducción no puede ser menor al 0% ni mayor al 30%"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}
	if(!is_numeric($FACTOR_AMPLIACION)){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de ampliación debe ser un número"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}

	if($FACTOR_AMPLIACION < 0 || $FACTOR_AMPLIACION > 30){
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = "El Factor de ampliación no puede ser menor al 0% ni mayor al 30%"; 
		print_r(json_encode($respuesta)); 
		die(); 
	}

 	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
	$FECHA_MODIFICACION = date("Ymd");
	$HORA_MODIFICACION = date("His");

	if(($database->count("COTIZACION_SITIOS",['AND'=>["ID_COTIZACION"=>$ID_COTIZACION,"MATRIZ_PRINCIPAL" => 'si']])!=0) && $MATRIZ_PRINCIPAL == 'si'){
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = "Ya existe un sitio matriz para esta cotizacion"; 
	print_r(json_encode($respuesta)); 
	die(); 
}
	
 	$id = $database->update("COTIZACION_SITIOS", [
		"TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS, 
		"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
		"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION,
		"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS,
		"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS,
		"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO,
		"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL,
		"FACTOR_REDUCCION" => $FACTOR_REDUCCION,
		"FACTOR_AMPLIACION" => $FACTOR_AMPLIACION,
		"JUSTIFICACION" => $JUSTIFICACION,
		"ID_ACTIVIDAD" => $ID_ACTIVIDAD,
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		//"HORA_MODIFICACION" => $HORA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
 	], ["ID"=>$ID]); 
 	valida_error_medoo_and_die(); 
 	$respuesta["resultado"]="ok"; 
 	print_r(json_encode($respuesta)); 
?> 
