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
			$mailerror->send("SG_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id = $_REQUEST["id"]; 
	$sectores = $database->get("SG_SITIOS", "*", ["ID"=>$id]); 
	if($sectores["ID_ACTIVIDAD"] == 0)
		$sectores["ACTIVIDAD"] = "";
	else	
		$sectores["ACTIVIDAD"] = $database->get("SG_ACTIVIDAD", "ACTIVIDAD", ["ID"=>$sectores["ID_ACTIVIDAD"]]);
	
	valida_error_medoo_and_die(); 
	print_r(json_encode($sectores)); 
?> 
