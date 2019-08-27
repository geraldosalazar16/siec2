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
$campos = ["CLIENTES_CONTACTOS.ID","CLIENTES_CONTACTOS.NOMBRE_CONTACTO(NOMBRE)",
"NOMBRE_DOMICILIO","CLIENTES_CONTACTOS.EMAIL(CORREO)","TELEFONO_FIJO(TELEFONO)","TELEFONO_MOVIL(CELULAR)",
"CLIENTES_CONTACTOS.FECHA_CREACION","CLIENTES_CONTACTOS.FECHA_MODIFICACION",
"UC.NOMBRE(NOMBRE_USUARIO_CREAR)","UM.NOMBRE(NOMBRE_USUARIO_MOD)","DATOS_ADICIONALES"];

$client_contact = $database->get("CLIENTES_CONTACTOS",
	["[>]USUARIOS(UC)" => ["ID_USUARIO_CREACION" => "ID"],"[>]USUARIOS(UM)" => ["ID_USUARIO_MODIFICACION" => "ID"],"[>]CLIENTES_DOMICILIOS" => ["CLIENTES_CONTACTOS.ID_CLIENTE_DOMICILIO" => "ID"]],
	$campos,["CLIENTES_CONTACTOS.ID"=>$id]);

valida_error_medoo_and_die();

print_r(json_encode($client_contact));


//-------- FIN --------------
?>