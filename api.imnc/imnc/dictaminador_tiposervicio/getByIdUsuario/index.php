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
	$id_usuario = $_REQUEST["id_usuario"]; 
	valida_parametro_and_die($id_usuario,"Falta ID de USUARIO");
	
	$respuesta = $database->get("USUARIOS",
									
									[
										"USUARIOS.ID",
										"USUARIOS.NOMBRE(NOMBRE_DICTAMINADOR)",
										
									],
									[
										"USUARIOS.ID"=>$id_usuario
									]
									
									);
	valida_error_medoo_and_die(); 
	
	$tipos_servicio = [];
	$lista_tipos_servicio = "";
	$tipos_servicio = $database->select("DICTAMINADOR_TIPO_SERVICIO",
									[
										"[><]TIPOS_SERVICIO"=>["DICTAMINADOR_TIPO_SERVICIO.ID_TIPO_SERVICIO"=>"ID"]
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_TIPO_SERVICIO",
										"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPO_SERVICIO)"
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_USUARIO"=>$id_usuario
									]
									
									);
	valida_error_medoo_and_die(); 
			
	for($i=0;$i<count($tipos_servicio);$i++){
		$lista_tipos_servicio .= " ".$tipos_servicio[$i]["NOMBRE_TIPO_SERVICIO"].",";
	}
	$respuesta["LISTA_TIPO_SERVICIO"]= $lista_tipos_servicio;
	$respuesta["resultado"]="ok";
	print_r(json_encode($respuesta)); 
?> 
