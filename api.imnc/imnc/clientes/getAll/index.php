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


$clientes = $database->select("CLIENTES", "*", ["ORDER"=>"NOMBRE"]);
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
}
 

print_r(json_encode($clientes));


//-------- FIN --------------
?>