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
			$mailerror->send("COTIZACION_SITIOS_PIND", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id = $_REQUEST["id"]; 
	$respuesta = $database->get("COTIZACION_SITIOS_PIND", "*", ["ID"=>$id]); 
	
	valida_error_medoo_and_die(); 
	
	$productos = $database->select("PROD_IND_SITIO", ["[>]PRODUCTOS_INDUSTRIALES" => ["ID_PI" => "ID"]], ["PRODUCTOS_INDUSTRIALES.ID","PRODUCTOS_INDUSTRIALES.NOMBRE","PRODUCTOS_INDUSTRIALES.VALOR"], ["AND"=>["ID_SITIO_PIND"=>$id,"ID_TRAMITE"=>$respuesta["ID_COTIZACION"]]]);
		valida_error_medoo_and_die();
	$respuesta["PRODUCTOS_INDUSTRIALES"]= $productos;
	print_r(json_encode($respuesta)); 
?> 
