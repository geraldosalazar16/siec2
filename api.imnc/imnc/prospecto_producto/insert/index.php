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
	$NORMAS= $objeto->producto;
	if(count($NORMAS) == 0){
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Es necesario seleccionar una norma";
		print_r(json_encode($respuesta));
		die();
	}
	$ALCANCE= $objeto->alcance;
	if(!$ALCANCE){
		$ALCANCE = "";
	}
    $existe = $database->select(
		"PROSPECTO_PRODUCTO",
		"*",
		[
			"AND" => [
				"ID_SERVICIO" => $ID_SERVICIO,
				"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO
			]
		]
	);
	if(count($existe) > 0){
		$respuesta['resultado']="error";
		$respuesta['mensaje']="El registro que intenta ingresar ya existe";
		print_r(json_encode($respuesta));
		die();
	}	
	$id_producto = $database->insert("PROSPECTO_PRODUCTO", [ 
	    "ID_PROSPECTO" => $ID_PROSPECTO,
		"ID_SERVICIO" => $ID_SERVICIO, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"ALCANCE" => $ALCANCE
	]); 
	valida_error_medoo_and_die();
	//iNSERTAR LAS NORMAS
	for ($i=0; $i < count($NORMAS); $i++) { 
		$id_norma = $NORMAS[$i]->ID_NORMA;
		$id_producto_normas = $database->insert("PROSPECTO_PRODUCTO_NORMAS", [ 
			"ID_PRODUCTO" => $id_producto,
			"ID_NORMA" => $id_norma
		]); 
		valida_error_medoo_and_die();
	}	 

	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
