<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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

$ID = $objeto->ID; 
$ID_PROSPECTO = $objeto->ID_PROSPECTO; 
valida_parametro_and_die($ID_PROSPECTO,"Falta ID de USUARIO");
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
$REFERENCIA = $objeto->REFERENCIA;
$TARIFA = $objeto->TARIFA;
if($ID_SERVICIO != 3){
	//No se necesita para Certificacion Personas,&& $ID_TIPO_SERVICIO != 18
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
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($COMPLEJIDAD,"Falta COMPLEJIDAD");
$BANDERA = $objeto->BANDERA;

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

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$id = $database->update("COTIZACIONES", [ 
	"ID_PROSPECTO" => $ID_PROSPECTO, 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
	"ETAPA" => $ETAPA,
	"ESTADO_COTIZACION" => $ESTADO_COTIZACION, 
	"FOLIO_SERVICIO" => $FOLIO_SERVICIO, 
	"FOLIO_INICIALES" => $FOLIO_INICIALES,
	"TARIFA" => $TARIFA,
	"DESCUENTO" => $DESCUENTO,
	"AUMENTO" => $AUMENTO,
	"REFERENCIA" => $REFERENCIA,
	"SG_INTEGRAL" => $SG_INTEGRAL,
	"BANDERA" => $BANDERA,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"COMBINADA" => $COMBINADA,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
], ["ID"=>$ID]); 

valida_error_medoo_and_die(); 

if($ID_SERVICIO != 3){
	//Borrar todas las normas
	$id = $database->delete("COTIZACION_NORMAS", 
		[
			"AND" => [
				"ID_COTIZACION" => $ID
			]		
		]);
	//iNSERTAR LAS NORMAS
	for ($i=0; $i < count($NORMAS); $i++) {
		$id_norma = $NORMAS[$i]->ID_NORMA;
		$id_cotizacion_normas = $database->insert("COTIZACION_NORMAS", [
			"ID_COTIZACION" => $ID,
			"ID_NORMA" => $id_norma
		]);
		valida_error_medoo_and_die();
	}
} else { //CIFA
	//para CIFA insertar los detalles MODALIDAD y ID_CURSO
	//Si MODALIDAD = programado ID_CURSO => columna ID_CURSO en CURSOS_PROGRAMADOS
	//Si MODALIDAD = insitu ID_CURSO => columna ID_CURSO en CURSOS
	$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
		"VALOR"	=>	$MODALIDAD
	],["AND" => [
			"ID_COTIZACION" => $ID,
			"DETALLE" => "MODALIDAD"
		]
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
		"VALOR"	=>	$ID_CURSO
	],["AND"=>[
			"ID_COTIZACION" => $ID,
			"DETALLE" => "ID_CURSO"
		]
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
		"VALOR"	=>	$CANT_PARTICIPANTES
	],["AND"=>[
			"ID_COTIZACION" => $ID,
			"DETALLE" => "CANT_PARTICIPANTES"
		]
	]);
	valida_error_medoo_and_die();
	$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
		"VALOR"	=>	$SOLO_CLIENTE
	],["AND"=>[
			"ID_COTIZACION" => $ID,
			"DETALLE" => "SOLO_CLIENTE"
		]
	]);
	valida_error_medoo_and_die();
}

//Si la cotizacion tiene algun detalle que deba ser guardado en la tabla cotizacion detalles.
switch($ID_TIPO_SERVICIO){
	case 16:
		$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
			"VALOR"	=>	$ACTIVIDAD_ECONOMICA
		],["AND"=>["ID_COTIZACION" => $ID,"DETALLE" => "SECTOR",]]);
		valida_error_medoo_and_die();
		break;
	case 17:
		
		break;
	case 18:
			$id_cotizacion_detalles = $database->update("COTIZACION_DETALLES", [
			"VALOR"	=>	$DICTAMEN_CONSTANCIA
			],["AND"=>["ID_COTIZACION" => $ID,"DETALLE" => "DICTAMEN_O_CONSTANCIA",]]);
			
			valida_error_medoo_and_die();
		break;	
	default: 
		break;
}

/*		CODIGO PARA AGREGAR FECHAS EN QUE SE CAMBIAN LOS ESTADOS		*/
$cons1	=	$database->get("COTIZACIONES_STATUS_FECHA","*",["ID_COTIZACION"=>$ID]);
if($cons1 == null){
	if($ESTADO_COTIZACION==1){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_SOLICITUD_COTIZACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==2){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_ENVIO_COTIZACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==3){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_NEGOCIACION"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==4){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_FIRMADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==5){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_PEDIDO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==6){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_CANCELADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==7){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_EJECUTADO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==8){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_ENVIO_CUESTIONARIO"=>date('Y-m-d')]);

	}
	if($ESTADO_COTIZACION==9){
		$cons2	=	$database->insert("COTIZACIONES_STATUS_FECHA",["ID_COTIZACION"=>$ID,"ID_ESTATUS_SEGUIMIENTO"=>$ESTADO_COTIZACION, "FECHA_RECEPCION_CUESTIONARIO"=>date('Y-m-d')]);

	}
	valida_error_medoo_and_die();

}
else {
	if ($cons1["ID_ESTATUS_SEGUIMIENTO"] != $ESTADO_COTIZACION) {
		if ($ESTADO_COTIZACION == 1) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_SOLICITUD_COTIZACION" => date('Y-m-d')]);
		}
		if ($ESTADO_COTIZACION == 2) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_ENVIO_COTIZACION" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 3) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_NEGOCIACION" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 4) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_FIRMADO" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 5) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_PEDIDO" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 6) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_CANCELADO" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 7) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_EJECUTADO" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 8) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_ENVIO_CUESTIONARIO" => date('Y-m-d')]);

		}
		if ($ESTADO_COTIZACION == 9) {
			$cons3 = $database->update("COTIZACIONES_STATUS_FECHA", ["ID_COTIZACION" => $ID, "ID_ESTATUS_SEGUIMIENTO" => $ESTADO_COTIZACION, "FECHA_RECEPCION_CUESTIONARIO" => date('Y-m-d')]);

		}
	}
}

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
