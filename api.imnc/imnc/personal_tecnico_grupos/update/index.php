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
			$mailerror->send("PERSONAL_TECNICO_GRUPOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
$ID = $objeto->ID; 
$ID_SG_TIPO_SERVICIO = $objeto->ID_SG_TIPO_SERVICIO; 
$ID_PERSONAL_TECNICO = $objeto->ID_PERSONAL_TECNICO; 
$ID_PERSONAL_TECNICO_ROL = $objeto->ID_PERSONAL_TECNICO_ROL; 
$REGISTRO = $objeto->REGISTRO; 
$FECHA = $objeto->FECHA; 
$HORA = $objeto->HORA; 
	$id = $database->update("PERSONAL_TECNICO_GRUPOS", [ 
"ID_SG_TIPO_SERVICIO" => $ID_SG_TIPO_SERVICIO, 
"ID_PERSONAL_TECNICO" => $ID_PERSONAL_TECNICO, 
"ID_PERSONAL_TECNICO_ROL" => $ID_PERSONAL_TECNICO_ROL, 
"REGISTRO" => $REGISTRO, 
"FECHA" => $FECHA, 
"HORA" => $HORA, 
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die(); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
