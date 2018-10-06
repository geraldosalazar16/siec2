<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
		die(); 
	} 
}  

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_COTIZACION = $objeto->ID_COTIZACION; 
valida_parametro_and_die($ID_COTIZACION, "Es necesario seleccionar una cotizacion");

$ID_CLIENTE = $objeto->ID_CLIENTE; 
valida_parametro_and_die($ID_CLIENTE, "Es necesario seleccionar un cliente");

$CLIENTE_PROSPECTO = $objeto->CLIENTE_PROSPECTO; 
valida_parametro_and_die($CLIENTE_PROSPECTO, "Es necesario indicar si es cliente o prospecto");

$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");

$ID_TIPO_SERVICIO	= $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un tipo de servicio");

$ID_NORMA	= $objeto->ID_NORMA; 
valida_parametro_and_die($ID_NORMA, "Es necesario seleccionar una NORMA");

$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO; 
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");

//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$CAMBIO= $objeto->CAMBIO;

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

//Insertar en SERVICIO_CLIENTE_ETAPA
$ID_FINAL = 0;
if($CLIENTE_PROSPECTO == 'prospecto'){
	//Si es prospecto buscar el id_cliente asociado
	$id_cliente_asociado = $database->get("PROSPECTO","ID_CLIENTE",["ID"=>$ID_CLIENTE]);
	//Validar que tenga cliente asociado
	if($id_cliente_asociado == 0 || !$id_cliente_asociado){
		$respuesta["resultado"] = "error\n"; 
		$respuesta["mensaje"] = "El prospecto debe tener un cliente asociado"; 
		print_r(json_encode($respuesta));
		die();
	} else {
		$ID_FINAL = $id_cliente_asociado;
	}
} else {
	$ID_FINAL = $ID_CLIENTE;
}
$id_servicio_cliente_etapa = $database->insert("SERVICIO_CLIENTE_ETAPA", [ 
	"ID_CLIENTE" => $ID_FINAL, 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ID_TIPO_SERVICIO"=>	$ID_TIPO_SERVICIO,
	"ID_NORMA"=>	$ID_NORMA,
	"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,  
	"REFERENCIA" => $REFERENCIA,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"CAMBIO"=>$CAMBIO,
]); 
valida_error_medoo_and_die(); 

//Insertar en SERVICIO_CLIENTE_ETAPA_HISTÓRICO
if($id_servicio_cliente_etapa	!=	0){
	$id1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
			"ID_SERVICIO_CONTRATADO" => $id, 
			"MODIFICACION" => "NUEVO SERVICIO", 
			"ESTADO_ANTERIOR"=>	"",
			"ESTADO_ACTUAL"=>	"",
			"USUARIO" => $ID_USUARIO_CREACION, 
			"FECHA_USUARIO" => $FECHA_CREACION,
			"FECHA_MODIFICACION" => date("Ymd"),
	
	]); 
	valida_error_medoo_and_die(); 

	//Sitios
	$cant_empleados_total = 0;
	$cant_empleados_cert_total = 0;

	$tramites = $database->select("COTIZACIONES_TRAMITES","*",["ID_COTIZACION" => $ID_COTIZACION]);
	$ids_tramites = [];
	$cont = count($tramites);
	for($i=0;$i<$cont;$i++){
		$ids_tramites[$i] = $tramites[$i]['ID'];
	}
	$domicilios = $database->select("COTIZACION_SITIOS","*",["ID_COTIZACION" => $ids_tramites]);
	if(count($domicilios) > 0){
		for($i=0;$i<count($domicilios);$i++){
			$ID_SERVICIO_CLIENTE_ETAPA = $id_servicio_cliente_etapa; 
			$ID_CLIENTE_DOMICILIO = $domicilios[0]['ID_DOMICILIO_SITIO'];  
			$CANTIDAD_TURNOS = $domicilios[0]['CANTIDAD_TURNOS']; 
			$NUMERO_TOTAL_EMPLEADOS = $domicilios[0]['TOTAL_EMPLEADOS']; 
			$NUMERO_EMPLEADOS_CERTIFICACION = $domicilios[0]['NUMERO_EMPLEADOS_CERTIFICACION'];  
			$CANTIDAD_DE_PROCESOS = $domicilios[0]['CANTIDAD_DE_PROCESOS'];  
			$TEMPORAL_O_FIJO = $domicilios[0]['TEMPORAL_O_FIJO'];  
			$ID_ACTIVIDAD = $domicilios[0]['ID_ACTIVIDAD']; 
			$MATRIZ_PRINCIPAL = $domicilios[0]['MATRIZ_PRINCIPAL']; 

			$cant_empleados_total += $NUMERO_TOTAL_EMPLEADOS;
			$cant_empleados_cert_total += $NUMERO_EMPLEADOS_CERTIFICACION;

			$id_sitio = $database->insert("I_SG_SITIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA, 
				"ID_CLIENTE_DOMICILIO" => $ID_CLIENTE_DOMICILIO,  
				"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS, 
				"NUMERO_TOTAL_EMPLEADOS" => $NUMERO_TOTAL_EMPLEADOS, 
				"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION, 
				"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS, 
				"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO, 
				"ID_ACTIVIDAD" => $ID_ACTIVIDAD, 
				"MATRIZ_PRINCIPAL" =>$MATRIZ_PRINCIPAL, 
				"FECHA_CREACION" => $FECHA_CREACION,
				"HORA_CREACION" => $HORA_CREACION,
				"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION,
			]); 
			valida_error_medoo_and_die();
		}
	} else {
		$respuesta["resultado"] = "error\n"; 
		$respuesta["mensaje"] = "Es necesario agregar sitios a la cotización"; 
		print_r(json_encode($respuesta));
		die();
	}
	//Información adicional
	//CSGC
	if($ID_SERVICIO == 1){
		//total_empleados	
		$id_sitio = $database->insert("I_TIPOS_SERVICIOS", [ 
			"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
			"ID_META_SCE" => 1,  
			"VALOR" => $cant_empleados_total
		]); 
		valida_error_medoo_and_die();
		//total_empleados_certificación
		$id_sitio = $database->insert("I_TIPOS_SERVICIOS", [ 
			"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
			"ID_META_SCE" => 3,  
			"VALOR" => $cant_empleados_cert_total
		]); 
		valida_error_medoo_and_die();
	}
	//Sectores
	//Auditorías
	$respuesta["resultado"]="ok"; 
}
print_r(json_encode($respuesta)); 
?> 
