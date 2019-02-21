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
			die(); 
		} 
	} 
	$id_usuario = $_REQUEST["id_usuario"]; 
	valida_parametro_and_die($id_usuario,"Falta ID de USUARIO");
	
	$campos = [
				"DICTAMINACIONES.ID",
				"DICTAMINACIONES.ID_SERVICIO_CLIENTE_ETAPA",
				"DICTAMINACIONES.TIPO_AUDITORIA",
				"DICTAMINACIONES.CICLO",
				"DICTAMINACIONES.ID_DICTAMINADOR",
				"DICTAMINACIONES.STATUS",
				"DICTAMINACIONES.FECHA_CREACION",
				"DICTAMINACIONES.FECHA_MODIFICACION",
				"DICTAMINACIONES.ID_USUARIO_CREACION",
				"CLIENTES.NOMBRE(NOMBRE_CLIENTE)",
				"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
				"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPO_SERVICIO)",
				"I_SG_AUDITORIAS_TIPOS.TIPO(NOMBRE_TIPO_AUDITORIA)",
				"USUARIOS.NOMBRE(NOMBRE_ASIGNADOR)"
				
	
	];
	
	$respuesta = $database->select("DICTAMINACIONES",
									[
										"[><]SERVICIO_CLIENTE_ETAPA"=>["DICTAMINACIONES.ID_SERVICIO_CLIENTE_ETAPA"=>"ID"],
										"[><]CLIENTES"=>["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE"=>"ID"],
										"[><]SERVICIOS"=>["SERVICIO_CLIENTE_ETAPA.ID_SERVICIO"=>"ID"],
										"[><]TIPOS_SERVICIO"=>["SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO"=>"ID"],
										"[><]I_SG_AUDITORIAS_TIPOS"=>["DICTAMINACIONES.TIPO_AUDITORIA"=>"ID"],
										"[><]USUARIOS"=>["DICTAMINACIONES.ID_USUARIO_CREACION"=>"ID"]
										
									]	
									,$campos,
									[
										"AND"=>[
												"DICTAMINACIONES.ID_DICTAMINADOR"=>$id_usuario,
												"STATUS"=>'0'
												]
									]
									
									);
	valida_error_medoo_and_die(); 
	//$respuesta["resultado"]="ok";
	print_r(json_encode($respuesta)); 
?> 
