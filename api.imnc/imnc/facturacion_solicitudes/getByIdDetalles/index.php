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
	$id_solicitud = $_REQUEST["id"];
	$solicitud = $database->get("FACTURACION_SOLICITUDES", "*", ["ID"=>$id_solicitud]);
	valida_error_medoo_and_die();
	$contacto_facturacion = $database->get("SERVICIO_CLIENTE_ETAPA",
		[
			"[><]CLIENTES_DOMICILIOS" => ["ID_CLIENTE" => "ID_CLIENTE"],
			"[><]CLIENTES_CONTACTOS" => ["CLIENTES_DOMICILIOS.ID" => "ID_CLIENTE_DOMICILIO"],
			"[><]CLIENTES" => ["CLIENTES_DOMICILIOS.ID_CLIENTE" => "ID"],
			"[><]TIPOS_CONTACTO" => ["CLIENTES_CONTACTOS.ID_TIPO_CONTACTO" => "ID"],
		],
		[
			"CLIENTES_CONTACTOS.ID",
			"CLIENTES_CONTACTOS.ID_CLIENTE_DOMICILIO",
			"CLIENTES_CONTACTOS.ES_PRINCIPAL",
			"CLIENTES_CONTACTOS.NOMBRE_CONTACTO",
			"CLIENTES_CONTACTOS.CARGO",
			"CLIENTES_CONTACTOS.TELEFONO_MOVIL",
			"CLIENTES_CONTACTOS.TELEFONO_FIJO",
			"CLIENTES_CONTACTOS.EMAIL",
			"TIPOS_CONTACTO.TIPO(ID_TIPO_CONTACTO)",
		],
		["AND"=>["SERVICIO_CLIENTE_ETAPA.ID"=>$solicitud["ID_SERVICIO_CLIENTE_ETAPA"],"TIPOS_CONTACTO.ID"=>8]]);
     valida_error_medoo_and_die();
	$solicitud["CONTACTO_FACTURACION"] = $contacto_facturacion;
	$documento= $database->get("BASE_DOCUMENTOS",
		[
			"[><]CATALOGO_DOCUMENTOS" => ["ID_CATALOGO_DOCUMENTOS" => "ID"],
		],
		[
			"BASE_DOCUMENTOS.UBICACION_DOCUMENTOS",
			"CATALOGO_DOCUMENTOS.NOMBRE",
		],
		["AND"=>["BASE_DOCUMENTOS.ID_SERVICIO"=>$solicitud["ID_SERVICIO_CLIENTE_ETAPA"],"BASE_DOCUMENTOS.CICLO"=>$solicitud["CICLO"],"CATALOGO_DOCUMENTOS.NOMBRE"=>"COMPROBANTE DE DOMICILIO"]]);
	valida_error_medoo_and_die();
	if($documento)
	{
		$solicitud["DOCUMENTO"] = $documento;
	}


print_r(json_encode($solicitud));
?> 
