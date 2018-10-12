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
	
	$ID_PRODUCTO = $objeto->ID_PRODUCTO;
	valida_parametro_and_die($ID_PRODUCTO,"Es necesario seleccionar un producto");
	$ID_SECTOR=$objeto->ID_SECTOR;
	valida_parametro_and_die($ID_SECTOR,"Es necesario seleccionar un sector");
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
	valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

	$FECHA_CREACION = date("Ymd");
	$HORA_CREACION = date("His");

	$id = $database->insert("PROSPECTO_SECTORES", [
		"ID_PRODUCTO" => $ID_PRODUCTO,
		"ID_SECTOR" => $ID_SECTOR,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"USUARIO_CREACION" => $ID_USUARIO_CREACION
	]);
	valida_error_medoo_and_die();

	$respuesta['resultado']="ok";
	$respuesta['id']=$id;
	print_r(json_encode($respuesta));
?> 
