<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
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
			$mailerror->send("COTIZACION_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 

	$id = $_REQUEST["id"]; 
	//AQUI BUSCO LOS DATOS GENERALES DE LA COTIZACION PQ NECESITO SABES SI ES PARA UN PROSPECTO O PARA UN CLIENTE
	$query = "SELECT * 
				FROM TABLA_ENTIDADES,COTIZACIONES 
				WHERE 
				ID_PROSPECTO = ID_VISTA 
				AND BANDERA_VISTA = BANDERA 
				AND ID =".$database->quote($id);
	
	$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die();
	
	
	$campos = [
		"COTIZACION_SITIOS.ID",
		"COTIZACION_SITIOS.ID_COTIZACION",
		"COTIZACION_SITIOS.ID_DOMICILIO_SITIO",
		"COTIZACION_SITIOS.TOTAL_EMPLEADOS",
		"COTIZACION_SITIOS.NUMERO_EMPLEADOS_CERTIFICACION",
		"COTIZACION_SITIOS.CANTIDAD_TURNOS",
		"COTIZACION_SITIOS.CANTIDAD_DE_PROCESOS",
		"COTIZACION_SITIOS.TEMPORAL_O_FIJO",
		"COTIZACION_SITIOS.MATRIZ_PRINCIPAL",
		"COTIZACION_SITIOS.ID_ACTIVIDAD",
		"COTIZACION_SITIOS.FACTOR_REDUCCION",
		"COTIZACION_SITIOS.FACTOR_AMPLIACION",
		"COTIZACION_SITIOS.JUSTIFICACION",
		"SG_ACTIVIDAD.ACTIVIDAD",
	];

	if($cotizacion[0]["BANDERA"] == 0){
		$id_cliente = $database->get("PROSPECTO",["ID_CLIENTE"], ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
		if($id_cliente == 0){
			$total_domicilios = $database->count("PROSPECTO_DOMICILIO", ["ID_PROSPECTO"=>$cotizacion[0]["ID_PROSPECTO"]]); 
			array_push($campos, "PROSPECTO_DOMICILIO.NOMBRE");
			$tabla_entidad = "PROSPECTO_DOMICILIO";
			$id_prospecto_asociado = $cotizacion[0]["ID_PROSPECTO"];
		}
		else{
			$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$id_cliente]);
			array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
			$tabla_entidad = "CLIENTES_DOMICILIOS";
			$id_prospecto_asociado = $database->get("PROSPECTO", "ID", ["ID_CLIENTE"=>$id_cliente]);
		}
		
	}
	else if($cotizacion[0]["BANDERA"] != 0){
		$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
		array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
		$tabla_entidad = "CLIENTES_DOMICILIOS";
		$id_prospecto_asociado = $database->get("PROSPECTO", "ID", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
	}


	$datos = $database->select("COTIZACION_SITIOS", ["[>]".$tabla_entidad => ["ID_DOMICILIO_SITIO" => "ID"], 
		"[>]SG_ACTIVIDAD" => ["ID_ACTIVIDAD" => "ID"] ], $campos, ["ID_COTIZACION"=>$id]);
	valida_error_medoo_and_die();

	
	//$datos = $database->select("COTIZACION_SITIOS", "*", ["ID_COTIZACION"=>$id]); 
	//valida_error_medoo_and_die(); 
	$respuesta['resultado']='ok';
	$respuesta['datos']= $datos;
	print_r(json_encode($respuesta)); 
?> 
