<?php  
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
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
			$mailerror->send("DICTAMINADOR_TIPO_SERVICIO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 

	
	
	
	$nombre_tabla = "DICTAMINADOR_TIPO_SERVICIO";
					 
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 
	
	$ID_USUARIO = $objeto->ID_USUARIO; 
	valida_parametro_and_die($ID_USUARIO,"Falta ID de USUARIO");
	$ID_TIPO_SERVICIO = $objeto->ID_TIPOSERVICIO; 
	valida_parametro_and_die($ID_TIPO_SERVICIO,"Falta ID de TIPO DE SERVICIO");
	
	
		$id = $database->delete("DICTAMINADOR_TIPO_SERVICIO", ["AND"=>
			[		
				"ID_USUARIO" => $ID_USUARIO, 
				"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO 
			]
		
		]); 
		valida_error_medoo_and_die(); 
		$respuesta["resultado"]="ok"; 
		
	
	print_r(json_encode($respuesta)); 
?> 
