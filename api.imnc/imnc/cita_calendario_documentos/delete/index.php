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

$respuesta=array();

$ID_SERV = $_REQUEST["id_serv"];
$ID_DOCUM = $_REQUEST["id_docum"];
$NOMB_CICLO = $_REQUEST["nomb_ciclo"];
valida_parametro_and_die($ID_SERV,"Falta ID de SERVICIO");
valida_parametro_and_die($ID_DOCUM,"Falta ID de DOCUMENTO");
valida_parametro_and_die($NOMB_CICLO,"Falta NOMBRE CICLO");
$id	=	$database->select("TAREAS_DOCUMENTO","ID",["AND" => ["ID_SERVICIO" => $ID_SERV,"ID_CATALOGO_DOCUMENTOS" => $ID_DOCUM,"CICLO" => $NOMB_CICLO]]);

// Voy a borrar el historico de este documento
for($i=0;$i<count($id);$i++){
$id1 = $database->delete("TAREAS_DOCUMENTO_HISTORICO", ["ID_TAREA"=>$id[$i]]);
valida_error_medoo_and_die();
}

// Borro las tareas de este documento
$id2 = $database->delete("TAREAS_DOCUMENTO", ["AND" => ["ID_SERVICIO" => $ID_SERV,"ID_CATALOGO_DOCUMENTOS" => $ID_DOCUM,"CICLO" => $NOMB_CICLO]]);
valida_error_medoo_and_die();
$respuesta['resultado']="ok";
$respuesta['id']=$id;

print_r(json_encode($respuesta));


//-------- FIN --------------
?>