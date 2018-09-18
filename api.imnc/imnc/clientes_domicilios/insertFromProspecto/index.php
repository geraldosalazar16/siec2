<?php 
include  '../../ex_common/query.php'; 

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$ID_CLIENTE = $objeto->ID_CLIENTE;
$DOMICILIOS = $objeto->DOMICILIOS;
$CONTACTOS = $objeto->CONTACTOS;
$CONCTACTO_DOMICILIO = array();

foreach ($CONTACTOS as $key => $contacto) {
	if(!array_key_exists ( $contacto->ID_PROSPECTO_DOMICILIO, $CONCTACTO_DOMICILIO )){
		$CONCTACTO_DOMICILIO[ $contacto->ID_PROSPECTO_DOMICILIO ] = [ $contacto ];
	}
	else{
		array_push ( $CONCTACTO_DOMICILIO[ $contacto->ID_PROSPECTO_DOMICILIO ] , $contacto );
	}
}

foreach ($DOMICILIOS as $key => $domicilio) {
	$ID = $domicilio->ID;
	$NOMBRE_DOMICILIO = $domicilio->NOMBRE;
	$CALLE = $domicilio->CALLE;
	$NUMERO_EXTERIOR = $domicilio->NUMERO_EXTERIOR;
	$NUMERO_INTERIOR = $domicilio->NUMERO_INTERIOR; // opcional
	$COLONIA_BARRIO = $domicilio->COLONIA;
	$DELEGACION_MUNICIPIO = $domicilio->MUNICIPIO;
	$ENTIDAD_FEDERATIVA = $domicilio->ESTADO;
	$CP = $domicilio->CODIGO_POSTAL;
	$PAIS = $domicilio->PAIS;
	$ES_FISCAL = ($domicilio->FISCAL == 1)? 'si' : 'no';
	$ID_USUARIO_CREACION = $domicilio->ID_USUARIO_CREACION;
	$FECHA_CREACION = date("Ymd");
	$HORA_CREACION = date("His");

	$client_dom = $database->insert("CLIENTES_DOMICILIOS", 
		[
		"ID_CLIENTE" => $ID_CLIENTE,
		"NOMBRE_DOMICILIO" => $NOMBRE_DOMICILIO,
		"CALLE" => $CALLE,
		"NUMERO_EXTERIOR" => $NUMERO_EXTERIOR,
		"NUMERO_INTERIOR" => $NUMERO_INTERIOR,
		"COLONIA_BARRIO" => $COLONIA_BARRIO,
		"DELEGACION_MUNICIPIO" => $DELEGACION_MUNICIPIO,
		"ENTIDAD_FEDERATIVA" => $ENTIDAD_FEDERATIVA,
		"CP" => $CP,
		"PAIS" => $PAIS,
		"ES_FISCAL" => $ES_FISCAL,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
	]);

	$id = $database->update("PROSPECTO_DOMICILIO", [ 
		"ID_CLIENTE_DOMICILIO" => $client_dom
	], ["ID"=>$ID]); 

	foreach ($CONCTACTO_DOMICILIO[$ID] as $key => $contacto) {
	    $NOMBRE_CONTACTO = $contacto->NOMBRE;
	    $EMAIL = $contacto->CORREO;
	    $TELEFONO_FIJO = $contacto->TELEFONO;
	    $TELEFONO_MOVIL = $contacto->CELULAR;
        $CARGO = $contacto->PUESTO;
        $DATOS_ADICIONALES = $contacto->DATOS_ADICIONALES;
		$client_contact = $database->insert("CLIENTES_CONTACTOS", [
			"ID_CLIENTE_DOMICILIO" => $client_dom,
			"ID_TIPO_CONTACTO" => '--',
			"NOMBRE_CONTACTO" => $NOMBRE_CONTACTO,
			"CARGO" => $CARGO,
			"TELEFONO_MOVIL" => $TELEFONO_MOVIL,
			"TELEFONO_FIJO" => $TELEFONO_FIJO,
			"EXTENSION" => '',
			"EMAIL" => $EMAIL,
			"DATOS_ADICIONALES" => $DATOS_ADICIONALES,
			"FECHA_CREACION" => $FECHA_CREACION,
			"FECHA_INICIO" => $FECHA_CREACION,
			"FECHA_FIN" => $FECHA_CREACION,
			"HORA_CREACION" => $HORA_CREACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]);
		valida_error_medoo_and_die("CLIENTES_CONTACTOS","jesus.popocatl@dhttecno.com");
	}

	valida_error_medoo_and_die("CLIENTES_DOMICILIOS","jesus.popocatl@dhttecno.com");
}


$respuesta['resultado']="ok";
$respuesta['id']=$client_dom;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>