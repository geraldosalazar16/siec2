<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
//	include  '../../ex_common/query.php';
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
	$nombre_tabla = "DICTAMINADOR_TIPO_SERVICIO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	
	$respuesta = $database->query("SELECT DISTINCT `DICTAMINADOR_TIPO_SERVICIO`.`ID_USUARIO`,`USUARIOS`.`NOMBRE` as `NOMBRE_USUARIO`
									FROM `DICTAMINADOR_TIPO_SERVICIO`
									INNER JOIN
										`USUARIOS` ON `USUARIOS`.`ID` = `DICTAMINADOR_TIPO_SERVICIO`.`ID_USUARIO`")->fetchAll(PDO::FETCH_ASSOC);

	valida_error_medoo_and_die(); 
		
	for($i=0;$i<count($respuesta);$i++){
		$tipos_servicio = [];
		$tipos_servicio = $database->select("DICTAMINADOR_TIPO_SERVICIO",
									[
										"[><]TIPOS_SERVICIO"=>["DICTAMINADOR_TIPO_SERVICIO.ID_TIPO_SERVICIO"=>"ID"]
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_TIPO_SERVICIO",
										"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPO_SERVICIO)"
									],
									[
										"DICTAMINADOR_TIPO_SERVICIO.ID_USUARIO"=>$respuesta[$i]["ID_USUARIO"]
									]
									
									);
		$respuesta[$i]["TIPOS_SERVICIO"] = $tipos_servicio;							
	}
	
	print_r(json_encode($respuesta)); 
	
	
?> 
