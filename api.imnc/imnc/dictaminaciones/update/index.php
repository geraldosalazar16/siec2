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
	$ID_USUARIO_MODIFICACION= $objeto->ID_USUARIO; 
	valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta el ID_USUARIO_MODIFICACION");
	$FECHA_MODIFICACION = date("Ymd");
	

		$id = $database->update("DICTAMINACIONES", [			
			"STATUS" => $STATUS, 
			"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
			"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
		
		],
		[
			"ID"=>$ID
		]); 
		valida_error_medoo_and_die(); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		
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
