<?php  
	include  '../../common/conn-apiserver.php';  
	include  '../../common/conn-medoo.php';  

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

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json);

$ID = $objeto->ID;
valida_parametro_and_die($ID,"Falta el ID");

$CLAVE = $objeto->CLAVE;
valida_parametro_and_die($CLAVE,"Falta la Clave");

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE,"Falta el Nombre");

$id_usuario = $objeto->id_usuario;
valida_parametro_and_die($id_usuario,"Falta seleccionar un usuario");

$USUARIO_CREACION = $objeto->id_usuario;
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

	
$id = $database->update("I_CAT_METODO_D_PAGO",
	[  
		"CLAVE" => strtoupper($CLAVE),
		"NOMBRE" => $NOMBRE,
		"FECHA_MODIFICACION" => $FECHA_CREACION." ".$HORA_CREACION,
		"ID_USUARIO_MODIFICACION" => $USUARIO_CREACION,
	],["ID"=>$ID]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
