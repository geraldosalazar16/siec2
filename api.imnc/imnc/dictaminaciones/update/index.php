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
		
	
	print_r(json_encode($respuesta)); 
?> 
