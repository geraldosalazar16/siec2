<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 

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
			die(); 
		} 
	} 

	$historicos = $database->select("FACTURACION_SOLICITUD_HISTORICO",
		[
//			"[><]FACTURACION_SOLICITUDES" => ["ID_SOLICITUD"=>"ID"],
			"[><]USUARIOS" => ["USUARIO"=>"ID"]
		],
		[
			"FACTURACION_SOLICITUD_HISTORICO.ID_SOLICITUD",
			"FACTURACION_SOLICITUD_HISTORICO.CAMBIO",
			"FACTURACION_SOLICITUD_HISTORICO.DESCRIPCION",
			"FACTURACION_SOLICITUD_HISTORICO.FECHA",
			"FACTURACION_SOLICITUD_HISTORICO.HORA",
			"USUARIOS.NOMBRE",
		],
	    [
	    	"ORDER"=>["FACTURACION_SOLICITUD_HISTORICO.FECHA","FACTURACION_SOLICITUD_HISTORICO.HORA"]
		]);
	valida_error_medoo_and_die();
	foreach ($historicos as $i => $h)
	{
		$historicos[$i]["FECHA"] = substr($h["FECHA"],6,8)."/".substr($h["FECHA"],-4,2)."/".substr($h["FECHA"],0,4);
		$historicos[$i]["HORA"] = substr($h["HORA"],0,2).":".substr($h["HORA"],2,2);
	}

print_r(json_encode($historicos));
?> 
