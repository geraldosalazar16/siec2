<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();

$strQuery = "SELECT DISTINCT SG_AUDITORIA_FECHAS.ID, SG_AUDITORIA_FECHAS.FECHA FECHA_AUDITORIA, SG_AUDITORIAS.ID ID_AUDITORIA, 
					SG_AUDITORIAS.DURACION_DIAS, SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO, 
					SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA, SG_AUDITORIAS.ID_SG_TIPO_SERVICIO, 
					SERVICIO_CLIENTE_ETAPA.REFERENCIA, CLIENTES.ID
			FROM SG_AUDITORIAS, SG_TIPOS_SERVICIO, SG_AUDITORIA_FECHAS, SERVICIO_CLIENTE_ETAPA, CLIENTES, SECTORES
			WHERE SG_AUDITORIAS.ID_SG_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID AND
      				SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID AND
      				SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID AND
      				SG_AUDITORIAS.ID = SG_AUDITORIA_FECHAS.ID_SG_AUDITORIA AND
      				SECTORES.ID_TIPO_SERVICIO = SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO 
                    ORDER BY SG_AUDITORIA_FECHAS.FECHA ";
//print_r($strQuery);

$arreglo_fechas = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();

//print_r($database->last_query());

$respuesta["FECHAS"] = $arreglo_fechas;

print_r(json_encode($respuesta));


?>