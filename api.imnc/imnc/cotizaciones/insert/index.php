<?php
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';


/*
include  '../../ex_common/query.php';
include 'funciones.php';
include '../../ex_common/archivos.php';
*/

function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "" . $parametro;
	if ($parametro == "") {
		$respuesta["resultado"] = "error";
		$respuesta["mensaje"] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) {
		$respuesta["resultado"]="error";
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);

$ID_PRODUCTO = $objeto->ID_PRODUCTO;

$ID_PROSPECTO = $objeto->ID_PROSPECTO;
valida_parametro_and_die($ID_PROSPECTO,"Falta ID de prospecto");
$ID_SERVICIO = $objeto->ID_SERVICIO;
valida_parametro_and_die($ID_SERVICIO,"Falta ID de SERVICIO");
$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO;
valida_parametro_and_die($ID_TIPO_SERVICIO,"Falta ID de TIPO DE SERVICIO");
$NORMAS = $objeto->NORMAS;
if(count($NORMAS) == 0 && $ID_SERVICIO != 3){ // No se usan normas en CIFA
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Es necesario seleccionar una norma";
		print_r(json_encode($respuesta));
		die();
}
$ETAPA = $objeto->ETAPA;
valida_parametro_and_die($ETAPA,"Falta la etapa");
$ESTADO_COTIZACION = $objeto->ESTADO_COTIZACION;
valida_parametro_and_die($ESTADO_COTIZACION,"Falta ESTADO COTIZACION");
$FOLIO_SERVICIO = $objeto->FOLIO_SERVICIO;
valida_parametro_and_die($FOLIO_SERVICIO,"Falta FOLIO SERVICIO");
$FOLIO_INICIALES = $objeto->FOLIO_INICIALES;
valida_parametro_and_die($FOLIO_INICIALES,"Falta FOLIO INICIALES");
$TARIFA = $objeto->TARIFA;


if($ID_SERVICIO != 3){
	//No se necesita para Certificacion Personas,informacion_comercial
	if($ID_TIPO_SERVICIO != 19 ){
		valida_parametro_and_die($TARIFA,"Falta seleccionar la Tarifa");
	} else {
		if(!$TARIFA){
			$TARIFA = 0;
		}
	}
	
} else {
	if(!$TARIFA){
		$TARIFA = "";

	}
}

$DESCUENTO = $objeto->DESCUENTO;
$AUMENTO = $objeto->AUMENTO;
$REFERENCIA = $objeto->REFERENCIA;
if(!$REFERENCIA){
	$REFERENCIA = "";
}
/*
$SG_INTEGRAL = $objeto->SG_INTEGRAL;
valida_parametro_and_die($SG_INTEGRAL,"Falta INTEGRAL");
*/
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
if($ID_SERVICIO == 1){
	valida_parametro_and_die($COMPLEJIDAD,"Falta COMPLEJIDAD");
}else {
	if(!$COMPLEJIDAD){
		$COMPLEJIDAD = 0;
	}
}
$BANDERA = $objeto->BANDERA;

$COMBINADA = $objeto->COMBINADA;
//SOLO ES OBLIGATORIO PARA INTEGRAL
if($ID_TIPO_SERVICIO == 20){
	valida_parametro_and_die($COMBINADA,"Falta COMBINADA");
} else {
	if(!$COMBINADA){
		$COMBINADA = 0;
	}
}
$ACTIVIDAD_ECONOMICA = $objeto->ACTIVIDAD_ECONOMICA;
//SOLO ES OBLIGATORIO PARA IGUALDAD LABORAL
if($ID_TIPO_SERVICIO == 16){
	valida_parametro_and_die($ACTIVIDAD_ECONOMICA,"Falta la Actividad Economica");
} else {
	if(!$ACTIVIDAD_ECONOMICA){
		$ACTIVIDAD_ECONOMICA = 0;
	}
}
//SOLO ES OBLIGATORIO PARA UNIDAD VERIFICACION INFORMACION COMERCIAL
$DICTAMEN_CONSTANCIA = $objeto->DICTAMEN_CONSTANCIA;
if($ID_TIPO_SERVICIO == 18){
	valida_parametro_and_die($DICTAMEN_CONSTANCIA,"Falta el DICTAMEN_CONSTANCIA");
} else {
	if(!$DICTAMEN_CONSTANCIA){
		$DICTAMEN_CONSTANCIA = 0;
	}
}

