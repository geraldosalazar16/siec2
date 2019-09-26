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
    $sce= $database->get("SERVICIO_CLIENTE_ETAPA",
		[
			"[><]ETAPAS_PROCESO" => ["ID_ETAPA_PROCESO" => "ID_ETAPA"]
		],
		[
			"SERVICIO_CLIENTE_ETAPA.REFERENCIA",
			"ETAPAS_PROCESO.ETAPA"
		],
		[
			"SERVICIO_CLIENTE_ETAPA.ID" => $solicitud["ID_SERVICIO_CLIENTE_ETAPA"]
		]);
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
		["AND"=>["SERVICIO_CLIENTE_ETAPA.ID"=>$solicitud["ID_SERVICIO_CLIENTE_ETAPA"],"TIPOS_CONTACTO.ID"=>3]]);
     valida_error_medoo_and_die();
	$solicitud["CONTACTO_FACTURACION"] = $contacto_facturacion;
	$documento= $database->get("BASE_DOCUMENTOS",
		[
			"[><]CATALOGO_DOCUMENTOS" => ["ID_CATALOGO_DOCUMENTOS" => "ID"],
			"[><]CATALOGO_SECCIONES" => ["CATALOGO_DOCUMENTOS.ID_SECCION" => "ID"],
		],
		[
			"BASE_DOCUMENTOS.ID",
			"BASE_DOCUMENTOS.UBICACION_DOCUMENTOS",
			"BASE_DOCUMENTOS.CICLO",
			"BASE_DOCUMENTOS.EXTENSION_DOCUMENTO",
			"CATALOGO_DOCUMENTOS.NOMBRE",
			"CATALOGO_SECCIONES.NOMBRE_SECCION"

		],
		["AND"=>["BASE_DOCUMENTOS.ID_SERVICIO"=>$solicitud["ID_SERVICIO_CLIENTE_ETAPA"],"BASE_DOCUMENTOS.CICLO"=>1,"BASE_DOCUMENTOS.ID_CATALOGO_DOCUMENTOS "=>5,"CATALOGO_DOCUMENTOS.ID_ETAPA"=>3]]);
	valida_error_medoo_and_die();
//	print_r($database);
//	exit();
	if($documento)
	{
		$cadena = explode("-",$sce["REFERENCIA"]);
		$ruta = "arch_expediente/".$cadena[1].$cadena[2]."/1/Asignación /".trim($documento["NOMBRE_SECCION"])."/".$documento["ID"].".".$documento["EXTENSION_DOCUMENTO"];
		$documento["EXIST"] = false;
		$documento["RUTA"] = "";//Asegurar q siempre exista RUTA
		$ruta_full = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR."arch_expediente".DIRECTORY_SEPARATOR.$cadena[1].$cadena[2].DIRECTORY_SEPARATOR."1".DIRECTORY_SEPARATOR."Asignación ".DIRECTORY_SEPARATOR.trim($documento["NOMBRE_SECCION"]).DIRECTORY_SEPARATOR.$documento["ID"].".".$documento["EXTENSION_DOCUMENTO"];
		if(file_exists($ruta_full))
		{
			$documento["EXIST"] = true;
			$documento["RUTA"] = $ruta;
		}		
	}
	else{
		$documento["EXIST"] = false;
		$documento["RUTA"] = "";//Asegurar q siempre exista RUTA
	}
    valida_error_medoo_and_die();
	$solicitud["DOCUMENTO"] = $documento;


print_r(json_encode($solicitud));
?> 
