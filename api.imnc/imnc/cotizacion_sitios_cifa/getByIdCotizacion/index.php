<?php 
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
	include './funciones.php';
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
			$mailerror->send("COTIZACION_SITIOS_CIFA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
			die(); 
		} 
	} 
	$id = $_REQUEST["id"]; 
	$obj_cotizacion = [];
	
	$query = "SELECT * 
	FROM TABLA_ENTIDADES,COTIZACIONES 
	WHERE 
	ID_PROSPECTO = ID_VISTA 
	AND BANDERA_VISTA = BANDERA 
	AND ID =".$database->quote($id);
	$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die();
	// AQUI LA PARTE QUE TIENE Q VER CON LOS DETALLES
	$modalidad = "";
	$id_curso = 0;
	$cantidad_participantes = 0;
	$solo_cliente = 0;
	$tiene_servicio = 0;
	$meta = $database->select("COTIZACION_DETALLES","*",["ID_COTIZACION" => $id]);
	foreach($meta as $data){
		if($data["DETALLE"] == "MODALIDAD"){
			$modalidad = $data["VALOR"];
		}
		if($data["DETALLE"] == "ID_CURSO"){
			$id_curso = $data["VALOR"];
		}
		if($data["DETALLE"] == "CANT_PARTICIPANTES"){
			$cantidad_participantes = $data["VALOR"];
		}
		if($data["DETALLE"] == "SOLO_CLIENTE"){
			$solo_cliente = $data["VALOR"];
		}
		if($data["DETALLE"] == "TIENE_SERVICIO"){
			$tiene_servicio = $data["VALOR"];
		}
		
	}
	if($modalidad == 'insitu'){
		$data = $database->get("CURSOS", "*", ["ID_CURSO"=>$id_curso]);
		valida_error_medoo_and_die();
		$obj_cotizacion["VALOR_POR_SITIO"] = $data["PRECIO_INSITU"]*$data["DIAS_INSITU"];
	
	//	AQUI LA PARTE Q TIENE Q VER CON SITIOS
	
	$campos = [
	"COTIZACION_SITIOS_CIFA.ID",
	"COTIZACION_SITIOS_CIFA.ID_COTIZACION",
	"COTIZACION_SITIOS_CIFA.ID_DOMICILIO_SITIO",
	"COTIZACION_SITIOS_CIFA.SELECCIONADO",
	
	];

	if($cotizacion[0]["BANDERA"] == 0){
		$total_domicilios = $database->count("PROSPECTO_DOMICILIO", ["ID_PROSPECTO"=>$cotizacion[0]["ID_PROSPECTO"]]); 
		array_push($campos, "PROSPECTO_DOMICILIO.NOMBRE");
		$tabla_entidad = "PROSPECTO_DOMICILIO";
	}
	else if($cotizacion[0]["BANDERA"] != 0){
		$total_domicilios = $database->count("CLIENTES_DOMICILIOS", ["ID_CLIENTE"=>$cotizacion[0]["ID_PROSPECTO"]]);
		array_push($campos, "CLIENTES_DOMICILIOS.NOMBRE_DOMICILIO(NOMBRE)");
		$tabla_entidad = "CLIENTES_DOMICILIOS";
	}

	$cotizacion_sitios = $database->select("COTIZACION_SITIOS_CIFA", ["[>]".$tabla_entidad => ["ID_DOMICILIO_SITIO" => "ID"]], $campos, ["ID_COTIZACION"=>$id]);
	valida_error_medoo_and_die();
	
	$obj_cotizacion["COUNT_SITIOS"] = count_sitios($id);
	$obj_cotizacion["COTIZACION_SITIOS"] = $cotizacion_sitios;
	
	//----- RESTRICCIONES -------
	$obj_cotizacion["RESTRICCIONES"] = array(); 

	if ($obj_cotizacion["COUNT_SITIOS"]["TOTAL_SITIOS"] <= 0) {
		array_push($obj_cotizacion["RESTRICCIONES"], "Es necesario agregar sitios para obtener una cotizacion");
	}

	if ($obj_cotizacion["COUNT_SITIOS"]["TOTAL_SITIOS"] < $obj_cotizacion["COUNT_SITIOS"]["SITIOS_A_VISITAR"]) {
		array_push($obj_cotizacion["RESTRICCIONES"], "Los sitios seleccionados deben ser por lo menos " . $obj_cotizacion["COUNT_SITIOS"]["SITIOS_A_VISITAR"]);
	}
	//---- FIN: RESTRICCIONES -------
	}
	//Aqui la parte que tiene que ver con TARIFA ADICIONAL
	$campos_t = [
	"COTIZACION_TARIFA_ADICIONAL.ID",
	"COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE",
	"COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL",
	"COTIZACION_TARIFA_ADICIONAL.CANTIDAD",
	"TARIFA_COTIZACION_ADICIONAL.DESCRIPCION",
	"TARIFA_COTIZACION_ADICIONAL.TARIFA"
	];

	$cotizacion_tarifa_adicional = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
	$campos_t, ["AND"=>["ID_TRAMITE"=>0,"ID_COTIZACION"=>$id]]);
	valida_error_medoo_and_die();
	$obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"] = $cotizacion_tarifa_adicional;
	
	$total_tarifa_adicional = 0;
	for ($i=0; $i < count($obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"]); $i++) { 
		$subtotal = $obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["TARIFA"] * $obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["CANTIDAD"];
		$obj_cotizacion["COTIZACION_TARIFA_ADICIONAL"][$i]["SUBTOTAL"] = $subtotal;
		$total_tarifa_adicional += $subtotal;
	}
	//FINAL DE LA PARTE TARIFA ADICIONAL
	
	//$respuesta["COTIZACION_SITIOS"] = $respuesta1;
	
	$resp = json_encode($obj_cotizacion);
	print_r($resp); 
?> 
