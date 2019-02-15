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
 			$mailerror->send("COTIZACION_SITIOS_PIND", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
 			die(); 
 		} 
 	} 
 	$respuesta=array(); 
 	$json = file_get_contents("php://input"); 
 	$objeto = json_decode($json); 
 	$ID = $objeto->ID; 
	$ID_COTIZACION = $objeto->ID_COTIZACION; 
 	
	$ID_DOMICILIO_SITIO = $objeto->ID_DOMICILIO_SITIO;
	valida_parametro_and_die($ID_DOMICILIO_SITIO,"Falta domicilio");
	
	
	$ALCANCE = $objeto->ALCANCE;
	valida_parametro_and_die($ALCANCE,"Falta el ALCANCE");
	
	$TEMPORAL_O_FIJO = $objeto->TEMPORAL_O_FIJO;
	valida_parametro_and_die($TEMPORAL_O_FIJO,"Falta seleccionar si es temporal o fijo");
	$MATRIZ_PRINCIPAL = $objeto->MATRIZ_PRINCIPAL;
	valida_parametro_and_die($MATRIZ_PRINCIPAL,"Falta seleccionar si es matriz principal");

	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
	$FECHA_MODIFICACION = date("Ymd");
//	$HORA_MODIFICACION = date("His");

/////////////////////////////////////
//Aqui se verifican los productos
////////////////////////////////////
	$PRODUCTOS = $objeto->PRODUCTOS_INDUSTRIALES;
	if(count($PRODUCTOS) == 0){ 
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Es necesario seleccionar algun producto";
		print_r(json_encode($respuesta));
		die();
	}

 	$id = $database->update("COTIZACION_SITIOS_PIND", [
		"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
		"ALCANCE" =>	$ALCANCE,
		"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO,
		"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL,
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
 	], ["ID"=>$ID]); 
 	valida_error_medoo_and_die(); 
	
	//PARA ACTUALIZAR LAS NORMAS
	//Borrar todos las productos
	$id = $database->delete("PROD_IND_SITIO", 
		[
			"AND" => [
				"ID_SITIO_PIND" => $ID,
				"ID_TRAMITE" => $ID_COTIZACION
			]		
		]);
	for ($i=0; $i < count($PRODUCTOS); $i++) {
		$id_producto = $PRODUCTOS[$i]->ID;
		$id_cotizacion_productos_sitios = $database->insert("PROD_IND_SITIO", [
			"ID_SITIO_PIND" => $ID,
			"ID_PI" => $id_producto,
			"ID_TRAMITE" => $ID_COTIZACION
		]);
		valida_error_medoo_and_die();
	}
 	$respuesta["resultado"]="ok"; 
 	print_r(json_encode($respuesta)); 
?> 
