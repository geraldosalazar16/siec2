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
$id_servicio_cliente_etapa = $_REQUEST["id"];
$nombre_etapa= $_REQUEST["nombre_etapa"];
$nombre_seccion= $_REQUEST["nombre_seccion"];
$nombre_ciclo= $_REQUEST["nombre_ciclo"];
$respuesta=array();

if($nombre_etapa == "todas"){
//	$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS", "*",["SECCION"=>$nombre_seccion,"ORDER"=>"ID"]);
	$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS",["[><]ETAPAS_PROCESO"=>["CATALOGO_DOCUMENTOS.ID_ETAPA"=>"ID_ETAPA"],"[><]CATALOGO_SECCIONES"=>["CATALOGO_DOCUMENTOS.ID_SECCION"=>"ID"]],["CATALOGO_DOCUMENTOS.ID","CATALOGO_DOCUMENTOS.NOMBRE","CATALOGO_DOCUMENTOS.DESCRIPCION","ETAPAS_PROCESO.ETAPA","CATALOGO_SECCIONES.NOMBRE_SECCION"],["AND"=>["CATALOGO_SECCIONES.NOMBRE_SECCION"=>$nombre_seccion],"ORDER"=>"CATALOGO_DOCUMENTOS.ID"]);
}
else{
	//$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS", "*",["AND"=>["ETAPA"=>$nombre_etapa,"SECCION"=>$nombre_seccion],"ORDER"=>"ID"]);
	$DOCUMENTOS = $database->select("CATALOGO_DOCUMENTOS",["[><]ETAPAS_PROCESO"=>["CATALOGO_DOCUMENTOS.ID_ETAPA"=>"ID_ETAPA"],"[><]CATALOGO_SECCIONES"=>["CATALOGO_DOCUMENTOS.ID_SECCION"=>"ID"]],["CATALOGO_DOCUMENTOS.ID","CATALOGO_DOCUMENTOS.NOMBRE","CATALOGO_DOCUMENTOS.DESCRIPCION","ETAPAS_PROCESO.ETAPA","CATALOGO_SECCIONES.NOMBRE_SECCION"] ,["AND"=>["ETAPAS_PROCESO.ETAPA"=>$nombre_etapa,"CATALOGO_SECCIONES.NOMBRE_SECCION"=>$nombre_seccion],"ORDER"=>"CATALOGO_DOCUMENTOS.ID"]);
}

for($i=0;$i<count($DOCUMENTOS);$i++){
	$ESTADO_DOCUMENTO = $database->select("BASE_DOCUMENTOS","ESTADO_DOCUMENTO",["AND"=>["ID_CATALOGO_DOCUMENTOS"=>$DOCUMENTOS[$i]["ID"],"ID_SERVICIO"=>$id_servicio_cliente_etapa,"CICLO"=>$nombre_ciclo]]);
	$DOCUMENTOS[$i]["ESTADO"]=$ESTADO_DOCUMENTO;

}

valida_error_medoo_and_die();

print_r(json_encode($DOCUMENTOS));


//-------- FIN --------------
?>