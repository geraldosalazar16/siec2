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
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

	
	
	
	$nombre_tabla = "NORMAS_TIPOSERVICIO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 
	
	$ID_NORMA = $objeto->ID_NORMA; 
	$ID_TIPO_SERVICIO = $objeto->ID_TIPOSERVICIO; 
	$FECHA_CREACION = date('Ymd');
	$HORA_CREACION	=	date("His");
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = ""; 
	$HORA_MODIFICACION	=	"";
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert($nombre_tabla, [
			
		"ID_NORMA" => $ID_NORMA, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO, 
		"FECHA_CREACION" => $FECHA_CREACION, 
		"HORA_CREACIO"	=>	$HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"HORA_MODIFICACION"	=>	$HORA_MODIFICACION,	
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	]); 
	valida_error_medoo_and_die(); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
