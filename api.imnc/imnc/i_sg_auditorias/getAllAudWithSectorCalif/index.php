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
$respuesta_otra = array();
$id_sce = $_REQUEST["idsce"];
$idtipoauditoria = $_REQUEST["idtipoauditoria"];
$ciclo = $_REQUEST["ciclo"];
//Lo primero es buscar el id tipo de servicio que sera un dato importante para trabajar
$tipo_servicio = $database->get("SERVICIO_CLIENTE_ETAPA", "ID_TIPO_SERVICIO", ["ID"=>$id_sce]);
//$auditoria = $database->get("SG_AUDITORIAS", ["FECHA_INICIO", "DURACION_DIAS"], ["ID"=>$id_sg_auditoria]);
//valida_error_medoo_and_die();

//$fecha_fin_auditoria = date('Ymd',strtotime($auditoria["FECHA_INICIO"] . "+".strval(intval($auditoria["DURACION_DIAS"])-1)." days")); 

//$auditoria["FECHA_FIN"] = $fecha_fin_auditoria;


$total_sectores = $database->count("I_SG_SECTORES", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id_sce]);
valida_error_medoo_and_die();

if ($total_sectores == 0){
	valida_parametro_and_die("", "Debe existir por lo menos un sector asociado a este tipo de servicio");
}
// Aqui se verifica si es para un servicio integral o no
if($tipo_servicio == 20){
	$strQuery = "SELECT 
		MAX(`PERSONAL_TECNICO_CALIF_SECTOR`.`FECHA_INICIO`),
		`SECTORES`.`ID`,
		`SECTORES`.`NOMBRE` AS `NOMBRE_SECTOR`,
		`SECTORES`.`ANHIO` AS `ANHIO`,
		`PERSONAL_TECNICO_CALIF_SECTOR`.`SECTOR_NACE`,
		`PERSONAL_TECNICO_CALIF_SECTOR`.`ALCANCE`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` AS REGISTRO,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID` AS `PT_CALIF_ID`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`,
		`PERSONAL_TECNICO_CALIFICACIONES`.`ID_ROL`,
		`PERSONAL_TECNICO`.`INICIALES`,
		`PERSONAL_TECNICO`.`NOMBRE`,
		`PERSONAL_TECNICO`.`APELLIDO_PATERNO`,
		`PERSONAL_TECNICO`.`APELLIDO_MATERNO`,
		`PERSONAL_TECNICO`.`EMAIL`,
		`PERSONAL_TECNICO`.`STATUS`,
		`TIPOS_SERVICIO`.`ID_REFERENCIA`
		FROM `PERSONAL_TECNICO_CALIF_SECTOR` 
		INNER JOIN `I_SG_SECTORES` ON `I_SG_SECTORES`.`ID_SECTOR` = `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_SECTOR` 
		INNER JOIN `SECTORES` ON `SECTORES`.`ID_SECTOR` = `I_SG_SECTORES`.`ID_SECTOR` 
		INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID`= `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_PERSONAL_TECNICO_CALIFICACION` 
		INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID` = `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO` 
     	INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA` 
		INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` 
		WHERE `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA`= " . $database->quote($id_sce) . " 
		GROUP BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID`,`PERSONAL_TECNICO_CALIF_SECTOR`.`ID_SECTOR`  
		ORDER BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID`";
}
else{
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
		`TIPOS_SERVICIO`.`ID_REFERENCIA`
		FROM `PERSONAL_TECNICO_CALIF_SECTOR` 
		INNER JOIN `I_SG_SECTORES` ON `I_SG_SECTORES`.`ID_SECTOR` = `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_SECTOR` 
		INNER JOIN `SECTORES` ON `SECTORES`.`ID_SECTOR` = `I_SG_SECTORES`.`ID_SECTOR` 
		INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID`= `PERSONAL_TECNICO_CALIF_SECTOR`.`ID_PERSONAL_TECNICO_CALIFICACION` 
		INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID` = `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO` 
		INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA` AND `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` = `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`
		INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` 
		
		WHERE `I_SG_SECTORES`.`ID_SERVICIO_CLIENTE_ETAPA`= " . $database->quote($id_sce) . "
		GROUP BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID`,`PERSONAL_TECNICO_CALIF_SECTOR`.`ID_SECTOR`
		ORDER BY `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`,`PERSONAL_TECNICO_CALIFICACIONES`.`ID`";
}