if ($DESCUENTO != "" && ($DESCUENTO < 0 || $DESCUENTO > 100)) {
	$respuesta["resultado"] = "error";
	$respuesta["mensaje"] = "El Descuento no puede ser menor al 0% ni mayor al 100%";
	print_r(json_encode($respuesta));
	die();
}
if ($AUMENTO != "" && ($AUMENTO < 0 || $AUMENTO > 100)) {
	$respuesta["resultado"] = "error";
	$respuesta["mensaje"] = "El Aumento no puede ser menor al 0% ni mayor al 100%";
	print_r(json_encode($respuesta));
	die();
}

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

//Solo para CIFA
$MODALIDAD = "";
$ID_CURSO = "";
$CANT_PARTICIPANTES = 0;
$SOLO_CLIENTE = "";
if($ID_SERVICIO == 3){
	$MODALIDAD = $objeto->MODALIDAD;
	valida_parametro_and_die($MODALIDAD,"Falta MODALIDAD");
	$ID_CURSO = $objeto->ID_CURSO;
	valida_parametro_and_die($ID_CURSO,"Falta ID CURSO");
	$SOLO_CLIENTE = $objeto->SOLO_CLIENTE;
	valida_parametro_and_die($SOLO_CLIENTE,"Falta SOLO_CLIENTE");
	if($SOLO_CLIENTE == 0){
		$CANT_PARTICIPANTES = $objeto->CANT_PARTICIPANTES;
		valida_parametro_and_die($CANT_PARTICIPANTES,"Falta CANT_PARTICIPANTES");
	} else if($SOLO_CLIENTE == 1){
		$CANT_PARTICIPANTES = 1;
	}
	
}


$FOLIO_MES = date("m");
$FOLIO_YEAR = date("y");

$FOLIO_CONSECUTIVO = $database->max("COTIZACIONES", "FOLIO_CONSECUTIVO", ["FOLIO_YEAR" => $FOLIO_YEAR]);
if(is_null($FOLIO_CONSECUTIVO))
	$FOLIO_CONSECUTIVO = 0;
else
	$FOLIO_CONSECUTIVO += 1;

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

//Verificar que el servicio no exista, excepto para CIFA
if($ID_SERVICIO != 3){
	$id_cotizacion = $database->get("COTIZACIONES",
		"*",
		[
			"AND" => [
				"ID_PROSPECTO" => $ID_PROSPECTO,
				"ID_SERVICIO" => $ID_SERVICIO,
				"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO
		]
	]);
	if( $ID_TIPO_SERVICIO == 17 && ($database->count("COTIZACION_NORMAS",["AND" =>["ID_NORMA" => $NORMAS[0]->ID_NORMA ,"ID_COTIZACION"=> $id_cotizacion["ID"] ]]) ==0)){
		$id_cotizacion = 0;
	}
	valida_error_medoo_and_die();

	if($id_cotizacion != 0){
		$respuesta["resultado"] = "error";
		$respuesta["mensaje"] = "La cotización que intenta ingresar ya existe";
		print_r(json_encode($respuesta));
		die();
	}
}

