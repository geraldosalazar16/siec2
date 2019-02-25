<?php  
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/email.php'; 

	function imprime_error_and_die($mensaje){
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje;
		print_r(json_encode($respuesta));
		die();
	}

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
	
	$nombre_tabla = "DICTAMINACIONES";
					 
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 
	
	$ID_SCE = $objeto->ID_SCE; 
	valida_parametro_and_die($ID_SCE,"Falta ID de SERVICIO CONTRATADO");
	$ID_TA= $objeto->ID_TA; 
	valida_parametro_and_die($ID_TA,"Falta ID de TIPO DE AUDITORIA");
	$CICLO= $objeto->CICLO; 
	valida_parametro_and_die($CICLO,"Falta el CICLO");
	$ID_DICTAMINADOR= $objeto->ID_DICTAMINADOR; 
	valida_parametro_and_die($ID_DICTAMINADOR,"Falta el DICTAMINADOR");
	$ID_USUARIO_CREACION= $objeto->ID_USUARIO_CREACION; 
	valida_parametro_and_die($ID_USUARIO_CREACION,"Falta el ID_USUARIO_CREACION");
	$FECHA_CREACION = date("Ymd");
	
	if($database->count("DICTAMINACIONES", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $ID_SCE,"TIPO_AUDITORIA" => $ID_TA, "CICLO" => $CICLO ]]) == 0){
		$id = $database->insert("DICTAMINACIONES", [			
			"ID_SERVICIO_CLIENTE_ETAPA" => $ID_SCE, 
			"TIPO_AUDITORIA" => $ID_TA,
			"CICLO" => $CICLO,
			"ID_DICTAMINADOR" => $ID_DICTAMINADOR,
			"FECHA_CREACION" => $FECHA_CREACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		
		]); 
		valida_error_medoo_and_die(); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		//Enviar email notificando asignación
		//Buscar info del dictaminador
		$info_dictaminador = $database->get("USUARIOS","*",["ID" => $ID_DICTAMINADOR]);
		//Buscar info del asignador
		$info_asignador = $database->get("USUARIOS","*",["ID" => $ID_USUARIO_CREACION]);
		$destinatario = $info_dictaminador["EMAIL"];
		$nombre_asignador = $info_asignador["NOMBRE"];
		$mensaje = $nombre_asignador . " le ha enviado una nueva solicitud de dictaminación";
		enviar_email($destinatario,$mensaje,"Nueva solicitud de dictaminación");
	}
	else{
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Este tipo de servicio ya esta cargado en dictaminacion ";
		
	}
	print_r(json_encode($respuesta)); 
?> 