// ==============================================================
// *		Recuperar auditores CON calificación				*
// ==============================================================
//$all_pt= array();

$all_pt = $database->query($strQuery)->fetchAll();
valida_error_medoo_and_die();

//print_r($all_pt);
//print_r("_._._._.");
if(sizeof($all_pt) > 0 ){

	$array_pt_califs = array();

	for ($i=0; $i < count($all_pt) ; $i++) {
		if (array_key_exists($all_pt[$i]["PT_CALIF_ID"], $respuesta)) { //Agregar al arreglo
//			$anterior = $respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CALIFICACIONES"][count($respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["CALIFICACIONES"])-1]["ID_SECTOR"];
//			$actual = $all_pt[$i]["ID"]."-".$all_pt[$i]["ID_REFERENCIA"]."-".$all_pt[$i]["ANHIO"];
//			if($anterior != $actual)
//			{
				$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CANT_SECTORES"] += 1;
				$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["TOTAL"] = $respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CANT_SECTORES"] . " de " . $total_sectores;
				$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["JERARQUIA"] = $database->get("PERSONAL_TECNICO_ROLES", "JERARQUIA", ["ID" => $all_pt[$i]["ID_ROL"]]);
				$detalles_de_califs = array();
				$detalles_de_califs["ID_SECTOR"] = $all_pt[$i]["ID"]."-".$all_pt[$i]["ID_REFERENCIA"]."-".$all_pt[$i]["ANHIO"];
				$detalles_de_califs["NOMBRE_SECTOR"] = $all_pt[$i]["NOMBRE_SECTOR"];
				$detalles_de_califs["ANHIO"] = $all_pt[$i]["ANHIO"];
				$detalles_de_califs["SECTOR_NACE"] = $all_pt[$i]["SECTOR_NACE"];
				$detalles_de_califs["ALCANCE"] = $all_pt[$i]["ALCANCE"];
				array_push($respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CALIFICACIONES"], $detalles_de_califs);
				//}

		}
		else{ // Insertar nuevo al arreglo
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]] = array();
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["ID_PERSONAL_TECNICO"] = $all_pt[$i]["ID_PERSONAL_TECNICO"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["NOMBRE_COMPLETO"] = $all_pt[$i]["NOMBRE"] . " " . $all_pt[$i]["APELLIDO_PATERNO"] . " " . $all_pt[$i]["APELLIDO_MATERNO"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["STATUS"] = $all_pt[$i]["STATUS"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["JERARQUIA"] = $database->get("PERSONAL_TECNICO_ROLES", "JERARQUIA", ["ID" => $all_pt[$i]["ID_ROL"]]);

			//$respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["IMAGEN_BASE64"] = $all_pt[$i]["IMAGEN_BASE64"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CANT_SECTORES"] = 1;
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["TOTAL"] = $respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CANT_SECTORES"] . " de " . $total_sectores;
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CALIFICACIONES"] = array();
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["REGISTRO"] = $all_pt[$i]["REGISTRO"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["PT_CALIF_ID"] = $all_pt[$i]["PT_CALIF_ID"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["ID_TIPO_SERVICIO"] = $all_pt[$i]["ID_TIPO_SERVICIO"];
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["ROL"] = $database->get("PERSONAL_TECNICO_ROLES", "ROL", ["ID" => $all_pt[$i]["ID_ROL"]]);

			array_push($array_pt_califs, $all_pt[$i]["PT_CALIF_ID"]);

			// Verfica que auditor no tenga las fechas de la auditoria asignadas

			/*$id_pt_calif = $respuesta[$all_pt[$i]["ID_PERSONAL_TECNICO"]]["PT_CALIF_ID"];
            $id_pt = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "ID_PERSONAL_TECNICO", ["ID"=>$id_pt_calif]);
            valida_error_medoo_and_die();
            $ids_pt_calif =  $database->select("PERSONAL_TECNICO_CALIFICACIONES", "ID", ["ID_PERSONAL_TECNICO"=>$id_pt]);
            valida_error_medoo_and_die();*/

			// Detalles de las calificaciones del auditor

			$detalles_de_califs = array();
			$detalles_de_califs["ID_SECTOR"] = $all_pt[$i]["ID"]."-".$all_pt[$i]["ID_REFERENCIA"]."-".$all_pt[$i]["ANHIO"];
			$detalles_de_califs["NOMBRE_SECTOR"] = $all_pt[$i]["NOMBRE_SECTOR"];
			$detalles_de_califs["SECTOR_NACE"] = $all_pt[$i]["SECTOR_NACE"];
			$detalles_de_califs["ALCANCE"] = $all_pt[$i]["ALCANCE"];

			array_push($respuesta[$all_pt[$i]["PT_CALIF_ID"]]["CALIFICACIONES"], $detalles_de_califs);

			$en_grupo = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND" => ["ID_PERSONAL_TECNICO_CALIF"=>$all_pt[$i]["PT_CALIF_ID"], "ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"TIPO_AUDITORIA"=>$idtipoauditoria,"CICLO"=>$ciclo]]);
			valida_error_medoo_and_die();
			$respuesta[$all_pt[$i]["PT_CALIF_ID"]]["EN_GRUPO"] = $en_grupo;
		}
	}

// ==============================================================
// *		Recuperar auditores SIN calificación				*
// ==============================================================

	$norma = array();
	$normas = $database->select("SCE_NORMAS", "ID_NORMA", ["ID_SCE"=>$id_sce]);
	valida_error_medoo_and_die();
    $query = "";
    $str_norma = "";
    foreach ($normas as $item)
	{
		$str_norma = "'".$item."'";
	}

		if (count($array_pt_califs) > 0) {
//			$str_calif = " WHERE PTC.`ID` NOT IN  (".implode(',',$array_pt_califs).") AND CN.`ID_NORMA` IN (".$str_norma.") ";
		$str_calif = " WHERE PTC.`ID` NOT IN  (".implode(',',$array_pt_califs).") ";
		} // Si hay auditores con calificacion se hace un query con todos menos ellos
	    $query .=  "SELECT 	PTC.`ID`,
		PTC.`ID_ROL`,
		PTC.`ID_PERSONAL_TECNICO`,
   		PTC.`ID_TIPO_SERVICIO`,
		PTC.`REGISTRO`,
        CONCAT(PT.`NOMBRE`,' ',PT.`APELLIDO_PATERNO`,' ',PT.`APELLIDO_MATERNO`) AS NOMBRE_COMPLETO,
		CONCAT('0 de ',".$total_sectores.") AS TOTAL,
        PT.`STATUS`,
        PTR.`ROL`,
        PTR.`JERARQUIA`,
		PTC.`ID` AS PT_CALIF_ID,
		CN.`ID_NORMA`
		FROM `PERSONAL_TECNICO_CALIFICACIONES` PTC
		INNER JOIN `PERSONAL_TECNICO` PT  ON PTC.`ID_PERSONAL_TECNICO` = PT.ID
		INNER JOIN `PERSONAL_TECNICO_ROLES` PTR  ON PTC.`ID_ROL` = PTR.ID
		INNER JOIN `CALIFICACIONES_NORMAS` CN ON PTC.ID = CN.`ID_CALIFICACION`
		".$str_calif." GROUP BY PTC.`ID`,PTC.`ID_TIPO_SERVICIO`,CN.`ID_NORMA` ORDER BY PTC.`ID_PERSONAL_TECNICO`, PTC.`ID`";
			//$otras_califs = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "*", ["AND"=>["ID[!]"=>$array_pt_califs, "ID_TIPO_SERVICIO"=>$tipo_servicio]]);

	$otras_califs = $database->query($query)->fetchAll();
	valida_error_medoo_and_die();

	if(count($otras_califs)>0)
	{
		for ($i=0; $i < count($otras_califs) ; $i++) {
		 if (array_key_exists($otras_califs[$i]["PT_CALIF_ID"], $respuesta_otra)) { //Agregar al arreglo
					 if(count(array_diff(array($otras_califs[$i]["ID_NORMA"]), $normas))==0) {
						 $detalles_de_califs = array();
						 $detalles_de_califs["NORMA"] = $otras_califs[$i]["ID_NORMA"];
						 array_push($respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["CALIFICACIONES"], $detalles_de_califs);
					 }

					if($respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["EN_GRUPO"]==0)
					{
						$en_grupo = $database->count("I_SG_AUDITORIA_GRUPOS",["[><]PERSONAL_TECNICO_CALIFICACIONES"=>["ID_PERSONAL_TECNICO_CALIF"=>"ID"]],"I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA", ["AND" => ["PERSONAL_TECNICO_CALIFICACIONES.ID_PERSONAL_TECNICO"=>$otras_califs[$i]["ID_PERSONAL_TECNICO"], "I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=>$idtipoauditoria,"I_SG_AUDITORIA_GRUPOS.CICLO"=>$ciclo]]);
						valida_error_medoo_and_die();
						$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["EN_GRUPO"] = $en_grupo;
					}
			}else
			{
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]] = array();
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["ID_PERSONAL_TECNICO"] = $otras_califs[$i]["ID_PERSONAL_TECNICO"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["NOMBRE_COMPLETO"] = $otras_califs[$i]["NOMBRE_COMPLETO"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["STATUS"] = $otras_califs[$i]["STATUS"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["JERARQUIA"] = $otras_califs[$i]["JERARQUIA"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["TOTAL"] = $otras_califs[$i]["TOTAL"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["REGISTRO"] = $otras_califs[$i]["REGISTRO"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["ID_TIPO_SERVICIO"] = $otras_califs[$i]["ID_TIPO_SERVICIO"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["PT_CALIF_ID"] = $otras_califs[$i]["PT_CALIF_ID"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["ROL"] = $otras_califs[$i]["ROL"];
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["CALIFICACIONES"] = array();

					if(count(array_diff(array($otras_califs[$i]["ID_NORMA"]), $normas))==0)
					{
						$detalles_de_califs = array();
						$detalles_de_califs["NORMA"] =  $otras_califs[$i]["ID_NORMA"];
						array_push($respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["CALIFICACIONES"], $detalles_de_califs);
					}

				$en_grupo = $database->count("I_SG_AUDITORIA_GRUPOS",["[><]PERSONAL_TECNICO_CALIFICACIONES"=>["ID_PERSONAL_TECNICO_CALIF"=>"ID"]],"I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA", ["AND" => ["PERSONAL_TECNICO_CALIFICACIONES.ID_PERSONAL_TECNICO"=>$otras_califs[$i]["ID_PERSONAL_TECNICO"], "I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=>$idtipoauditoria,"I_SG_AUDITORIA_GRUPOS.CICLO"=>$ciclo]]);
				valida_error_medoo_and_die();
				$respuesta_otra[$otras_califs[$i]["PT_CALIF_ID"]]["EN_GRUPO"] = $en_grupo;
			}



		}
	}




// ==============================================================
// * ORDENAR "CON CALIFICACION" POR CANTIDAD DE CALIFICACIONES	*
// ==============================================================


	$CANT_SECTORES = array();
	foreach ($respuesta as $key => $row)
	{
		$CANT_SECTORES[$key] = $row['CANT_SECTORES'];
	}
	array_multisort($CANT_SECTORES, SORT_DESC, $respuesta);

	$CANT_CALIF = array();
	foreach ($respuesta_otra as $key => $row)
	{
		$CANT_CALIF[$key] = count($row['CALIFICACIONES']);
	}
	array_multisort($CANT_CALIF, SORT_DESC, $respuesta_otra);


	$respuesta_final = array();
	$respuesta_final["CON_CALIFICACION"] = $respuesta;
	$respuesta_final["SIN_CALIFICACION"] = $respuesta_otra;
}
else{
	$respuesta_final = array();
	$respuesta_final["CON_CALIFICACION"] = array();
	$respuesta_final["SIN_CALIFICACION"] = array();
}

//print_r($respuesta);
//print_r(json_encode($all_pt));
$respuesta_final["resultado"] = "ok";
print_r(json_encode($respuesta_final,JSON_PRETTY_PRINT));
//print_r(json_encode($tiene_fechas));


//-------- FIN --------------
?>

