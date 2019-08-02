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
	
	$ID = $objeto->ID; 
	valida_parametro_and_die($ID,"Falta ID de DICTAMINACION");
	$STATUS= $objeto->STATUS; 
	valida_parametro_and_die($STATUS,"Falta STATUS");
	$FECHA_CERTIFICADO= $objeto->FECHA_CERTIFICADO; 
	$ID_USUARIO_MODIFICACION= $objeto->ID_USUARIO; 
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta el ID_USUARIO_MODIFICACION");
	$FECHA_MODIFICACION = date("Ymd");
	

		$id = $database->update("DICTAMINACIONES", [			
			"STATUS" => $STATUS, 
			"FECHA_CERTIFICADO"=>$FECHA_CERTIFICADO,
			"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
			"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
		
		],
		[
			"ID"=>$ID
		]); 
		valida_error_medoo_and_die(); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		/*
			**		A PARTIR DE AQUI CREO LAS TAREAS DE FORMA AUTOMATICA
		*/
		//		PARA CREAR LAS TAREAS DE FORMA AUTOMATICA LO PRIMERO ES COMPROBAR QUE SEA FINAL DE ETAPA 2 Y QUE LA DICTAMINACION ESTE APROBADA.
		$datos_dictam = $database->select("DICTAMINACIONES","*",["ID"=>$ID]);
		if($datos_dictam[0]["TIPO_AUDITORIA"]==3 && $datos_dictam[0]["STATUS"] == 1){
			//	LA PRIMERA TAREA A CREAR ES VIGILANCIA ANUAL 1 Y SE CREARA 1 ANHIO DESPUES DE LA FECHA DE CERTIFICADO
			$FECHA_VIG1 = date('Y-m-d',strtotime('+1 year',strtotime($datos_dictam[0]["FECHA_CERTIFICADO"])));
			$dia_sem = date('w',strtotime($FECHA_VIG1));
			if($dia_sem == 0){ //si es domingo resto 2 dias para que la tarea caiga viernes
				$FECHA_VIG1 = date('Y-m-d',strtotime('-2 day',strtotime($FECHA_VIG1)));
			}
			if($dia_sem == 6){ //si es sabado resto 1 dia para que la tarea caiga viernes
				$FECHA_VIG1 = date('Y-m-d',strtotime('-1 day',strtotime($FECHA_VIG1)));
			}
			// AQUI LA CONSULTA QUE INSERTARA LA TAREA
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS", [ 
										"ID_SERVICIO" => $datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"], 
										"ID_TAREA" => 2,
										"ID_AUDITORIA" => 0,
										"FECHA_INICIO" => $FECHA_VIG1, 
										"HORA_INICIO" =>"08:00", 
										"FECHA_FIN" => $FECHA_VIG1,
										"HORA_FIN" => "16:00"
										]); 
			// AQUI LA INSERTO EN LA TABLA HISTORICO
			//Necesito obtener el id de la tarea para insertarlo en la tabla historicos
			$tarea_id = $database->query("SELECT MAX(ID) FROM TAREAS_SERVICIOS_CONTRATADOS")->fetchAll();
			$FECHA = date("Ymd");
			$HORA = date("His");
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS_HISTORICO", [ 
										"ID_TAREA" => $tarea_id[0]["MAX(ID)"],
										"USUARIO_MODIFICACION" => $USUARIO, 
										"FECHA_MODIFICACION" => $FECHA, 
										"HORA_MODIFICACION" => $HORA,
										"DESCRIPCION_MODIFICACION" => "CREACION DE LA TAREA",
										"FECHA_INICIO" => $FECHA_VIG1,
										"FECHA_FIN" => $FECHA_VIG1,
										"HORA_INICIO" => "08:00",
										"HORA_FIN" => "16:00"
									]); 		
			//	LA SEGUNDA TAREA A CREAR ES VIGILANCIA ANUAL 2 Y SE CREARA 2 ANHIO DESPUES DE LA FECHA DE CERTIFICADO
			$FECHA_VIG2 = date('Y-m-d',strtotime('+2 year',strtotime($datos_dictam[0]["FECHA_CERTIFICADO"])));
			$dia_sem = date('w',strtotime($FECHA_VIG2));
			if($dia_sem == 0){ //si es domingo resto 2 dias para que la tarea caiga viernes
				$FECHA_VIG2 = date('Y-m-d',strtotime('-2 day',strtotime($FECHA_VIG2)));
			}
			if($dia_sem == 6){ //si es sabado resto 1 dia para que la tarea caiga viernes
				$FECHA_VIG2 = date('Y-m-d',strtotime('-1 day',strtotime($FECHA_VIG2)));
			}
			// AQUI LA CONSULTA QUE INSERTARA LA TAREA
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS", [ 
										"ID_SERVICIO" => $datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"], 
										"ID_TAREA" => 3,
										"ID_AUDITORIA" => 0,
										"FECHA_INICIO" => $FECHA_VIG2, 
										"HORA_INICIO" =>"08:00", 
										"FECHA_FIN" => $FECHA_VIG2,
										"HORA_FIN" => "16:00"
										]); 
			// AQUI LA INSERTO EN LA TABLA HISTORICO
			//Necesito obtener el id de la tarea para insertarlo en la tabla historicos
			$tarea_id = $database->query("SELECT MAX(ID) FROM TAREAS_SERVICIOS_CONTRATADOS")->fetchAll();
			$FECHA = date("Ymd");
			$HORA = date("His");
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS_HISTORICO", [ 
										"ID_TAREA" => $tarea_id[0]["MAX(ID)"],
										"USUARIO_MODIFICACION" => $USUARIO, 
										"FECHA_MODIFICACION" => $FECHA, 
										"HORA_MODIFICACION" => $HORA,
										"DESCRIPCION_MODIFICACION" => "CREACION DE LA TAREA",
										"FECHA_INICIO" => $FECHA_VIG2,
										"FECHA_FIN" => $FECHA_VIG2,
										"HORA_INICIO" => "08:00",
										"HORA_FIN" => "16:00"
									]);
			//	LA TERCERA TAREA A CREAR ES RENOVACION Y SE CREARA 2 ANHIO Y 9 MESES DESPUES DE LA FECHA DE CERTIFICADO
			$FECHA_REN = date('Y-m-d',strtotime('+33 month',strtotime($datos_dictam[0]["FECHA_CERTIFICADO"])));
			$dia_sem = date('w',strtotime($FECHA_REN));
			if($dia_sem == 0){ //si es domingo resto 2 dias para que la tarea caiga viernes
				$FECHA_REN = date('Y-m-d',strtotime('-2 day',strtotime($FECHA_REN)));
			}
			if($dia_sem == 6){ //si es sabado resto 1 dia para que la tarea caiga viernes
				$FECHA_REN = date('Y-m-d',strtotime('-1 day',strtotime($FECHA_REN)));
			}
			
			// AQUI LA CONSULTA QUE INSERTARA LA TAREA
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS", [ 
										"ID_SERVICIO" => $datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"], 
										"ID_TAREA" => 12,
										"ID_AUDITORIA" => 0,
										"FECHA_INICIO" => $FECHA_REN, 
										"HORA_INICIO" =>"08:00", 
										"FECHA_FIN" => $FECHA_REN,
										"HORA_FIN" => "16:00"
										]); 
			// AQUI LA INSERTO EN LA TABLA HISTORICO
			//Necesito obtener el id de la tarea para insertarlo en la tabla historicos
			$tarea_id = $database->query("SELECT MAX(ID) FROM TAREAS_SERVICIOS_CONTRATADOS")->fetchAll();
			$FECHA = date("Ymd");
			$HORA = date("His");
			$id = $database->insert("TAREAS_SERVICIOS_CONTRATADOS_HISTORICO", [ 
										"ID_TAREA" => $tarea_id[0]["MAX(ID)"],
										"USUARIO_MODIFICACION" => $USUARIO, 
										"FECHA_MODIFICACION" => $FECHA, 
										"HORA_MODIFICACION" => $HORA,
										"DESCRIPCION_MODIFICACION" => "CREACION DE LA TAREA",
										"FECHA_INICIO" => $FECHA_REN,
										"FECHA_FIN" => $FECHA_REN,
										"HORA_INICIO" => "08:00",
										"HORA_FIN" => "16:00"
									]);
		/*	$consulta = 'INSERT INTO 
							`TAREAS_SERVICIOS_CONTRATADOS` (`ID`, `ID_SERVICIO`, `ID_TAREA`, `ID_AUDITORIA`, `FECHA_INICIO`, `HORA_INICIO`, `FECHA_FIN`, `HORA_FIN`) 
							VALUES
								("", '.$datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"].',2 , 0,"'.$FECHA_VIG1.'", "08:00", "'.$FECHA_VIG1.'", "16:00"),
								("", '.$datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"].', 3, 0, "'.$FECHA_VIG2.'", "08:00", "'.$FECHA_VIG2.'", "16:00"),
								("",'.$datos_dictam[0]["ID_SERVICIO_CLIENTE_ETAPA"].', 12, 0, "'.$FECHA_REN.'", "08:00", "'.$FECHA_REN.'", "16:00")';
			$idx = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);	*/				
		}
		
		
		/*	
			**
		*/
		//Enviar email notificando cambio de estatus
		//Buscar info del dictaminador
		$info_dictaminador = $database->get("USUARIOS","*",["ID" => $ID_USUARIO_MODIFICACION]);
		//Buscar info de la dictaminación
		$info_dictaminacion = $database->get("DICTAMINACIONES","*",["ID" => $ID]);
		//Buscar info del asignador
		$info_asignador = $database->get("USUARIOS","*",["ID" => $info_dictaminacion["ID_USUARIO_CREACION"]]);
		//Buscar info del servicio
		$info_servicio = $database->select("DICTAMINACIONES",[
				"[><]SERVICIO_CLIENTE_ETAPA"=>["DICTAMINACIONES.ID_SERVICIO_CLIENTE_ETAPA"=>"ID"],
				"[><]CLIENTES"=>["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE"=>"ID"]										
			],[
				"CLIENTES.NOMBRE"
			],[
				"DICTAMINACIONES.ID"=>$ID
			]
		);
		$destinatario = $info_asignador["EMAIL"];
		$nombre_dictaminador = $info_dictaminador["NOMBRE"];
		$mensaje = $nombre_dictaminador . " ha cambiado el estatus del servicio " . $info_servicio[0]["NOMBRE"]. " a '" . $STATUS . "'";
		enviar_email($destinatario,$mensaje,"Solicitud de dictaminación modificada");
	print_r(json_encode($respuesta)); 
?> 
