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

$ID_COTIZACION = $objeto->ID;  
$BANDERA = $objeto->BANDERA;
$ID_SERVICIO = $objeto->ID_SERVICIO;
$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO;
$SG_INTEGRAL = $objeto->SG_INTEGRAL == "si"? "S" : "N";
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$tramite = $objeto->OBJ_TRAMITE;
$ID_TRAMITE = $tramite->ID;
$ID_ETAPA_PROCESO = $tramite->ID_ETAPA_PROCESO;
$CAMBIO = $tramite->CAMBIO; 


$TOTAL_EMPLEADOS = 0;
$TOTAL_EMPLEADOS_PARA_CERTIFICACION = 0;
$TURNOS = 0;

$SITIOS = $database->select("COTIZACION_SITIOS", "*",["ID_COTIZACION"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	foreach ($SITIOS as $key => $sitio) {
		$TOTAL_EMPLEADOS += $sitio["TOTAL_EMPLEADOS"]; 
		$TOTAL_EMPLEADOS_PARA_CERTIFICACION += $sitio["NUMERO_EMPLEADOS_CERTIFICACION"];
		$TURNOS += $sitio["CANTIDAD_TURNOS"];
	}

$servicio = $objeto->OBJ_SERVICIO;
$REFERENCIA = $servicio->REFERENCIA;
$ID_CLIENTE = $servicio->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Se necesita un cliente registrado");
$ID_NORMA = $servicio->ID_NORMA;
$MULTISITIOS = $servicio->MULTISITIOS;
valida_parametro_and_die($MULTISITIOS, "Se necesita indicar multisitios");
$CONDICIONES_SEGURIDAD = $servicio->CONDICIONES_SEGURIDAD;
valida_parametro_and_die($CONDICIONES_SEGURIDAD, "Se necesita indicar condiciones de seguridad");
$ALCANCE = $servicio->ALCANCE;
valida_parametro_and_die($ALCANCE, "Se necesita indicar el alcance");


$ID_SERVICIO_CLIENTE = $servicio->ID_SERVICIO_CLIENTE;
if($ID_SERVICIO_CLIENTE == 0){
	$ID_SERVICIO_CLIENTE = $database->insert("SERVICIO_CLIENTE_ETAPA", [ 
		"ID_CLIENTE" => $ID_CLIENTE, 
		"ID_SERVICIO" => $ID_SERVICIO, 
		"REFERENCIA" => $REFERENCIA,
		"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,
		"SG_INTEGRAL" => $SG_INTEGRAL,
		"CAMBIO" => $CAMBIO,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
	]); 
	valida_error_medoo_and_die();
}

$id = $database->update("COTIZACIONES_TRAMITES", [ 
	"ID_SERVICIO_CLIENTE" => $ID_SERVICIO_CLIENTE,
	"FECHA_MODIFICACION" => $FECHA_CREACION,
	"HORA_MODIFICACION" => $HORA_CREACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_CREACION
], ["ID"=>$ID_TRAMITE]); 

$id_new_tipo_servicio = $database->insert("SG_TIPOS_SERVICIO", [ 
	"ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE, 
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO, 
	"TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS,
	"TOTAL_EMPLEADOS_PARA_CERTIFICACION" => $TOTAL_EMPLEADOS_PARA_CERTIFICACION,
	"TURNOS" => $TURNOS,
	"ID_NORMA" => $ID_NORMA,
	"MULTISITIOS" => $MULTISITIOS,
	"CONDICIONES_SEGURIDAD" => $CONDICIONES_SEGURIDAD,
	"ALCANCE" => $ALCANCE,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]);
valida_error_medoo_and_die();

$CAMBIOS = $database->select("SERVICIO_COTIZACION_CAMBIO", "*", ["ID_TRAMITE"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	foreach ($CAMBIOS as $key => $cambio) {
		$ID_CAMBIO = $cambio["ID_CAMBIO"];
		$DESCRIPCION = $cambio["DESCRIPCION"];

		$id = $database->insert("SERVICIO_CLIENTE_CAMBIO", [ 
			"ID_SERVICIO_CLIENTE" => $ID_SERVICIO_CLIENTE, 
			"ID_CAMBIO" => $ID_CAMBIO, 
			"DESCRIPCION" => $DESCRIPCION 
		]); 
		valida_error_medoo_and_die(); 
	}


	$SITIOS = $database->select("COTIZACION_SITIOS", "*",["ID_COTIZACION"=>$ID_TRAMITE]);
	valida_error_medoo_and_die(); 
	foreach ($SITIOS as $key => $sitio) {
		$ID_SITIO = $sitio["ID"]; 
		$TOTAL_EMPLEADOS = $sitio["TOTAL_EMPLEADOS"]; 
		$NUMERO_EMPLEADOS_CERTIFICACION = $sitio["NUMERO_EMPLEADOS_CERTIFICACION"];
		$CANTIDAD_TURNOS = $sitio["CANTIDAD_TURNOS"];
		$CANTIDAD_DE_PROCESOS = $sitio["CANTIDAD_DE_PROCESOS"];
		$TEMPORAL_O_FIJO = $sitio["TEMPORAL_O_FIJO"];
		$MATRIZ_PRINCIPAL = $sitio["MATRIZ_PRINCIPAL"];
		$ID_ACTIVIDAD = $sitio["ID_ACTIVIDAD"];
		
		$ID_DOMICILIO_SITIO = $sitio["ID_DOMICILIO_SITIO"];
		if($BANDERA == 0){
			$id_dom = $database->get("PROSPECTO_DOMICILIO", "ID_CLIENTE_DOMICILIO",["ID"=>$ID_DOMICILIO_SITIO]);
			$ID_DOMICILIO_SITIO = $id_dom;
		}

		$id_new_sitio = $database->insert("SG_SITIOS", [ 
			"ID_SG_TIPO_SERVICIO" => $id_new_tipo_servicio, 
			"ID_CLIENTE_DOMICILIO" => $ID_DOMICILIO_SITIO,
			"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS, 
			"NUMERO_TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS, 
			"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION, 
			"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS, 
			"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO, 
			"ID_ACTIVIDAD" => $ID_ACTIVIDAD, 
			"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL, 
			"FECHA_CREACION" => $FECHA_CREACION,
			"HORA_CREACION" => $HORA_CREACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]); 
		valida_error_medoo_and_die(); 

		$id = $database->update("COTIZACION_SITIOS", [ 
			"ID_SG_SITIO" => $id_new_sitio,
			"FECHA_MODIFICACION" => $FECHA_CREACION,
			"HORA_MODIFICACION" => $HORA_CREACION,
			"ID_USUARIO_MODIFICACION" => $ID_USUARIO_CREACION
			], ["ID"=>$ID_SITIO]); 
	}

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
