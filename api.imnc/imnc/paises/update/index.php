<?php  
	include  '../../common/conn-apiserver.php';  
	include  '../../common/conn-medoo.php';  
	include  '../../common/conn-sendgrid.php';  
	function valida_parametro_and_die($parametro, $mensaje_error){ 
		$parametro = "" . $parametro; 
		if ($parametro == "") { 
			$respuesta["resultado"] = "error\n"; 
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
			$mailerror->send("PAISES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
$NOMBRE = $objeto->NOMBRE; 
$NOMBRE_CORTO = $objeto->NOMBRE_CORTO; 
	$id = $database->update("PAISES", [ 
"NOMBRE_CORTO" => $NOMBRE_CORTO, 
	], ["NOMBRE"=>$NOMBRE]); 
	valida_error_medoo_and_die(); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
