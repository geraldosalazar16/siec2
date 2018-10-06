<?php  
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$error = $database->error();
		if($error[0] == '23000' && $error[1] == 1062){
			$respuesta['mensaje']="El registro que intenta ingresar ya existe";
		} else {
			$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		}
		print_r(json_encode($respuesta));
		die();
	}
}	
	
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	
	$ID_PROSPECTO = $objeto->id_prospecto;
	valida_parametro_and_die($ID_PROSPECTO,"Es necesario seleccionar un prospecto");
	$ID_SERVICIO=$objeto->area;
	valida_parametro_and_die($ID_SERVICIO,"Es necesario seleccionar un servicio");
	$ID_TIPO_SERVICIO = $objeto->departamento; 
	valida_parametro_and_die($ID_TIPO_SERVICIO,"Es necesario seleccionar un tipo de servicio");
	$ID_NORMA= $objeto->producto;
	valida_parametro_and_die($ID_NORMA,"Es necesario seleccionar una norma");
	$ALCANCE= $objeto->alcance;
	if(!$ALCANCE){
		$ALCANCE = "";
	}
    /*	
	$id = $database->insert("PROSPECTO_PRODUCTO", [ 
	    "ID_PROSPECTO" => $ID_PROSPECTO,
		"ID_SERVICIO" => $ID_SERVICIO, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"ID_NORMA" => $ID_NORMA,
		"ALCANCE" => $ALCANCE
	]); 
	*/	
	$QUERY = "INSERT INTO PROSPECTO_PRODUCTO VALUES (".$ID_PROSPECTO.",".$ID_SERVICIO.",".$ID_TIPO_SERVICIO.",'".$ID_NORMA."','".$ALCANCE."')";
	$id = $database->query($QUERY);
	valida_error_medoo_and_die(); 

	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
