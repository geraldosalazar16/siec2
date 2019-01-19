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
 			$mailerror->send("COTIZACION_SITIOS_CPER", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
 			die(); 
 		} 
 	} 
 	$respuesta=array(); 
 	$json = file_get_contents("php://input"); 
 	$objeto = json_decode($json); 
 	$ID = $objeto->ID; 

 	
	$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
	valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");

	$CANTIDAD_PERSONAS = $objeto->CANTIDAD_PERSONAS;
	valida_parametro_and_die($CANTIDAD_PERSONAS,"Falta la cantidad de personas");

 	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
	$FECHA_MODIFICACION = date("Ymd");
//	$HORA_MODIFICACION = date("His");

 	$id = $database->update("COTIZACION_SITIOS_CPER", [
		"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
		"CANTIDAD_PERSONAS" => $CANTIDAD_PERSONAS,
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
 	], ["ID"=>$ID]); 
 	valida_error_medoo_and_die(); 
 	$respuesta["resultado"]="ok"; 
 	print_r(json_encode($respuesta)); 
?> 