$id_cotizacion = $database->insert("COTIZACIONES", [
	"ID_PROSPECTO" => $ID_PROSPECTO,
	"ID_SERVICIO" => $ID_SERVICIO,
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
	"ETAPA" => $ETAPA,
	"ESTADO_COTIZACION" => $ESTADO_COTIZACION,
	"FOLIO_SERVICIO" => $FOLIO_SERVICIO,
	"FOLIO_INICIALES" => $FOLIO_INICIALES,
	"FOLIO_CONSECUTIVO" => $FOLIO_CONSECUTIVO,
	"FOLIO_MES" => $FOLIO_MES,
	"FOLIO_YEAR" => $FOLIO_YEAR,
	"TARIFA" => $TARIFA,
	"DESCUENTO" => $DESCUENTO,
	"AUMENTO" => $AUMENTO,
	"REFERENCIA" => $REFERENCIA,
	//"SG_INTEGRAL" => $SG_INTEGRAL,
	"BANDERA" => $BANDERA,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"COMBINADA" => $COMBINADA,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

//iNSERTAR LAS NORMAS
if($ID_SERVICIO != 3){
	for ($i=0; $i < count($NORMAS); $i++) {
		$id_norma = $NORMAS[$i]->ID_NORMA;
		$id_cotizacion_normas = $database->insert("COTIZACION_NORMAS", [
			"ID_COTIZACION" => $id_cotizacion,
			"ID_NORMA" => $id_norma
		]);
		valida_error_medoo_and_die();
	}

	//Si la cotizacion tiene algun detalle que deba ser guardado en la tabla cotizacion detalles.
	switch($ID_TIPO_SERVICIO){
		case 16:
			$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
				"ID_COTIZACION" => $id_cotizacion,
				"DETALLE" => "SECTOR",
				"VALOR"	=>	$ACTIVIDAD_ECONOMICA
			]);
			valida_error_medoo_and_die();
			break;
		case 17:
			
			break;
		case 18:
			$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
				"ID_COTIZACION" => $id_cotizacion,
				"DETALLE" => "DICTAMEN_O_CONSTANCIA",
				"VALOR"	=>	$DICTAMEN_CONSTANCIA
			]);
			valida_error_medoo_and_die();
			break;
		default: 
			break;
	}

	
} else if($ID_SERVICIO == 3) {
	//para CIFA insertar los detalles MODALIDAD y ID_CURSO
	//Si MODALIDAD = programado ID_CURSO => columna ID_CURSO en CURSOS_PROGRAMADOS
	//Si MODALIDAD = insitu ID_CURSO => columna ID_CURSO en CURSOS
	$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
		"ID_COTIZACION" => $id_cotizacion,
		"DETALLE" => "MODALIDAD",
		"VALOR"	=>	$MODALIDAD
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
		"ID_COTIZACION" => $id_cotizacion,
		"DETALLE" => "ID_CURSO",
		"VALOR"	=>	$ID_CURSO
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
		"ID_COTIZACION" => $id_cotizacion,
		"DETALLE" => "CANT_PARTICIPANTES",
		"VALOR"	=>	$CANT_PARTICIPANTES
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
		"ID_COTIZACION" => $id_cotizacion,
		"DETALLE" => "SOLO_CLIENTE",
		"VALOR"	=>	$SOLO_CLIENTE
	]);
	valida_error_medoo_and_die();
    $id_cotizacion_detalles = $database->insert("COTIZACION_DETALLES", [
        "ID_COTIZACION" => $id_cotizacion,
        "DETALLE" => "TIENE_SERVICIO",
        "VALOR"	=>	0
    ]);
    valida_error_medoo_and_die();
}

//Si todo salio bien agregar el id de la cotizacion al producto
if($id_cotizacion && $id_cotizacion !== 0 && $ID_PRODUCTO){
	$id_producto = $database->update("PROSPECTO_PRODUCTO", 
		[
			"ID_COTIZACION" => $id_cotizacion
		],
		[
			"ID" => $ID_PRODUCTO
		]
	);
	valida_error_medoo_and_die();		
}

/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
	if($ESTADO_COTIZACION==1){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==2){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==3){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_NEGOCIACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==4){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_FIRMADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==5){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_PEDIDO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==6){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_CANCELADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==7){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_EJECUTADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==8){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==9){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$id_cotizacion,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')]);

	}
    valida_error_medoo_and_die();


$respuesta["resultado"]="ok";
$respuesta["id"]=$id;

print_r(json_encode($respuesta));
?>
