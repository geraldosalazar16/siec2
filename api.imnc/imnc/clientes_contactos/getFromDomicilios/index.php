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

$idsDom = $_REQUEST["idsDom"];
valida_parametro_and_die($idsDom, "Faltan parámetros");

$client_contact= $database->query("select cc.ID, NOMBRE_CONTACTO, EMAIL, TELEFONO_FIJO, TELEFONO_MOVIL,
			NOMBRE_DOMICILIO from CLIENTES_CONTACTOS cc inner join CLIENTES_DOMICILIOS cd
			on cc.ID_CLIENTE_DOMICILIO=cd.ID where ID_CLIENTE_DOMICILIO in ".$idsDom);
$client_contact=$client_contact->fetchAll();

valida_error_medoo_and_die();

print_r(json_encode($client_contact));


//-------- FIN --------------
?>