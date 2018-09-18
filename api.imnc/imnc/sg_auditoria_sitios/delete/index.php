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
			$mailerror->send("SG_AUDITORIA_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id = $_REQUEST["id"]; 
	/*
	$sitio_auditoria = $database->get("SG_AUDITORIA_SITIOS", "*", ["ID"=>$id]);
	valida_error_medoo_and_die();
	$auditoria = $database->get("SG_AUDITORIAS", "*", ["ID"=>$sitio_auditoria["ID_SG_AUDITORIA"]]);
	valida_error_medoo_and_die();
	$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "*", ["ID"=>$auditoria["ID_SG_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die();
	if($tipo_servicio["ID_TIPO_SERVICIO"] == "CSGA" || $tipo_servicio["ID_TIPO_SERVICIO"] == "CSGC"){
		$DIA_ANT = $sitio_auditoria["DIAS_AUDITORIAS"];
		$DIAS_AUDITORIA = $auditoria["DURACION_DIAS"] - $DIA_ANT;
		$id_aud = $database->update("SG_AUDITORIAS", [ 
		"DURACION_DIAS" => $DIAS_AUDITORIA
		], ["ID"=>$sitio_auditoria["ID_SG_AUDITORIA"]]);
	}
	*/
	$database->delete("SG_AUDITORIA_SITIOS", ["ID"=>$id]); 
	valida_error_medoo_and_die(); 

	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
