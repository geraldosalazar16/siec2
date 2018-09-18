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
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}
function borrar_pdf($direcc_arch){
if (!unlink($direcc_arch)){
//si no puede ser muestro un mensaje 🙂
	$respuesta['resultado']="error";
	$respuesta['mensaje']= "no se pudo borrar el archivo .".$direcc_arch;
}
}
$respuesta=array();

$ID_SERV = $_REQUEST["id_serv"];
$ID_DOCUM = $_REQUEST["id_docum"];
$NOMB_CICLO = $_REQUEST["nomb_ciclo"];
valida_parametro_and_die($ID_SERV,"Falta ID de SERVICIO");
valida_parametro_and_die($ID_DOCUM,"Falta ID de DOCUMENTO");
valida_parametro_and_die($NOMB_CICLO,"Falta NOMBRE CICLO");
$direccion	=	$database->select("BASE_DOCUMENTOS","UBICACION_DOCUMENTOS",["AND" => ["ID_SERVICIO" => $ID_SERV,"ID_CATALOGO_DOCUMENTOS" => $ID_DOCUM,"CICLO" => $NOMB_CICLO]]);

$direccion_archivo=$direccion[0].$ID_DOCUM.".pdf";
borrar_pdf($direccion_archivo);
$id = $database->delete("BASE_DOCUMENTOS", ["AND" => ["ID_SERVICIO" => $ID_SERV,"ID_CATALOGO_DOCUMENTOS" => $ID_DOCUM,"CICLO" => $NOMB_CICLO]]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$id;
$respuesta['direccion']=$direccion;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>