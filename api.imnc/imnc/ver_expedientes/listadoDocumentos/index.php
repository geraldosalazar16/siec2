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

/*
$DOCUMENTO = $database->select("CATALOGO_DOCUMENTOS",["ID","NOMBRE","ID_ETAPA","ID_SECCION","DESCRIPCION"],["ORDER"=>"ID"]);
*/
$DOCUMENTOS = $database->query("SELECT 
CD.ID,
CD.NOMBRE,
CD.ID_ETAPA,
CD.ID_SECCION,
CD.DESCRIPCION,
E.ETAPA,
S.NOMBRE_SECCION 
FROM 
CATALOGO_DOCUMENTOS CD INNER JOIN ETAPAS_PROCESO E ON CD.ID_ETAPA = E.ID_ETAPA
INNER JOIN CATALOGO_SECCIONES S ON CD.ID_SECCION = S.ID")->fetchAll();


valida_error_medoo_and_die();

print_r(json_encode($DOCUMENTOS));


//-------- FIN --------------
?>