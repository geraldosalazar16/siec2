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

$id = $_REQUEST["id"];
valida_parametro_and_die($id, "Faltan parámetros");
$campos = ["CLIENTES_DOMICILIOS.ID","CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)","CLIENTES_DOMICILIOS.PAIS",
			"CLIENTES_DOMICILIOS.ENTIDAD_FEDERATIVA(ESTADO)","CLIENTES_DOMICILIOS.DELEGACION_MUNICIPIO(MUNICIPIO)",
			"CLIENTES_DOMICILIOS.COLONIA_BARRIO(COLONIA)","CLIENTES_DOMICILIOS.CP(CODIGO_POSTAL)","CLIENTES_DOMICILIOS.CALLE",
			"CLIENTES_DOMICILIOS.NUMERO_INTERIOR","CLIENTES_DOMICILIOS.NUMERO_EXTERIOR","CLIENTES_DOMICILIOS.ES_FISCAL(FISCAL)","CLIENTES_DOMICILIOS.FECHA_CREACION",
			"CLIENTES_DOMICILIOS.FECHA_MODIFICACION","UC.NOMBRE(NOMBRE_USUARIO_CREAR)","UM.NOMBRE(NOMBRE_USUARIO_MOD)"];

$client_dom = $database->get("CLIENTES_DOMICILIOS",
	["[>]USUARIOS(UC)" => ["ID_USUARIO_CREACION" => "ID"],"[>]USUARIOS(UM)" => ["ID_USUARIO_MODIFICACION" => "ID"]],
	$campos,["CLIENTES_DOMICILIOS.ID"=>$id]);
valida_error_medoo_and_die();

print_r(json_encode($client_dom));


//-------- FIN --------------
?>