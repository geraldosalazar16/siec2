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
$REFERENCIA = $objeto->REFERENCIA;
$SG_INTEGRAL = $objeto->SG_INTEGRAL == "si"? "S" : "N";
$COMPLEJIDAD = $objeto->COMPLEJIDAD;
$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$tramite = $objeto->OBJ_TRAMITE;
$ID_TRAMITE = $tramite->ID;
$ID_ETAPA_PROCESO = $tramite->ID_ETAPA_PROCESO;
$CAMBIO = $tramite->CAMBIO; 
$ID_SERVICIO_CLIENTE = $tramite->ID_SERVICIO_CLIENTE;


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

$ID_CLIENTE = $objeto->ID_CLIENTE;
valida_parametro_and_die($ID_CLIENTE, "Se necesita un cliente registrado");


$id_new_tipo_servicio = $database->update("SG_TIPOS_SERVICIO", [ 
	"TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS,
	"TOTAL_EMPLEADOS_PARA_CERTIFICACION" => $TOTAL_EMPLEADOS_PARA_CERTIFICACION,
	"TURNOS" => $TURNOS,
	"COMPLEJIDAD" => $COMPLEJIDAD,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	"HORA_MODIFICACION" => $HORA_MODIFICACION,
	"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
],["AND" => ["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE, "ID_TIPO_SERVICIO"=>$ID_TIPO_SERVICIO]]);
valida_error_medoo_and_die();

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
		$ID_SG_SITIO = $sitio["ID_SG_SITIO"];

		if(!is_null($ID_SG_SITIO)){
			$id_new_sitio = $database->update("SG_SITIOS", [ 
				"CANTIDAD_TURNOS" => $CANTIDAD_TURNOS, 
				"NUMERO_TOTAL_EMPLEADOS" => $TOTAL_EMPLEADOS, 
				"NUMERO_EMPLEADOS_CERTIFICACION" => $NUMERO_EMPLEADOS_CERTIFICACION, 
				"CANTIDAD_DE_PROCESOS" => $CANTIDAD_DE_PROCESOS, 
				"TEMPORAL_O_FIJO" => $TEMPORAL_O_FIJO, 
				"ID_ACTIVIDAD" => $ID_ACTIVIDAD, 
				"MATRIZ_PRINCIPAL" => $MATRIZ_PRINCIPAL, 
				"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
				"HORA_MODIFICACION" => $HORA_MODIFICACION,
				"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
			], ["ID"=>$ID_SG_SITIO]); 
			valida_error_medoo_and_die();
		}
		else{
			if($BANDERA == 1){
				$ID_DOMICILIO_SITIO = $sitio["ID_DOMICILIO_SITIO"];
			}
			else{
				$id_dom = $database->select("CLIENTES_DOMICILIOS", "*",["ID_CLIENTE"=>$ID_CLIENTE]);
				$ID_DOMICILIO_SITIO = $id_dom[0]["ID"];
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
				"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
				"HORA_MODIFICACION" => $HORA_MODIFICACION,
				"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
			]); 
			valida_error_medoo_and_die(); 
		
			$id = $database->update("COTIZACION_SITIOS", [ 
				"ID_SG_SITIO" => $id_new_sitio,
				"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
				"HORA_MODIFICACION" => $HORA_MODIFICACION,
				"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
				], ["ID"=>$ID_SITIO]); 
		}
	}

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
