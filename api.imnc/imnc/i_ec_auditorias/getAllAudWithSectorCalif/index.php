<?php 
error_reporting(E_ALL);
ini_set("display_errors",1);


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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "leovardo.quintero@dhttecno.com");
		die();
	}
}


	$respuesta=array();
	$id_sce = $_REQUEST["idsce"];
	$idtipoauditoria = $_REQUEST["idtipoauditoria"];
	$ciclo = $_REQUEST["ciclo"];
	
//$auditoria = $database->get("SG_AUDITORIAS", ["FECHA_INICIO", "DURACION_DIAS"], ["ID"=>$id_sg_auditoria]);
//valida_error_medoo_and_die();

//$fecha_fin_auditoria = date('Ymd',strtotime($auditoria["FECHA_INICIO"] . "+".strval(intval($auditoria["DURACION_DIAS"])-1)." days")); 

//$auditoria["FECHA_FIN"] = $fecha_fin_auditoria;


//$total_sectores = $database->count("I_SG_SECTORES", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id_sce]);
//valida_error_medoo_and_die();

//if ($total_sectores == 0){
//	valida_parametro_and_die("", "Debe existir por lo menos un sector asociado a este tipo de servicio");
//}
/*
$strQuery = "SELECT 
		MAX(`PERSONAL_TECNICO_CALIF_SECTOR`.`FECHA_INICIO`),
		`SECTORES`.`ID`,
		`SECTORES`.`NOMBRE` AS `NOMBRE_SECTOR`,
		`SECTORES`.`ANHIO` AS `ANHIO`,
		`PERSONAL_TECNICO_CALIF_SECTOR`.`SECTOR_NACE`,
		`PERSONAL_TECNICO_CALIF_SECTOR`.`ALCANCE`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID` AS `PT_CALIF_ID`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_ROL`,
		`PERSONAL_TECNICO`.`INICIALES`,
		`PERSONAL_TECNICO`.`NOMBRE`,
		`PERSONAL_TECNICO`.`APELLIDO_PATERNO`,
		`PERSONAL_TECNICO`.`APELLIDO_MATERNO`,
		`PERSONAL_TECNICO`.`EMAIL`,
		`PERSONAL_TECNICO`.`STATUS`,
		`PERSONAL_TECNICO`.`IMAGEN_BASE64` ,
		`TIPOS_SERVICIO`.`ID_REFERENCIA`
		FROM `PERSONAL_TECNICO_CALIF_SECTOR` 
		INNER JOIN `I_SG_SECTORES` ON `I_SG_SECTORES`.`ID_SECTOR` = `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_SECTOR` 
		INNER JOIN `SECTORES` ON `SECTORES`.`ID_SECTOR` = `I_SG_SECTORES`.`ID_SECTOR` 
		INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID`= `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_PERSONAL_TECNICO_CALIFICACION` 
		INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID` = `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO` 
		INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA` AND `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` = `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO` 
		INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` 
		
		WHERE `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA`= " . $database->quote($id_sce) . "
		GROUP BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID` 
		ORDER BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`";
*/
// ==============================================================
// *		Recuperar auditores CON calificación				*
// ==============================================================
//$all_pt= array();
//$all_pt = $database->query($strQuery)->fetchAll();
//valida_error_medoo_and_die();
//print_r($all_pt);
//print_r("_._._._.");

//if(sizeof($all_pt) > 0 ){

$array_pt_califs = array();

//for ($i=0; $i < count($all_pt) ; $i++) { 
//	if (array_key_exists($all_pt[$i]["ID_PERSONAL_TECNICO"], $respuesta)) { //Agregar al arreglo
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CANT_SECTORES"] += 1;
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["TOTAL"] = $respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CANT_SECTORES"] . " de " . $total_sectores;
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["JERARQUIA"] = $database->get("PERSONAL_TECNICO_ROLES", "JERARQUIA", ["ID" => $all_pt[$i]["ID_ROL"]]);
//		$detalles_de_califs = array();
//		$detalles_de_califs["ID_SECTOR"] = $all_pt[$i]["ID"]."-".$all_pt[$i]["ID_REFERENCIA"]."-".$all_pt[$i]["ANHIO"];
//		$detalles_de_califs["NOMBRE_SECTOR"] = $all_pt[$i]["NOMBRE_SECTOR"];
//		$detalles_de_califs["ANHIO"] = $all_pt[$i]["ANHIO"];
//		$detalles_de_califs["SECTOR_NACE"] = $all_pt[$i]["SECTOR_NACE"];
//		$detalles_de_califs["ALCANCE"] = $all_pt[$i]["ALCANCE"];
//		$detalles_de_califs["ROL"] = $database->get("PERSONAL_TECNICO_ROLES", "ROL", ["ID" => $all_pt[$i]["ID_ROL"]]);
		
