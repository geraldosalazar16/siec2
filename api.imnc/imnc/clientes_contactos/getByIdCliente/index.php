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

//$client_contact = $database->select("CLIENTES_CONTACTOS", "*", ["ID"=>$id]);
$client_contact = $database->select("CLIENTES_CONTACTOS", ["[><]CLIENTES_DOMICILIOS"=>["CLIENTES_CONTACTOS.ID_CLIENTE_DOMICILIO"=>"ID"],
															"[><]CLIENTES"=>["CLIENTES_DOMICILIOS.ID_CLIENTE"=>"ID"]],["CLIENTES_CONTACTOS.NOMBRE_CONTACTO","CLIENTES_CONTACTOS.ID"], ["CLIENTES_DOMICILIOS.ID_CLIENTE"=>$id]);
valida_error_medoo_and_die();

print_r(json_encode($client_contact));


//-------- FIN --------------
?>