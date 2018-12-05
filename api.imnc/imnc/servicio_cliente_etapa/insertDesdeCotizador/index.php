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
$respuesta["warnings"] = [];

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

$NORMAS= $objeto->NORMAS;
if(count($NORMAS) == 0){
	$respuesta['resultado']="error";
	$respuesta['mensaje']="Es necesario seleccionar una norma";
	print_r(json_encode($respuesta));
	die();
}

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
	"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,  
	"REFERENCIA" => $REFERENCIA,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"CAMBIO"=>$CAMBIO,
]); 
valida_error_medoo_and_die(); 
//Agregar las normas
for ($i=0; $i < count($NORMAS); $i++) { 
	$id_norma = $NORMAS[$i]->ID_NORMA;
	$id_sce_normas = $database->insert("SCE_NORMAS", [ 
		"ID_SCE" => $id_servicio_cliente_etapa,
		"ID_NORMA" => $id_norma
	]); 
	valida_error_medoo_and_die();
}

//Insertar en SERVICIO_CLIENTE_ETAPA_HISTÓRICO
if($id_servicio_cliente_etapa	!=	0){
	$id1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
			"ID_SERVICIO_CONTRATADO" => $id_servicio_cliente_etapa, 
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

	$tramites = $database->select("COTIZACIONES_TRAMITES",
	"*",["ID_COTIZACION" => $ID_COTIZACION]);
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
	if($ID_SERVICIO == 1){//CSGC
		if($ID_TIPO_SERVICIO ==1){
			//total_empleados	
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 1,  
				"VALOR" => $cant_empleados_total
			]); 
			valida_error_medoo_and_die();
			//total_empleados_certificación
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 3,  
				"VALOR" => $cant_empleados_cert_total
			]); 
			valida_error_medoo_and_die();
		}
		if($ID_TIPO_SERVICIO ==2){
			//total_empleados	
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 85,  
				"VALOR" => $cant_empleados_total
			]); 
			valida_error_medoo_and_die();
			//total_empleados_certificación
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 86,  
				"VALOR" => $cant_empleados_cert_total
			]); 
			valida_error_medoo_and_die();
		}
		if($ID_TIPO_SERVICIO == 12){
			//total_empleados	
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 88,  
				"VALOR" => $cant_empleados_total
			]); 
			valida_error_medoo_and_die();
			//total_empleados_certificación
			$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_META_SCE" => 89,  
				"VALOR" => $cant_empleados_cert_total
			]); 
			valida_error_medoo_and_die();
		}
		if($ID_TIPO_SERVICIO == 20){
			//Busco los tipos de servicio que tiene cargada la auditoria integral
			$integral_ts = $database->query("SELECT `ID_TIPO_SERVICIO` FROM `NORMAS_TIPOSERVICIO` WHERE `ID_NORMA` IN (SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`=  ".$id_servicio_cliente_etapa.") AND `ID_TIPO_SERVICIO` !=20")->fetchAll(PDO::FETCH_ASSOC);
			valida_error_medoo_and_die();
			for($i=0;$i<count($integral_ts);$i++){
				if($integral_ts[$i]["ID_TIPO_SERVICIO"] ==1){
				//total_empleados	
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 1,  
						"VALOR" => $cant_empleados_total
						]); 
					valida_error_medoo_and_die();
				//total_empleados_certificación
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 3,  
						"VALOR" => $cant_empleados_cert_total
						]); 
					valida_error_medoo_and_die();
				}
				if($integral_ts[$i]["ID_TIPO_SERVICIO"] ==2){
					//total_empleados	
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 85,  
						"VALOR" => $cant_empleados_total
						]); 
					valida_error_medoo_and_die();
					//total_empleados_certificación
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 86,  
						"VALOR" => $cant_empleados_cert_total
						]); 
					valida_error_medoo_and_die();
				}
				if($integral_ts[$i]["ID_TIPO_SERVICIO"] ==12){
					//total_empleados	
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 88,  
						"VALOR" => $cant_empleados_total
					]); 
					valida_error_medoo_and_die();
					//total_empleados_certificación
					$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_META_SCE" => 89,  
						"VALOR" => $cant_empleados_cert_total
					]); 
					valida_error_medoo_and_die();
				}
				
			}
			
		}
		
	}
	if($ID_SERVICIO == 2){ //EC AUN SIN INCLUIR EN EL COTIZADOR

	}
	if($ID_SERVICIO == 3){ //CIFA AUN SIN INCLUIR EN EL COTIZADOR

	}
	//Sectores
	//Buscar en prospecto_producto el id del producto
	//filtrando con id prospecto id servicio y id tipo servicio
	//porque no tengo el id producto, de todas formas esta combinación nose repite
	$id_producto = $database->get("PROSPECTO_PRODUCTO",
	"ID",["AND" => [
		"ID_PROSPECTO" => $ID_CLIENTE,
		"ID_SERVICIO" => $ID_SERVICIO,
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO
		]
	]);
	valida_error_medoo_and_die();
	//Con el id del producto busco en prospecto_sectores
	$sectores = $database->select("PROSPECTO_SECTORES",
	"*",["ID_PRODUCTO" => $id_producto]);
	valida_error_medoo_and_die();
	if(count($sectores) == 0){
		$adv = "No existen sectores cargados al prospecto, se deben cargar manualmente en Programación";
		array_push($respuesta["warnings"],$adv);
	}
	foreach ($sectores as $sector) {
		$id_sce_sectores = $database->insert("I_SG_SECTORES", [ 
			"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
			"ID_SECTOR" => $sector["ID_SECTOR"],  
			"FECHA_CREACION" => $FECHA_CREACION,
			"HORA_CREACION" => $HORA_CREACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]); 
		valida_error_medoo_and_die();
	}
	//Auditorías
	//Para cargar una auditoría necesito
	/*
	TIPO_AUDITORIA: 
	CICLO: Si es un prospecto es ciclo 1 
	DURACION_DIAS: Se obtiene de cotizaciones/getById?id=x
	STATUS_AUDITORIA: Pendiente
	NO_USA_METODO: No
	SITIOS_AUDITAR: Se obtienen de cotizacion_sitios
	ID_SERVICIO_CLIENTE_ETAPA
	*/
	$ruta = $global_apiserver.'/cotizaciones/getById?id='.$ID_COTIZACION;
	$cotizacion = file_get_contents($ruta);
	$cotizacion = json_decode($cotizacion);

	//Recorro todos los trámites e inserto sus auditorías correspondientes
	foreach ($cotizacion[0]->COTIZACION_TRAMITES as $tramite) {
		//para cada trámite hay que agregar una auditoría en 
		$dias_auditoria = $tramite->DIAS_AUDITORIA;
		$tipo_auditoria = $tramite->ID_ETAPA_PROCESO;
		// Si el tipo servicio es Integral busco los dias para cada norma
		if($ID_TIPO_SERVICIO == 20){
			foreach($cotizacion[0]->NORMAS as $normasint){
				$id1 = $database->update("SCE_NORMAS", [ 
					"DIAS_AUDITOR" => $normasint->DIAS,
					"ID_TIPO_AUDITORIA" => $tipo_auditoria,
					"CICLO" => 1],[
				"AND"=>["ID_SCE" => $id_servicio_cliente_etapa,"ID_NORMA" => $normasint->ID_NORMA,"ID_TIPO_AUDITORIA" => 0,"CICLO" => 0]
				]);
				valida_error_medoo_and_die();
			}
		}
		//Buscar los sitios
		$sitios = $database->select("COTIZACION_SITIOS", "*", ["ID_COTIZACION"=>$tramite->ID]);
		valida_error_medoo_and_die();
		
		//Insertar en I_SG_AAUDITORIAS
		$id_sg_auditoria = $database->insert("I_SG_AUDITORIAS", [ 
			"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
			"TIPO_AUDITORIA" => $tipo_auditoria,  
			"CICLO" => 1,
			"DURACION_DIAS" => $dias_auditoria,
			"STATUS_AUDITORIA" => "1",
			"NO_USA_METODO" => 0,
			"SITIOS_AUDITAR" => count($sitios),
			"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]); 
		valida_error_medoo_and_die();

		//Insertar los sitios en I_SG_AUDITORIA_SITIOS
		foreach ($sitios as $key => $sitio) {
			$id_cliente_domicilio = $sitio["ID_DOMICILIO_SITIO"];
			$id_sg_auditoria_sitios = $database->insert("I_SG_AUDITORIA_SITIOS", [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"TIPO_AUDITORIA" => $tipo_auditoria,  
				"CICLO" => 1,
				"ID_CLIENTE_DOMICILIO" => $id_cliente_domicilio,
				"FECHA_CREACION" => $FECHA_CREACION,
				"HORA_CREACION" => $HORA_CREACION,
				"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
			]); 
			valida_error_medoo_and_die();
		}
	}
	
	$respuesta["resultado"]="ok"; 
}
print_r(json_encode($respuesta)); 
?> 
