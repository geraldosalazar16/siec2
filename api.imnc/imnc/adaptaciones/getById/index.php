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

$clientes = $database->get("CLIENTES", "*", ["ID"=>$id]);
valida_error_medoo_and_die();

// $arr_contactos = $database->select("CLIENTES_CONTACTOS", "*", ["ID_CLIENTE"=>$id]);
// valida_error_medoo_and_die();

$domicilios = $database->select("CLIENTES_DOMICILIOS", "*", ["ID_CLIENTE"=>$id]);

valida_error_medoo_and_die();

for ($i=0; $i < count($domicilios); $i++) { 	
	$contactos = $database->select("CLIENTES_CONTACTOS", "*", ["ID_CLIENTE_DOMICILIO"=>$domicilios[$i]["ID"]]);
	valida_error_medoo_and_die();
	$domicilios[$i]["CONTACTOS"] = $contactos;
}

$otras_razones_sociales = $database->select("CLIENTES_RAZONES_SOCIALES", "*", ["ID_CLIENTE"=>$clientes["ID"]]);
valida_error_medoo_and_die();
$clientes["OTRAS_RAZONES_SOCIALES"] = $otras_razones_sociales;


// $clientes["CONTACTOS"] = $arr_contactos;
$clientes["CONTACTOS"] = $contactos;
$clientes["DOMICILIOS"] = $domicilios;

print_r(json_encode($clientes));


//-------- FIN --------------
?>