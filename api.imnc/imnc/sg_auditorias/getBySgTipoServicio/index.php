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
		$mailerror->send("SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias+1;
}

$id_sg_tipo_servicio = $_REQUEST["id_sg_tipo_servicio"]; 
$SG_AUDITORIAS = $database->select("SG_AUDITORIAS", "*", ["ID_SG_TIPO_SERVICIO"=>$id_sg_tipo_servicio]); 
valida_error_medoo_and_die(); 
//Multiplicador para el calculo de sitios
$const_sitio = 1; //Default - Etapa certificacion
$SG_TIPO_SERVICIO = $database->get("SG_TIPO_SERVICIO", "*", ["ID"=>$id_sg_tipo_servicio]); 
$SERV_CL = $database->get("SERVICIO_CLIENTE_ETAPA", "*", ["ID"=>$SG_TIPO_SERVICIO["ID_SERVICIO_CLIENTE_ETAPA"]]);
$TRAMITE = $database->get("ETAPAS_PROCESO", "*", ["ID_ETAPA"=>$SERV_CL["ID_ETAPA_PROCESO"]]);
valida_error_medoo_and_die();  
if(strpos($TRAMITE["ETAPA"], 'Vigilancia') !== false){ // Vigilancia
	$const_sitio = 0.6;
}

