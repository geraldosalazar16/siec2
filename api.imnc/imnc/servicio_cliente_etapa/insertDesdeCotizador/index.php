<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  
include  '../../common/jwt.php'; 

use \Firebase\JWT\JWT;

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
//ESTA VALIDACION NO ES NECESARIA EN CIFA
if(count($NORMAS) == 0 && $ID_SERVICIO != 3){
	$respuesta['resultado']="error";
	$respuesta['mensaje']="Es necesario seleccionar una norma";
	print_r(json_encode($respuesta));
	die();
}

$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO; 
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");

//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$CAMBIO= $objeto->CAMBIO;

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

//parametros solo para cifa
$MODALIDAD = $objeto->MODALIDAD; 
$ID_CURSO = $objeto->ID_CURSO; 
$ID_CURSO_PROGRAMADO = $objeto->ID_CURSO_PROGRAMADO; 
$tipo = ""; //Esto se usa para generar la referencia
if($ID_SERVICIO == 3){
	valida_parametro_and_die($MODALIDAD,"Falta MODALIDAD");
	if($MODALIDAD == "programado"){
		valida_parametro_and_die($ID_CURSO_PROGRAMADO,"Falta ID_CURSO_PROGRAMADO");
		$tipo = "P";
	} else {
		valida_parametro_and_die($ID_CURSO,"Falta ID_CURSO");
		$tipo = "D";
	}	
}

$REFERENCIA = $objeto->REFERENCIA;
if($ID_SERVICIO != 3){
	valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");
} else {
	//Generar la referencia para CIFA
	$REFERENCIA = file_get_contents($global_apiserver . '/cursos/getReferencia/?id=' .$ID_SERVICIO . '&tipo=' . $tipo);
}

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
//Agregar las normas, para todos menos para CIFA
if($ID_SERVICIO != 3){
	for ($i=0; $i < count($NORMAS); $i++) { 
		$id_norma = $NORMAS[$i]->ID_NORMA;
		$id_sce_normas = $database->insert("SCE_NORMAS", [ 
			"ID_SCE" => $id_servicio_cliente_etapa,
			"ID_NORMA" => $id_norma
		]); 
		valida_error_medoo_and_die();
	}
}