//		array_push($respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CALIFICACIONES"], $detalles_de_califs);
//	}
//	else{ // Insertar nuevo al arreglo
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]] = array();
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["ID_PERSONAL_TECNICO"] = $all_pt[$i]["ID_PERSONAL_TECNICO"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["NOMBRE_COMPLETO"] = $all_pt[$i]["NOMBRE"] . " " . $all_pt[$i]["APELLIDO_PATERNO"] . " " . $all_pt[$i]["APELLIDO_MATERNO"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["STATUS"] = $all_pt[$i]["STATUS"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["JERARQUIA"] = $database->get("PERSONAL_TECNICO_ROLES", "JERARQUIA", ["ID" => $all_pt[$i]["ID_ROL"]]);
		
		//$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["IMAGEN_BASE64"] = $all_pt[$i]["IMAGEN_BASE64"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CANT_SECTORES"] = 1;
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["TOTAL"] = $respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CANT_SECTORES"] . " de " . $total_sectores;
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CALIFICACIONES"] = array();
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["REGISTRO"] = $all_pt[$i]["REGISTRO"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["PT_CALIF_ID"] = $all_pt[$i]["PT_CALIF_ID"];
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["ID_TIPO_SERVICIO"] = $all_pt[$i]["ID_TIPO_SERVICIO"];
		//print_r("__");
		//print_r($respuesta);
		//print_r("__");
//		array_push($array_pt_califs, $all_pt[$i]["PT_CALIF_ID"]);

		// Verfica que auditor no tenga las fechas de la auditoria asignadas

//		$id_pt_calif = $respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["PT_CALIF_ID"];
//		$id_pt = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "ID_PERSONAL_TECNICO", ["ID"=>$id_pt_calif]);
//		valida_error_medoo_and_die();
//		$ids_pt_calif =  $database->select("PERSONAL_TECNICO_CALIFICACIONES", "ID", ["ID_PERSONAL_TECNICO"=>$id_pt]);
//		valida_error_medoo_and_die();

/*		$consulta = "SELECT 
	PT.ID,
	PT.NOMBRE,
	PT.APELLIDO_MATERNO,
	PT.APELLIDO_PATERNO,
	PT.INICIALES,
	PT.FECHA_NACIMIENTO,
	SGAG.ID_SERVICIO_CLIENTE_ETAPA AS ID_SERVICIO_CLIENTE_ETAPA_SGAG,
	SGAG.TIPO_AUDITORIA AS TIPO_AUDITORIA_SGAG,
	SGAG.CICLO AS CICLO_SGAG,
	SGAGF.ID AS ID_SGAGF,
	SGAGF.FECHA 
	FROM 
		PERSONAL_TECNICO AS PT
	INNER JOIN 	
		PERSONAL_TECNICO_CALIFICACIONES AS PTC ON PTC.ID_PERSONAL_TECNICO = PT.ID
	INNER JOIN 	
		I_SG_AUDITORIA_GRUPOS AS SGAG ON SGAG.ID_PERSONAL_TECNICO_CALIF = PTC.ID
	INNER JOIN	
		I_SG_AUDITORIA_GRUPO_FECHAS AS SGAGF ON 
												SGAGF.ID_SERVICIO_CLIENTE_ETAPA = SGAG.ID_SERVICIO_CLIENTE_ETAPA
											AND SGAGF.TIPO_AUDITORIA = SGAG.TIPO_AUDITORIA
											AND SGAGF.CICLO = SGAG.CICLO
											AND SGAGF.ID_PERSONAL_TECNICO_CALIF = SGAG.ID_PERSONAL_TECNICO_CALIF
											
	WHERE 
		PT.ID = ".$database->quote($id_pt)." 
		AND SGAGF.FECHA NOT IN (SELECT FECHA 
										FROM I_SG_AUDITORIA_FECHAS WHERE 
																			ID_SERVICIO_CLIENTE_ETAPA = ".$database->quote($id_sce)."
																		AND TIPO_AUDITORIA = ".$database->quote($idtipoauditoria)."
																		AND CICLO = ".$database->quote($ciclo).")";
	*/ /*	$consulta = "SELECT PT.ID,PT.NOMBRE,PT.APELLIDO_MATERNO,PT.APELLIDO_PATERNO,PT.INICIALES,PT.FECHA_NACIMIENTO,SGAG.ID AS ID_SGAG,SGAGF.ID AS ID_SGAGF,SGAGF.FECHA FROM PERSONAL_TECNICO AS PT,PERSONAL_TECNICO_CALIFICACIONES AS PTC ,SG_AUDITORIA_GRUPOS AS SGAG, SG_AUDITORIA_GRUPO_FECHAS AS SGAGF WHERE PT.ID = ".$database->quote($id_pt)." AND PTC.ID_PERSONAL_TECNICO = PT.ID AND SGAG.ID_PERSONAL_TECNICO_CALIF = PTC.ID AND SGAGF.ID_SG_AUDITORIA_GRUPO = SGAG.ID AND SGAGF.FECHA NOT IN (SELECT FECHA FROM SG_AUDITORIA_FECHAS WHERE ID_SG_AUDITORIA = ".$database->quote($id_sg_auditoria).")";*/
	
		//echo $consulta;
		//$tiene_fechas = array();
//		$tiene_fechas = $database->query($consulta)->fetchAll();
//		valida_error_medoo_and_die();
//		if (sizeof($tiene_fechas) == 0) {
//			$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["STATUS"] = "asignado";
//		}

		// Detalles de las calificaciones del auditor
		
//		$detalles_de_califs = array();
//		$detalles_de_califs["ID_SECTOR"] = $all_pt[$i]["ID"]."-".$all_pt[$i]["ID_REFERENCIA"]."-".$all_pt[$i]["ANHIO"];
//		$detalles_de_califs["NOMBRE_SECTOR"] = $all_pt[$i]["NOMBRE_SECTOR"];
//		$detalles_de_califs["SECTOR_NACE"] = $all_pt[$i]["SECTOR_NACE"];
//		$detalles_de_califs["ALCANCE"] = $all_pt[$i]["ALCANCE"];
//		$detalles_de_califs["ROL"] = $database->get("PERSONAL_TECNICO_ROLES", "ROL", ["ID" => $all_pt[$i]["ID_ROL"]]);
		
//		array_push($respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CALIFICACIONES"], $detalles_de_califs);

//		$en_grupo = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND" => ["ID_PERSONAL_TECNICO_CALIF"=>$all_pt[$i]["PT_CALIF_ID"], "ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$idtipoauditoria,"CICLO"=>$ciclo]]);
//		valida_error_medoo_and_die();
//		$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["EN_GRUPO"] = $en_grupo;
//	}
//}


// ==============================================================
// *		Recuperar auditores SIN calificación				*
// ==============================================================

$tipo_servicio = $database->get("SERVICIO_CLIENTE_ETAPA", "ID_TIPO_SERVICIO", ["ID"=>$id_sce]);
$norma = $database->get("SERVICIO_CLIENTE_ETAPA", "ID_NORMA", ["ID"=>$id_sce]);
valida_error_medoo_and_die();

if (count($array_pt_califs) > 0) { // Si hay auditores con calificacion se hace un query con todos menos ellos
	$otras_califs = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "*", ["AND"=>["ID[!]"=>$array_pt_califs, "ID_TIPO_SERVICIO"=>$tipo_servicio,"ID_NORMA"=>$norma]]);

	}
else{
	$otras_califs = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "*", ["AND"=>[ "ID_TIPO_SERVICIO"=>$tipo_servicio,"ID_NORMA"=>$norma]]);
}

valida_error_medoo_and_die();
for ($i=0; $i < count($otras_califs); $i++) { 
	$otros_auditores = $database->get("PERSONAL_TECNICO", "*", ["ID"=>$otras_califs[$i]["ID_PERSONAL_TECNICO"]]);
	valida_error_medoo_and_die();
	$rol = $database->get("PERSONAL_TECNICO_ROLES", "ROL", ["ID"=>$otras_califs[$i]["ID_ROL"]]);
	valida_error_medoo_and_die();
	///agreagdo 14 febrero
	$jerq =$database->get("PERSONAL_TECNICO_ROLES", "JERARQUIA", ["ID" => $otras_califs[$i]["ID_ROL"]]);
	//valida_error_medoo_and_die();


	$otras_califs[$i]["ROL"] = $rol;
	$otras_califs[$i]["JERARQUIA"] =$jerq;

	$otras_califs[$i]["ID_PERSONAL_TECNICO"] = $otros_auditores["ID"];
	$otras_califs[$i]["NOMBRE_COMPLETO"] =  $otros_auditores["NOMBRE"] . " " . $otros_auditores["APELLIDO_PATERNO"] . " " . $otros_auditores["APELLIDO_MATERNO"];
	$otras_califs[$i]["STATUS"] = $otros_auditores["STATUS"];
	//$otras_califs[$i]["TOTAL"] = "0 de " . $total_sectores;
	$otras_califs[$i]["PT_CALIF_ID"] = $otras_califs[$i]["ID"];
	$otras_califs[$i]["EN_GRUPO"] = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND" => ["ID_PERSONAL_TECNICO_CALIF"=>$otras_califs[$i]["PT_CALIF_ID"], "ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$idtipoauditoria,"CICLO"=>$ciclo]]);
	valida_error_medoo_and_die();

	// Verfica que auditor no tenga las fechas de la auditoria asignadas

	$id_pt_calif = $otras_califs[$i]["PT_CALIF_ID"];
	$id_pt = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "ID_PERSONAL_TECNICO", ["ID"=>$id_pt_calif]);
	valida_error_medoo_and_die();
	$ids_pt_calif =  $database->select("PERSONAL_TECNICO_CALIFICACIONES", "ID", ["ID_PERSONAL_TECNICO"=>$id_pt]);
	valida_error_medoo_and_die();

		$consulta = "SELECT 
	PT.ID,
	PT.NOMBRE,
	PT.APELLIDO_MATERNO,
	PT.APELLIDO_PATERNO,
	PT.INICIALES,
	PT.FECHA_NACIMIENTO,
	SGAG.ID_SERVICIO_CLIENTE_ETAPA AS ID_SERVICIO_CLIENTE_ETAPA_SGAG,
	SGAG.TIPO_AUDITORIA AS TIPO_AUDITORIA_SGAG,
	SGAG.CICLO AS CICLO_SGAG,
	SGAGF.ID AS ID_SGAGF,
	SGAGF.FECHA 
	FROM 
		PERSONAL_TECNICO AS PT
	INNER JOIN 	
		PERSONAL_TECNICO_CALIFICACIONES AS PTC ON PTC.ID_PERSONAL_TECNICO = PT.ID
	INNER JOIN 	
		I_SG_AUDITORIA_GRUPOS AS SGAG ON SGAG.ID_PERSONAL_TECNICO_CALIF = PTC.ID
	INNER JOIN	
		I_SG_AUDITORIA_GRUPO_FECHAS AS SGAGF ON 
												SGAGF.ID_SERVICIO_CLIENTE_ETAPA = SGAG.ID_SERVICIO_CLIENTE_ETAPA
											AND SGAGF.TIPO_AUDITORIA = SGAG.TIPO_AUDITORIA
											AND SGAGF.CICLO = SGAG.CICLO
											AND SGAGF.ID_PERSONAL_TECNICO_CALIF = SGAG.ID_PERSONAL_TECNICO_CALIF
											
	WHERE 
		PT.ID = ".$database->quote($id_pt)." 
		AND SGAGF.FECHA NOT IN (SELECT FECHA 
										FROM I_SG_AUDITORIA_FECHAS WHERE 
																			ID_SERVICIO_CLIENTE_ETAPA = ".$database->quote($id_sce)."
																		AND TIPO_AUDITORIA = ".$database->quote($idtipoauditoria)."
																		AND CICLO = ".$database->quote($ciclo).")";
	/*	$consulta = "SELECT PT.ID,PT.NOMBRE,PT.APELLIDO_MATERNO,PT.APELLIDO_PATERNO,PT.INICIALES,PT.FECHA_NACIMIENTO,SGAG.ID AS ID_SGAG,SGAGF.ID AS ID_SGAGF,SGAGF.FECHA FROM PERSONAL_TECNICO AS PT,PERSONAL_TECNICO_CALIFICACIONES AS PTC ,SG_AUDITORIA_GRUPOS AS SGAG, SG_AUDITORIA_GRUPO_FECHAS AS SGAGF WHERE PT.ID = ".$database->quote($id_pt)." AND PTC.ID_PERSONAL_TECNICO = PT.ID AND SGAG.ID_PERSONAL_TECNICO_CALIF = PTC.ID AND SGAGF.ID_SG_AUDITORIA_GRUPO = SGAG.ID AND SGAGF.FECHA NOT IN (SELECT FECHA FROM SG_AUDITORIA_FECHAS WHERE ID_SG_AUDITORIA = ".$database->quote($id_sg_auditoria).")";*/
		//$consulta = "SELECT PT.ID,PT.NOMBRE,PT.APELLIDO_MATERNO,PT.APELLIDO_PATERNO,PT.INICIALES,PT.FECHA_NACIMIENTO,SGAG.ID AS ID_SGAG,SGAGF.ID AS ID_SGAGF,SGAGF.FECHA FROM PERSONAL_TECNICO AS PT,PERSONAL_TECNICO_CALIFICACIONES AS PTC ,SG_AUDITORIA_GRUPOS AS SGAG, SG_AUDITORIA_GRUPO_FECHAS AS SGAGF WHERE PT.ID = ".$database->quote($id_pt)." AND PTC.ID_PERSONAL_TECNICO = PT.ID AND SGAG.ID_PERSONAL_TECNICO_CALIF = PTC.ID AND SGAGF.ID_SG_AUDITORIA_GRUPO = SGAG.ID AND SGAGF.FECHA NOT IN (SELECT FECHA FROM SG_AUDITORIA_FECHAS )";
		//$consulta = "SELECT * FROM PERSONAL_TECNICO,PERSONAL_TECNICO_CALIFICACIONES,SG_AUDITORIA_GRUPOS, SG_AUDITORIA_GRUPO_FECHAS ";
		
		//echo $consulta;
