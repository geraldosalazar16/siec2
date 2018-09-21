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

	$clientes1 = $database->query("SELECT DISTINCT C.ID,C.NOMBRE,C.ID_TIPO_ENTIDAD,C.ID_TIPO_PERSONA,C.ID_CLIENTE_FACTURARIO,
	C.RFC,C.TIENE_FACTURARIO,C.ES_FACTURARIO,C.FECHA_CREACION,C.HORA_CREACION,C.FECHA_MODIFICACION,C.ID_USUARIO_CREACION,C.ID_USUARIO_MODIFICACION,
	C.HORA_MODIFICACION,CC.NOMBRE_CONTACTO,CC.TELEFONO_MOVIL,CC.TELEFONO_FIJO,CC.EMAIL,CC.EMAIL2,
	CD.NOMBRE_DOMICILIO,CD.CALLE,CD.NUMERO_EXTERIOR,CD.NUMERO_INTERIOR,CD.COLONIA_BARRIO,CD.DELEGACION_MUNICIPIO,CD.ENTIDAD_FEDERATIVA,CD.CP,CD.PAIS
	FROM 
	`CLIENTES_DOMICILIOS` CD
	LEFT JOIN
	`CLIENTES` C
	ON C.ID = CD.ID_CLIENTE
	LEFT JOIN `CLIENTES_CONTACTOS` CC
	ON CD.ID = CC.ID_CLIENTE_DOMICILIO");
	$clientes = $clientes1->fetchAll();

valida_error_medoo_and_die();
for ($i=0; $i < count($clientes) ; $i++) { 
	if (!is_null($clientes[$i]["ID_CLIENTE_FACTURARIO"])) {
		$nombre_facturatario = $database->get("CLIENTES", "NOMBRE", ["ID"=>$clientes[$i]["ID_CLIENTE_FACTURARIO"]]);
		valida_error_medoo_and_die();

		$clientes[$i]["NOMBRE_FACTURATARIO"] = $nombre_facturatario;
	}
	$tipo_persona = $database->get("TIPOS_PERSONA", "TIPO", ["ID"=>$clientes[$i]["ID_TIPO_PERSONA"]]);
	valida_error_medoo_and_die();
	$clientes[$i]["TIPO_PERSONA"] = $tipo_persona;

	$tipo_entidad = $database->get("TIPOS_ENTIDAD", "TIPO", ["ID"=>$clientes[$i]["ID_TIPO_ENTIDAD"]]);
	valida_error_medoo_and_die();
	$clientes[$i]["TIPO_ENTIDAD"] = $tipo_entidad;
	$clientes[$i]["DIRECCION"] = $clientes[$i]["CALLE"]." ".$clientes[$i]["NUMERO_EXTERIOR"]." ".$clientes[$i]["NUMERO_INTERIOR"]." ".$clientes[$i]["COLONIA_BARRIO"]." ".$clientes[$i]["DELEGACION_MUNICIPIO"]." ".$clientes[$i]["ENTIDAD_FEDERATIVA"]." ".$clientes[$i]["CP"]." ".$clientes[$i]["PAIS"];
}
 

print_r(json_encode($clientes));


//-------- FIN --------------
?>