//SI LA OPERACIÓN FUE EXITOSA
if($id_servicio_cliente_etapa	!=	0){
	//Insertar en SERVICIO_CLIENTE_ETAPA_HISTÓRICO
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
	//Para Servicios de Certificacion de Sistemas de Gestion
	if($ID_SERVICIO == 1){
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
				$ID_CLIENTE_DOMICILIO = $domicilios[$i]['ID_DOMICILIO_SITIO'];  
				$CANTIDAD_TURNOS = $domicilios[$i]['CANTIDAD_TURNOS']; 
				$NUMERO_TOTAL_EMPLEADOS = $domicilios[$i]['TOTAL_EMPLEADOS']; 
				$NUMERO_EMPLEADOS_CERTIFICACION = $domicilios[$i]['NUMERO_EMPLEADOS_CERTIFICACION'];  
				$CANTIDAD_DE_PROCESOS = $domicilios[$i]['CANTIDAD_DE_PROCESOS'];  
				$TEMPORAL_O_FIJO = $domicilios[$i]['TEMPORAL_O_FIJO'];  
				$ID_ACTIVIDAD = $domicilios[$i]['ID_ACTIVIDAD']; 
				$MATRIZ_PRINCIPAL = $domicilios[$i]['MATRIZ_PRINCIPAL']; 

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
					if($tipo_auditoria == 2){
						$dia_int_norm = 1;
					}
					else{
						$dia_int_norm = $normasint->DIAS;
					}
					$id1 = $database->update("SCE_NORMAS", [ 
						"DIAS_AUDITOR" => $dia_int_norm,
						"ID_TIPO_AUDITORIA" => $tipo_auditoria,
						"CICLO" => 1],[
					"AND"=>["ID_SCE" => $id_servicio_cliente_etapa,"ID_NORMA" => $normasint->ID_NORMA,"ID_TIPO_AUDITORIA" => 0,"CICLO" => 0]
					]);
					valida_error_medoo_and_die();
				}
				if($tipo_auditoria == 2){
					$dias_auditoria = count($cotizacion[0]->NORMAS);
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
	}
	//Para Servicios de Evaluación de la Conformidad
	if($ID_SERVICIO == 2){
		if($ID_TIPO_SERVICIO == 16){
			//Sitios
			$tramites = $database->select("COTIZACIONES_TRAMITES_CIL",
				"*",["ID_COTIZACION" => $ID_COTIZACION]);
			$ids_tramites = [];
			$cont = count($tramites);
			for($i=0;$i<$cont;$i++){
				$ids_tramites[$i] = $tramites[$i]['ID'];
			}
			$domicilios = $database->select("COTIZACION_SITIOS_CIL","*",["ID_COTIZACION" => $ids_tramites]);
			
			if(count($domicilios) > 0){
				for($i=0;$i<count($domicilios);$i++){
					$ID_SERVICIO_CLIENTE_ETAPA = $id_servicio_cliente_etapa; 
					$ID_CLIENTE_DOMICILIO = $domicilios[$i]['ID_DOMICILIO_SITIO'];  
					$MATRIZ_PRINCIPAL = $domicilios[$i]['MATRIZ_PRINCIPAL']; 
					if(strtolower($MATRIZ_PRINCIPAL)=='si'){
						$VALOR = 3;
					}else{
						$VALOR = 4;
					}
					$id1 = $database->insert("I_EC_SITIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_CLIENTE_DOMICILIO"	=> 	$ID_CLIENTE_DOMICILIO,
						"ID_META_SITIOS"	=>	9,
						"VALOR" => $VALOR 
				
					]);
					valida_error_medoo_and_die();}
			} else {
				$respuesta["resultado"] = "error\n"; 
				$respuesta["mensaje"] = "Es necesario agregar sitios a la cotización"; 
				print_r(json_encode($respuesta));
				die();
			}
		
			//Información adicional
			// Para este tipo de servicio no se puede enviar informacion adicional pq no se captura en el cotizador.
			//
			
			//Sectores
			//Este tipo de servicio pertenece al Evaluacion de la conformidad y hasta ahora no se le capturan sectores.
			//
			
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
				$tipo_auditoria = $tramite->ID_TIPO_AUDITORIA;
				
				
				//Buscar los sitios
				$sitios = $database->select("COTIZACION_SITIOS_CIL", "*", ["ID_COTIZACION"=>$tramite->ID]);
				valida_error_medoo_and_die();
		
				//Insertar en I_EC_AUDITORIAS
				$id_ec_auditoria = $database->insert("I_EC_AUDITORIAS", [ 
					"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
					"TIPO_AUDITORIA" => $tipo_auditoria,  
					"CICLO" => 1,
					"DURACION_DIAS" => $dias_auditoria,
					"STATUS_AUDITORIA" => "1",
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
	
		}
		if($ID_TIPO_SERVICIO == 17){
			//Sitios
			$tramites = $database->select("COTIZACIONES_TRAMITES_TUR",
				"*",["ID_COTIZACION" => $ID_COTIZACION]);
			$ids_tramites = [];
			$cont = count($tramites);
			for($i=0;$i<$cont;$i++){
				$ids_tramites[$i] = $tramites[$i]['ID'];
			}
			$domicilios = $database->select("COTIZACION_SITIOS_TUR","*",["ID_COTIZACION" => $ids_tramites]);
			
			if(count($domicilios) > 0){
				for($i=0;$i<count($domicilios);$i++){
					$ID_SERVICIO_CLIENTE_ETAPA = $id_servicio_cliente_etapa; 
					$ID_CLIENTE_DOMICILIO = $domicilios[$i]['ID_DOMICILIO_SITIO'];  
					//$MATRIZ_PRINCIPAL = $domicilios[$i]['MATRIZ_PRINCIPAL']; 
					//if(strtolower($MATRIZ_PRINCIPAL)=='si'){
					//	$VALOR = 3;
					//}else{
					//	$VALOR = 4;
					//}
					$id1 = $database->insert("I_EC_SITIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_CLIENTE_DOMICILIO"	=> 	$ID_CLIENTE_DOMICILIO,
						"ID_META_SITIOS"	=>	17,
						"VALOR" => " " 
				
					]);
					valida_error_medoo_and_die();}
			} else {
				$respuesta["resultado"] = "error\n"; 
				$respuesta["mensaje"] = "Es necesario agregar sitios a la cotización"; 
				print_r(json_encode($respuesta));
				die();
			}
		
			//Información adicional
			if($NORMAS[0]->ID_NORMA == 'NMX-AA-120-SCFI-2006' || $NORMAS[0]->ID_NORMA == 'NMX-AA-120-SCFI-2016'){
				$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
					"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
					"ID_META_SCE" => 73,  
					"VALOR" => $domicilios[0]['LONGITUD_PLAYA']
				]); 
				valida_error_medoo_and_die();
			}
			
			//Sectores
			//Este tipo de servicio pertenece al Evaluacion de la conformidad y hasta ahora no se le capturan sectores.
			//
			
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
				$tipo_auditoria = $tramite->ID_TIPO_AUDITORIA;
				
				
				//Buscar los sitios
				$sitios = $database->select("COTIZACION_SITIOS_TUR", "*", ["ID_COTIZACION"=>$tramite->ID]);
				valida_error_medoo_and_die();
		
				//Insertar en I_EC_AUDITORIAS
				$id_ec_auditoria = $database->insert("I_EC_AUDITORIAS", [ 
					"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
					"TIPO_AUDITORIA" => $tipo_auditoria,  
					"CICLO" => 1,
					"DURACION_DIAS" => $dias_auditoria,
					"STATUS_AUDITORIA" => "1",
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
	
		}
		if($ID_TIPO_SERVICIO == 19){ //Certificacion de Personas
			//Sitios
			$tramites = $database->select("COTIZACIONES_TRAMITES_CPER",
				"*",["ID_COTIZACION" => $ID_COTIZACION]);
			$ids_tramites = [];
			$cont = count($tramites);
			for($i=0;$i<$cont;$i++){
				$ids_tramites[$i] = $tramites[$i]['ID'];
			}
			$domicilios = $database->select("COTIZACION_SITIOS_CPER","*",["ID_COTIZACION" => $ids_tramites]);
			
			if(count($domicilios) > 0){//Aqui de momento no insertaremos sitios pq en PROGRAMACION este tipo de servicio no tiene cargado metadatos y por tanto no se puede insertar sitios.
				
				/*for($i=0;$i<count($domicilios);$i++){
					$ID_SERVICIO_CLIENTE_ETAPA = $id_servicio_cliente_etapa; 
					$ID_CLIENTE_DOMICILIO = $domicilios[$i]['ID_DOMICILIO_SITIO'];  
					
					$id1 = $database->insert("I_EC_SITIOS", [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_CLIENTE_DOMICILIO"	=> 	$ID_CLIENTE_DOMICILIO,
						"ID_META_SITIOS"	=>	17,
						"VALOR" => " " 
				
					]);
					valida_error_medoo_and_die();
				}*/
			} else {
				$respuesta["resultado"] = "error\n"; 
				$respuesta["mensaje"] = "Es necesario agregar sitios a la cotización"; 
				print_r(json_encode($respuesta));
				die();
			}
		
			//Información adicional
			// EN CUANTO A LA INFORMACION ADICIONAL PUES AUN NO SE QUE HABRIA QUE PASARLE, ASI QUE NO LE PASO NADA
			/*if($NORMAS[0]->ID_NORMA == 'NMX-AA-120-SCFI-2006' || $NORMAS[0]->ID_NORMA == 'NMX-AA-120-SCFI-2016'){
				$id_ts = $database->insert("I_TIPOS_SERVICIOS", [ 
					"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
					"ID_META_SCE" => 73,  
					"VALOR" => $domicilios[0]['LONGITUD_PLAYA']
				]); 
				valida_error_medoo_and_die();
			}
			*/
			//Sectores
			//Este tipo de servicio pertenece al Evaluacion de la conformidad y hasta ahora no se le capturan sectores.
			//
			
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
				$dias_auditoria = 0;//$tramite->DIAS_AUDITORIA;
				$tipo_auditoria = $tramite->ID_TIPO_AUDITORIA;
				
				
				//Buscar los sitios
				$sitios = $database->select("COTIZACION_SITIOS_CPER", "*", ["ID_COTIZACION"=>$tramite->ID]);
				valida_error_medoo_and_die();
		
				//Insertar en I_EC_AUDITORIAS
				$id_ec_auditoria = $database->insert("I_EC_AUDITORIAS", [ 
					"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
					"TIPO_AUDITORIA" => $tipo_auditoria,  
					"CICLO" => 1,
					"DURACION_DIAS" => $dias_auditoria,
					"STATUS_AUDITORIA" => "1",
					"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
				]); 
				valida_error_medoo_and_die();

				//Insertar los sitios en I_SG_AUDITORIA_SITIOS
				// COMO NO SE PUDIERON INSERTAR SITIOS PUES DE MOMENTO SE VA EN BLANCO ESTA INFORMACION
		/*		foreach ($sitios as $key => $sitio) {
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
				} */
			}
	
		}
	}
	//Para servicios de CIFA
	if($ID_SERVICIO == 3){
		if($MODALIDAD == "programado"){

		} else {
			//INSERTAR EN SCE_CURSOS
			$id_sce_cursos = $database->insert("SCE_CURSOS", [
				"ID_SCE" => $id_servicio_cliente_etapa,
				"ID_CURSO" => $ID_CURSO
			]);
			//GENERAR TOKEN PARA EL CLIENTE
			$respuesta = array();

			//payload
			$data = [
				'ID_CLIENTE' => $ID_FINAL,
				'MODALIDAD' => $MODALIDAD,
				'ID_CURSO' => $ID_CURSO,
				'ID_PROGRAMACION' => $id_servicio_cliente_etapa
			];
			/*
			iss = issuer, servidor que genera el token
			data = payload del JWT
			*/
			$token = array(
			"iss" => $global_apiserver,
			"data" => $data
			);

			//Codifica la información usando el $key definido en jwt.php
			$jwt = JWT::encode($token, $key);

			//GUARDAR EL URL EN COTIZACIÓN DETALLE
			$url = $insertar_participantes . "?token=" . $jwt;
			$cot_detalle = $database->insert("COTIZACION_DETALLES", [
				"ID_COTIZACION" => $ID_COTIZACION,
				"DETALLE" => "URL_PARTICIPANTES",
				"VALOR" => $url
			]);
			valida_error_medoo_and_die();

			//TODO: Enviar notificación al cliente de que su servicio está listo para cargar participantes
		}		
	}
	$respuesta["resultado"]="ok"; 
}
print_r(json_encode($respuesta)); 
?> 
