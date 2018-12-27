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

$ES_RENOVACION	= $objeto->ES_RENOVACION; 
valida_parametro_and_die($ES_RENOVACION, "Es necesario indicar si es renovación o no");
/*
$NORMAS= $objeto->NORMAS;
if(count($NORMAS) == 0){
	$respuesta['resultado']="error";
	$respuesta['mensaje']="Es necesario seleccionar una norma";
	print_r(json_encode($respuesta));
	die();
}

$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO; 
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");
*/
//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

//Buscar el id de sce
$id_servicio_cliente_etapa = $database->get("SERVICIO_CLIENTE_ETAPA","ID",["REFERENCIA" => $REFERENCIA]);

//Buscar los eventos de la cotización
if($id_servicio_cliente_etapa	!=	0){	
	//Si existe un id de servicio contratado pues me traigo todos los datos 
	$servicio_cliente_etapa = $database->select("SERVICIO_CLIENTE_ETAPA","*",["ID" => $id_servicio_cliente_etapa]);
	$ID_SERVICIO = $objeto->ID_SERVICIO; 
	$ID_TIPO_SERVICIO	= $objeto->ID_TIPO_SERVICIO; 
	if($ID_SERVICIO == 1){
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

		//$ciclo = 1;
		$ciclo = substr($REFERENCIA,1,1);
		/* Cuando no es renovación solamente agrego los eventos al
		ciclo actual del servicio */
		if($ES_RENOVACION == "S"){
			/* Si es renovación debo agregar los eventos al siguiente ciclo*/
			//Determinar el siguiente ciclo
			//$ciclo = substr($REFERENCIA,1,1);
			$ciclo = (int)$ciclo+1;
		}

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
						"CICLO" => $ciclo],[
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
				"CICLO" => $ciclo,
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
					"CICLO" => $ciclo,
					"ID_CLIENTE_DOMICILIO" => $id_cliente_domicilio,
					"FECHA_CREACION" => $FECHA_CREACION,
					"HORA_CREACION" => $HORA_CREACION,
					"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
				]); 
				valida_error_medoo_and_die();
			}
		}
    
	}
	if($ID_SERVICIO == 2){
		if($ID_TIPO_SERVICIO == 16){
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

			//$ciclo = 1;
			$ciclo = substr($REFERENCIA,1,1);
			/* Cuando no es renovación solamente agrego los eventos al
			ciclo actual del servicio */
			if($ES_RENOVACION == "S"){
				/* Si es renovación debo agregar los eventos al siguiente ciclo*/
				//Determinar el siguiente ciclo
				//$ciclo = substr($REFERENCIA,1,1);
				$ciclo = (int)$ciclo+1;
			}
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
						"CICLO" => $ciclo,
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
							"CICLO" => $ciclo,
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

			//$ciclo = 1;
			$ciclo = substr($REFERENCIA,1,1);
			/* Cuando no es renovación solamente agrego los eventos al
			ciclo actual del servicio */
			if($ES_RENOVACION == "S"){
				/* Si es renovación debo agregar los eventos al siguiente ciclo*/
				//Determinar el siguiente ciclo
				//$ciclo = substr($REFERENCIA,1,1);
				$ciclo = (int)$ciclo+1;
			}
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
						"CICLO" => $ciclo,
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
							"CICLO" => $ciclo,
							"ID_CLIENTE_DOMICILIO" => $id_cliente_domicilio,
							"FECHA_CREACION" => $FECHA_CREACION,
							"HORA_CREACION" => $HORA_CREACION,
							"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
						]); 
						valida_error_medoo_and_die();
					}
			}
		}
	}
	$respuesta["resultado"]="ok"; 
}
print_r(json_encode($respuesta)); 
?> 
