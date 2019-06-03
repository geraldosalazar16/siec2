<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 

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
	$id_solicitud = $_REQUEST["id"]; 
	$data = $database->get("FACTURACION_SOLICITUDES", "*", ["ID"=>$id_solicitud]); 
	valida_error_medoo_and_die(); 
	print_r(json_encode($data)); 
?> 
