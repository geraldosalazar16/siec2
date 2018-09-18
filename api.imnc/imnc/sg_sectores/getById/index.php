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
			$mailerror->send("SG_SECTORES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id = $_REQUEST["id"]; 
	$sg_sector = $database->get("SG_SECTORES", "*", ["ID"=>$id]); 

	$sector_nombre = $database->get("SECTORES", "NOMBRE", ["ID_SECTOR"=>$sg_sector["ID_SECTOR"]]);
	$sg_sector["NOMBRE_SECTOR"] = $sector_nombre;

	valida_error_medoo_and_die(); 
	print_r(json_encode($sg_sector)); 
?> 
