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


$client_contact = $database->select("CLIENTES_CONTACTOS",
      [
          "[><]TIPOS_CONTACTO" => ["ID_TIPO_CONTACTO" => "ID"],
      ],
      [
          "CLIENTES_CONTACTOS.ID",
          "CLIENTES_CONTACTOS.ID_CLIENTE_DOMICILIO",
          "CLIENTES_CONTACTOS.ID_TIPO_CONTACTO",
          "CLIENTES_CONTACTOS.DESCRIPCION_CONTACTO",
          "CLIENTES_CONTACTOS.ES_PRINCIPAL",
          "CLIENTES_CONTACTOS.NOMBRE_CONTACTO",
          "CLIENTES_CONTACTOS.CARGO",
          "CLIENTES_CONTACTOS.TELEFONO_MOVIL",
          "CLIENTES_CONTACTOS.TELEFONO_FIJO",
          "CLIENTES_CONTACTOS.EXTENSION",
          "CLIENTES_CONTACTOS.EMAIL",
          "CLIENTES_CONTACTOS.EMAIL2",
          "CLIENTES_CONTACTOS.DATOS_ADICIONALES",
          "CLIENTES_CONTACTOS.FECHA_INICIO",
          "CLIENTES_CONTACTOS.FECHA_FIN",
          "CLIENTES_CONTACTOS.FECHA_CREACION",
          "CLIENTES_CONTACTOS.HORA_CREACION",
          "CLIENTES_CONTACTOS.FECHA_MODIFICACION",
          "CLIENTES_CONTACTOS.HORA_MODIFICACION",
          "CLIENTES_CONTACTOS.ID_USUARIO_CREACION",
          "CLIENTES_CONTACTOS.ID_USUARIO_MODIFICACION",
          "TIPOS_CONTACTO.TIPO",

      ]
    );
valida_error_medoo_and_die();

print_r(json_encode($client_contact));


//-------- FIN --------------
?>
