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

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP


$ID_SERVICIO = $objeto->ID_SERVICIO;
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");

$ID_REFERENCIA = $objeto->ID_REFERENCIA;
valida_parametro_and_die($ID_REFERENCIA, "Es necesario seleccionar un texto para usar como referencia");

$ACRONIMO = $objeto->ACRONIMO;
valida_parametro_and_die($ACRONIMO, "Es necesario capturar una clave de tipo de servicio");

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario capturar el nombre del tipo de servicio");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");
$FECHA_CREACION	=	date("Ymd");
$HORA_CREACION	=	date("His");
$ID_USUARIO_MODIFICACION = 0;
$FECHA_MODIFICACION = "";
$HORA_MODIFICACION = "";

$id = $database->insert("TIPOS_SERVICIO", [
	"ID_SERVICIO" => $ID_SERVICIO,
	"ACRONIMO" => $ACRONIMO,
	"NOMBRE" => $NOMBRE,
	"ID_REFERENCIA" => $ID_REFERENCIA,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

///////////////////////////////////////////////////////////////////////////////
//				PARA INSERTAR LAS RELACIONES NORMAS TIPO SERVICIO

$id2	=	$database->delete("NORMAS_TIPOSERVICIO",["ID_TIPO_SERVICIO"=>$id]);

$NORMA	=	$objeto->NORMA;
if(isset($NORMA)){
for($i=0;$i<count($NORMA);$i++){
$ID_NORMA = $NORMA[$i]->ID;

$id1 = $database->insert("NORMAS_TIPOSERVICIO", [
			
		"ID_NORMA" => $ID_NORMA, 
		"ID_TIPO_SERVICIO" => $id,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION		
		
	]); 
	valida_error_medoo_and_die();

}
	
}

$respuesta['resultado']="ok";
$respuesta['id']=$id;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>