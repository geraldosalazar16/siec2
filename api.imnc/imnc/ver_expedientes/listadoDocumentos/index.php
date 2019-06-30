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
$id_serv = $_REQUEST["id_serv"]; 
$id_ts =$_REQUEST["id_ts"]; 
$etapa =$_REQUEST["etapa"]; 
$seccion =$_REQUEST["seccion"]; 

$where ='';
if($id_serv=='' && $id_ts == ''&& $etapa == '' && $seccion == ''){
	$where .= '';	
}
else{
	if($id_serv != ''){
		if($where == ''){
			$where .= ' WHERE SERV.ID = '.$id_serv;
		}
		else{
			$where .= ' AND SERV.ID = '.$id_serv;
		}		
	}
	if($id_ts != ''){
		if($where == ''){
			$where .= ' WHERE CD.ID_TIPO_SERVICIO = '.$id_ts;
		}
		else{
			$where .= ' AND CD.ID_TIPO_SERVICIO = '.$id_ts;
		}		
	}
	if($etapa != ''){
		if($where == ''){
			$where .= ' WHERE CD.ID_ETAPA = '.$etapa;
		}
		else{
			$where .= ' AND CD.ID_ETAPA = '.$etapa;
		}
	}
	if($seccion != ''){
		if($where == ''){
			$where .= ' WHERE CD.ID_SECCION = '.$seccion;
		}
		else{
			$where .= ' AND CD.ID_SECCION = '.$seccion;
		}
	}
	
}
/*
$DOCUMENTO = $database->select("CATALOGO_DOCUMENTOS",["ID","NOMBRE","ID_ETAPA","ID_SECCION","DESCRIPCION"],["ORDER"=>"ID"]);
*/
$DOCUMENTOS = $database->query("SELECT 
CD.ID,
CD.NOMBRE,
CD.ID_ETAPA,
CD.ID_SECCION,
CD.ID_TIPO_SERVICIO,
CD.DESCRIPCION,
E.ETAPA,
S.NOMBRE_SECCION,
TS.NOMBRE AS NOMBRE_TIPO_SERVICIO,
SERV.NOMBRE AS NOMBRE_SERVICIO,
SERV.ID AS ID_SERVICIO  
FROM 
CATALOGO_DOCUMENTOS CD 
INNER JOIN ETAPAS_PROCESO E ON CD.ID_ETAPA = E.ID_ETAPA
INNER JOIN CATALOGO_SECCIONES S ON CD.ID_SECCION = S.ID 
INNER JOIN TIPOS_SERVICIO TS ON CD.ID_TIPO_SERVICIO = TS.ID
INNER JOIN SERVICIOS SERV ON TS.ID_SERVICIO = SERV.ID ".$where)->fetchAll();


valida_error_medoo_and_die();

print_r(json_encode($DOCUMENTOS));


//-------- FIN --------------
?>