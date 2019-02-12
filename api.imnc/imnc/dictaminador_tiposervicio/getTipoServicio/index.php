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
			$mailerror->send("DICTAMINADOR_TIPO_SERVICIO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id_ts = $_REQUEST["id_ts"]; 
	valida_parametro_and_die($id_ts,"Falta ID de TIPO DE SERVICIO");
	$respuesta = $database->select("DICTAMINADOR_TIPO_SERVICIO",
									[
										"[><]USUARIOS"=>["DICTAMINADOR_TIPO_SERVICIO.ID_USUARIO"=>"ID"]
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_USUARIO(ID)",
										"USUARIOS.NOMBRE(NOMBRE_USUARIO)"
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_TIPO_SERVICIO"=>$id_ts
									]
									
									);
	valida_error_medoo_and_die(); 
	print_r(json_encode($respuesta)); 
?> 
