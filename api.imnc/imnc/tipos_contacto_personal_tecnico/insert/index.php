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
			$mailerror->send("TIPOS_CONTACTO_PERSONAL_TECNICO", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
$TIPO = $objeto->TIPO; 
$FECHA_CREACION = $objeto->FECHA_CREACION; 
$HORA_CREACION = $objeto->HORA_CREACION; 
$FECHA_MODIFICACION = $objeto->FECHA_MODIFICACION; 
$HORA_MODIFICACION = $objeto->HORA_MODIFICACION; 
$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert("TIPOS_CONTACTO_PERSONAL_TECNICO", [ 
"TIPO" => $TIPO, 
"FECHA_CREACION" => $FECHA_CREACION, 
"HORA_CREACION" => $HORA_CREACION, 
"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
"HORA_MODIFICACION" => $HORA_MODIFICACION, 
"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION, 
	]); 
	valida_error_medoo_and_die(); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