//		$tiene_fechas = $database->query($consulta)->fetchAll();
//		valida_error_medoo_and_die();
//		if (sizeof($tiene_fechas) == 0) {
			//$respuesta[$otras_califs[$i]["ID_PERSONAL_TECNICO"]]["STATUS"] = "asignado";
//			$otras_califs[$i]["STATUS"] = "asignado";
//		}
}

// ==============================================================
// * ORDENAR "CON CALIFICACION" POR CANTIDAD DE CALIFICACIONES	*
// ==============================================================

/*
$CANT_SECTORES = array();
foreach ($respuesta as $key => $row)
{
    $CANT_SECTORES[$key] = $row['CANT_SECTORES'];
}
array_multisort($CANT_SECTORES, SORT_DESC, $respuesta);
*/

$respuesta_final = array();
$respuesta_final["CON_CALIFICACION"] = $respuesta;
$respuesta_final["SIN_CALIFICACION"] = $otras_califs;
//}
//else{
//    $respuesta_final = array();
//$respuesta_final["CON_CALIFICACION"] = array();
//$respuesta_final["SIN_CALIFICACION"] = array();
//}

//print_r($respuesta);
//print_r(json_encode($all_pt));
print_r(json_encode($respuesta_final));
//print_r(json_encode($tiene_fechas));


//-------- FIN --------------
?>


