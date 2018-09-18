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
$ID_COTIZACION_ANT = $objeto->ID; 
$ID_PROSPECTO = $objeto->ID_PROSPECTO; 
$ID_SERVICIO = $objeto->ID_SERVICIO; 
$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 
$ESTADO_COTIZACION = $objeto->ESTADO_COTIZACION; 
$FOLIO_SERVICIO = $objeto->FOLIO_SERVICIO; 
$FOLIO_INICIALES = $objeto->FOLIO_INICIALES; 
$REFERENCIA = $objeto->REFERENCIA;
$TARIFA = $objeto->TARIFA;
$DESCUENTO = $objeto->DESCUENTO;
$SG_INTEGRAL = $objeto->SG_INTEGRAL;
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
$BANDERA = $objeto->BANDERA; 
$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
$FOLIO_MES = $objeto->FOLIO_MES;
$FOLIO_YEAR = $objeto->FOLIO_YEAR;
$FOLIO_CONSECUTIVO = $objeto->FOLIO_CONSECUTIVO;
$FOLIO_UPDATE = $objeto->FOLIO_UPDATE;
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id_new_cotizacion = $database->insert("COTIZACIONES", [ 
	"ID_COTIZACION_ANT" => $ID_COTIZACION_ANT,
	"ID_PROSPECTO" => $ID_PROSPECTO, 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO, 
	"ESTADO_COTIZACION" => $ESTADO_COTIZACION, 
	"FOLIO_SERVICIO" => $FOLIO_SERVICIO, 
	"FOLIO_INICIALES" => $FOLIO_INICIALES, 
	"FOLIO_CONSECUTIVO" => $FOLIO_CONSECUTIVO, 
	"FOLIO_MES" => $FOLIO_MES, 
	"FOLIO_YEAR" => $FOLIO_YEAR,
	"FOLIO_UPDATE" => $FOLIO_UPDATE,
	"REFERENCIA" => $REFERENCIA,
	"TARIFA" => $TARIFA,
	"DESCUENTO" => $DESCUENTO,
	"SG_INTEGRAL" => $SG_INTEGRAL,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"BANDERA" => $BANDERA,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 
valida_error_medoo_and_die();

$TRAMITES = $objeto->COTIZACION_TRAMITES;
foreach ($TRAMITES as $key => $tramite) {
	$ID_TRAMITE = $tramite->ID;
	$ID_ETAPA_PROCESO = $tramite->ID_ETAPA_PROCESO;
	$VIATICOS = $tramite->VIATICOS; 
	$DESCUENTO = $tramite->DESCUENTO; 
	$FACTOR_INTEGRACION = $tramite->FACTOR_INTEGRACION; 
	$JUSTIFICACION = $tramite->JUSTIFICACION; 
	$CAMBIO = $tramite->CAMBIO; 
	$ID_SERVICIO_CLIENTE = $tramite->ID_SERVICIO_CLIENTE;

	$id_new_tramite = $database->insert("COTIZACIONES_TRAMITES", [ 
		"ID_COTIZACION" => $id_new_cotizacion,
		"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,
		"VIATICOS" => $VIATICOS,
		"DESCUENTO" => $DESCUENTO,
		"FACTOR_INTEGRACION" => $FACTOR_INTEGRACION,
		"JUSTIFICACION" => $JUSTIFICACION,
		"CAMBIO" => $CAMBIO,
		"ID_SERVICIO_CLIENTE" => $ID_SERVICIO_CLIENTE,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
	]); 
	valida_error_medoo_and_die(); 

	$SITIOS = $database->select("COTIZACION_SITIOS", "*",["ID_COTIZACION"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	foreach ($SITIOS as $key => $sitio) {
		$TOTAL_EMPLEADOS = $sitio["TOTAL_EMPLEADOS"]; 
		$ID_DOMICILIO_SITIO = $sitio["ID_DOMICILIO_SITIO"];
		$SELECCIONADO = $sitio["SELECCIONADO"]; 
		$NUMERO_EMPLEADOS_CERTIFICACION = $sitio["NUMERO_EMPLEADOS_CERTIFICACION"];
		$CANTIDAD_TURNOS = $sitio["CANTIDAD_TURNOS"];
		$CANTIDAD_DE_PROCESOS = $sitio["CANTIDAD_DE_PROCESOS"];
		$TEMPORAL_O_FIJO = $sitio["TEMPORAL_O_FIJO"];
		$MATRIZ_PRINCIPAL = $sitio["MATRIZ_PRINCIPAL"];
		$FACTOR_REDUCCION = $sitio["FACTOR_REDUCCION"];
		$FACTOR_AMPLIACION = $sitio["FACTOR_AMPLIACION"];
		$JUSTIFICACION_SITIO = $sitio["JUSTIFICACION"];
		$ID_ACTIVIDAD = $sitio["ID_ACTIVIDAD"];
		$ID_SG_SITIO = $sitio["ID_SG_SITIO"];

		$id = $database->insert("COTIZACION_SITIOS", [ 
			"ID_COTIZACION" => $id_new_tramite, 
			"TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS, 
			"ID_DOMICILIO_SITIO" => $ID_DOMICILIO_SITIO,
			"SELECCIONADO" => $SELECCIONADO, 
			"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION,
			"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS,
			"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS,
			"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO,
			"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL,
			"FACTOR_REDUCCION" => $FACTOR_REDUCCION,
			"FACTOR_AMPLIACION" => $FACTOR_AMPLIACION,
			"JUSTIFICACION" => $JUSTIFICACION_SITIO,
			"ID_ACTIVIDAD" => $ID_ACTIVIDAD,
			"ID_SG_SITIO" => $ID_SG_SITIO,
			"FECHA_CREACION" => $FECHA_CREACION,
			"HORA_CREACION" => $HORA_CREACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]); 
		valida_error_medoo_and_die(); 
	}

	$TARIFA_AD = $database->select("COTIZACION_TARIFA_ADICIONAL", "*", ["ID_TRAMITE"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	$ID = $database->max("COTIZACION_TARIFA_ADICIONAL","ID");
	foreach ($TARIFA_AD as $key => $tarifa) {
		$ID++;
		$CANTIDAD = $tarifa["CANTIDAD"];
		$ID_TARIFA_ADICIONAL = $tarifa["ID_TARIFA_ADICIONAL"];

		$id = $database->insert("COTIZACION_TARIFA_ADICIONAL", [ 
			"ID" => $ID,	
			"ID_TARIFA_ADICIONAL" => $ID_TARIFA_ADICIONAL, 
			"ID_TRAMITE" => $id_new_tramite,
			"CANTIDAD" => $CANTIDAD
		]); 
		valida_error_medoo_and_die(); 
	}

	$CAMBIOS = $database->select("SERVICIO_COTIZACION_CAMBIO", "*", ["ID_TRAMITE"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	foreach ($CAMBIOS as $key => $cambio) {
		$ID_CAMBIO = $cambio["ID_CAMBIO"];
		$DESCRIPCION = $cambio["DESCRIPCION"];

		$id = $database->insert("SERVICIO_COTIZACION_CAMBIO", [ 
			"ID_TRAMITE" => $id_new_tramite, 
			"ID_CAMBIO" => $ID_CAMBIO, 
			"DESCRIPCION" => $DESCRIPCION 
		]); 
		valida_error_medoo_and_die(); 
	}
}

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