for ($i=0; $i < count($SG_AUDITORIAS) ; $i++) { 
	$SG_AUDITORIAS[$i]["TIPO_AUDITORIA_NOMBRE"] = $database->get("SG_AUDITORIAS_TIPOS", "TIPO" ,["ID"=>$SG_AUDITORIAS[$i]["TIPO_AUDITORIA"]]); 
	valida_error_medoo_and_die(); 
	$SG_AUDITORIAS[$i]["STATUS_AUDITORIA_NOMBRE"] = $database->get("SG_AUDITORIAS_STATUS", "STATUS" ,["ID"=>$SG_AUDITORIAS[$i]["STATUS_AUDITORIA"]]); 
	valida_error_medoo_and_die(); 
	//$SG_AUDITORIAS[$i]["TOTAL_SITIOS"] = $database->count("SG_SITIOS", ["ID_SG_TIPO_SERVICIO"=>$id_sg_tipo_servicio]); 
	//valida_error_medoo_and_die(); 
	$SG_AUDITORIAS[$i]["SITIOS_ASOCIADOS"] = $database->count("SG_AUDITORIA_SITIOS", ["ID_SG_AUDITORIA"=>$SG_AUDITORIAS[$i]["ID"]]); 
	valida_error_medoo_and_die(); 
	$SG_AUDITORIAS[$i]["AUDITORES_ASOCIADOS"] = $database->count("SG_AUDITORIA_GRUPOS", ["ID_SG_AUDITORIA"=>$SG_AUDITORIAS[$i]["ID"]]); 
	valida_error_medoo_and_die(); 

	//Sitio Matriz
	$mensaje_actividad_faltante = "";
	$SITIO_MATRIZ =  $database->query("
		SELECT COUNT(*) AS COUNT_MATRIZ
		FROM SG_AUDITORIA_SITIOS AS SAS
		INNER JOIN SG_SITIOS AS SGS
		ON SAS.ID_SG_SITIO = SGS.ID
		WHERE SGS.MATRIZ_PRINCIPAL = 'si' AND SAS.ID_SG_AUDITORIA =". $database->quote($SG_AUDITORIAS[$i]["ID"]))->fetchAll(PDO::FETCH_ASSOC);

	valida_error_medoo_and_die(); 
	if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
		$mensaje_actividad_faltante = "Falta sitio matriz. ";
	}

	if($SG_AUDITORIAS[$i]["NO_USA_METODO"] == 0){

		//Obtener numero de sitios por actividad
		$SITIO_TOTAL_ACTIVIDAD = $database->query("
			SELECT COUNT(*) AS COUNT_ACTIVIDAD, SGS.ID_ACTIVIDAD, SAC.ACTIVIDAD AS ACTIVIDAD
			FROM SG_SITIOS AS SGS
			INNER JOIN SG_ACTIVIDAD AS SAC
			ON SGS.ID_ACTIVIDAD = SAC.ID
			WHERE SGS.MATRIZ_PRINCIPAL = 'no' AND SGS.ID_SG_TIPO_SERVICIO =". $database->quote($id_sg_tipo_servicio)
			."GROUP BY SGS.ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);
		$SG_AUDITORIAS[$i]["TOTAL_SITIOS"] = 1; //el sitio matriz
		
		$SITIO_AUX_ACTIVIDAD = $database->query("
			SELECT COUNT(*) AS COUNT_ACTIVIDAD, SGS.ID_ACTIVIDAD
			FROM SG_AUDITORIA_SITIOS AS SAS
			INNER JOIN SG_SITIOS AS SGS
			ON SAS.ID_SG_SITIO = SGS.ID
			WHERE SGS.MATRIZ_PRINCIPAL = 'no' AND SAS.ID_SG_AUDITORIA =". $database->quote($SG_AUDITORIAS[$i]["ID"])
			."GROUP BY SGS.ID_ACTIVIDAD")->fetchAll(PDO::FETCH_ASSOC);
		$SITIO_ASOCIADO_ACTIVIDAD = array();
		foreach ($SITIO_AUX_ACTIVIDAD as $key => $actividad) {
			$SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]] = $actividad["COUNT_ACTIVIDAD"];
		}
		// Restricción de raiz cuadrada de total de sitios por actividad
		foreach ($SITIO_TOTAL_ACTIVIDAD as $key => $actividad) {
			$aux_actividad = ceil(sqrt($actividad["COUNT_ACTIVIDAD"]) * $const_sitio);
			$SG_AUDITORIAS[$i]["TOTAL_SITIOS"] += $aux_actividad;
			if( !array_key_exists($actividad["ID_ACTIVIDAD"], $SITIO_ASOCIADO_ACTIVIDAD)){
				$mensaje_actividad_faltante .= "\nFaltan ".$aux_actividad." sitios de la actividad ".$actividad["ACTIVIDAD"].". ";
			}
			else if( array_key_exists($actividad["ID_ACTIVIDAD"], $SITIO_ASOCIADO_ACTIVIDAD) && 
				$SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]] < $aux_actividad){
				$actv_faltante = $aux_actividad - $SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]];
				$mensaje_actividad_faltante .= "\nFaltan ".$actv_faltante." sitios de la actividad ".$actividad["ACTIVIDAD"].". ";
			}
		}
	}
	else{
		$SG_AUDITORIAS[$i]["TOTAL_SITIOS"] = $SG_AUDITORIAS[$i]["SITIOS_AUDITAR"]; 
	}
	// Restricciones de sitios
	$SG_AUDITORIAS[$i]["RESTRICCIONES_SITIOS"] = array();
	if ($SG_AUDITORIAS[$i]["SITIOS_ASOCIADOS"] < $SG_AUDITORIAS[$i]["TOTAL_SITIOS"]) {
		$mensaje_restriccion = 'Se deben cubrir '.$SG_AUDITORIAS[$i]["TOTAL_SITIOS"].' sitios en esta auditoria. '.$mensaje_actividad_faltante;
		array_push($SG_AUDITORIAS[$i]["RESTRICCIONES_SITIOS"], $mensaje_restriccion);
	}

	// Restricciones de grupos
	$SG_AUDITORIAS[$i]["RESTRICCIONES_GRUPOS"] = array();

	$tiene_auditor_lider = $database->count("SG_AUDITORIA_GRUPOS", ["AND" => ["ID_SG_AUDITORIA"=>$SG_AUDITORIAS[$i]["ID"], "ID_ROL"=>"TECL"]]); 
	valida_error_medoo_and_die(); 

	// Restricción de auditor líder
	if ($tiene_auditor_lider == 0) {
		$mensaje_restriccion = 'Debe estar asignado un líder';
		array_push($SG_AUDITORIAS[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}

	// Recupera los auditores asignados a la auditoría menos a los expertos ténicos (ExTec), los ExTec no cuentan en la restricción de días	
	$grupo_auditores = $database->select("SG_AUDITORIA_GRUPOS", "*", ["AND"=>["ID_SG_AUDITORIA"=>$SG_AUDITORIAS[$i]["ID"], "ID_ROL[!]"=>"ExTec"]]); 
	valida_error_medoo_and_die(); 
	$SG_AUDITORIAS[$i]["DIAS_ASIGNADOS"] = 0;

	for ($j=0; $j < count($grupo_auditores) ; $j++) { 
		$grupo_auditores_fechas = $database->select("SG_AUDITORIA_GRUPO_FECHAS", "*", ["ID_SG_AUDITORIA_GRUPO"=>$grupo_auditores[$j]["ID"]]); 
		valida_error_medoo_and_die(); 
		$grupo_auditores[$j]["FECHAS_ASIGNADAS"] = $grupo_auditores_fechas;
		$SG_AUDITORIAS[$i]["DIAS_ASIGNADOS"] += count($grupo_auditores_fechas);
	}
	
	// Restricción de días auditor
	if ($SG_AUDITORIAS[$i]["DIAS_ASIGNADOS"] < $SG_AUDITORIAS[$i]["DURACION_DIAS"]) {
		$mensaje_restriccion = 'Faltan días por asignar (se han asignado '.$SG_AUDITORIAS[$i]["DIAS_ASIGNADOS"].' de '. $SG_AUDITORIAS[$i]["DURACION_DIAS"].')';
		array_push($SG_AUDITORIAS[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}

	$sg_sectores = $database->select("SG_SECTORES", "ID_SECTOR", ["ID_SG_TIPO_SERVICIO"=>$SG_AUDITORIAS[$i]["ID_SG_TIPO_SERVICIO"]]); 
	$ids_pt_califs = $database->select("SG_AUDITORIA_GRUPOS", "ID_PERSONAL_TECNICO_CALIF", ["ID_SG_AUDITORIA"=>$SG_AUDITORIAS[$i]["ID"]]);
	$sectores_calificados = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["ID_PERSONAL_TECNICO_CALIFICACION"=>$ids_pt_califs]);

	//print_r($sg_sectores);
	//print_r($sectores_calificados);
	//print_r(array_diff($sg_sectores,$sectores_calificados));
	
	// Restricción de calificacion del grupo aditor
	if( $SG_AUDITORIAS[$i]["TIPO_AUDITORIA"] != "E1" && ($sectores_calificados == NULL || count(array_diff($sg_sectores, $sectores_calificados)) > 0)){
		$mensaje_restriccion = 'El grupo no está calificado en todos los sectores';
		array_push($SG_AUDITORIAS[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}
}


print_r(json_encode($SG_AUDITORIAS)); 
?> 
