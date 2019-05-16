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
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

											
$valores = $database->query("SELECT `I_EC_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_EC_AUDITORIAS`.`DURACION_DIAS`, `I_SG_AUDITORIAS_TIPOS`.`TIPO`,`I_SG_AUDITORIA_STATUS`.`STATUS`,`I_EC_AUDITORIAS`.`ID_USUARIO_CREACION`,`I_EC_AUDITORIAS`.`ID_USUARIO_MODIFICACION`,`I_EC_AUDITORIAS`.`TIPO_AUDITORIA`,`I_EC_AUDITORIAS`.`STATUS_AUDITORIA`,`I_EC_AUDITORIAS`.`CICLO` FROM `I_EC_AUDITORIAS` INNER JOIN `I_SG_AUDITORIAS_TIPOS` ON `I_SG_AUDITORIAS_TIPOS`.`ID` = `I_EC_AUDITORIAS`.`TIPO_AUDITORIA` INNER JOIN `I_SG_AUDITORIA_STATUS` ON `I_SG_AUDITORIA_STATUS`.`ID` = `I_EC_AUDITORIAS`.`STATUS_AUDITORIA` WHERE `I_EC_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die(); 

for ($i=0; $i < count($valores) ; $i++) { 
	$valores[$i]["SITIOS_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_SITIOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"]]]);
	$consulta = "SELECT 
	`I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`,
    `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`,
    `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`,
    `CLIENTES_DOMICILIOS`.`NOMBRE_DOMICILIO`, 
    `TIPOS_SERVICIO`.`NOMBRE`,
    `I_EC_AUDITORIAS`.`DURACION_DIAS`,
    `I_META_SITIOS`.`NOMBRE` AS `NOMBRE_META_SITIOS`,
	`I_META_SITIOS`.`TIPO` AS `TIPO`,
    `I_EC_SITIOS`.`VALOR`
     
FROM 
	`I_SG_AUDITORIA_SITIOS` 
    INNER JOIN `I_EC_SITIOS` ON `I_EC_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`
    INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` 
    INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO`
    INNER JOIN `CLIENTES_DOMICILIOS` ON `CLIENTES_DOMICILIOS`.`ID`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`
    INNER JOIN `I_EC_AUDITORIAS` ON `I_EC_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=`I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_EC_AUDITORIAS`.`TIPO_AUDITORIA` = `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA` AND `I_EC_AUDITORIAS`.`CICLO` = `I_SG_AUDITORIA_SITIOS`.`CICLO`
    INNER JOIN `I_META_SITIOS` ON `I_META_SITIOS`.`ID` = `I_EC_SITIOS`.`ID_META_SITIOS`
 WHERE
 	`I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"]."
    AND `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]."
    AND `I_SG_AUDITORIA_SITIOS`.`CICLO`=".$valores[$i]["CICLO"]."
  ORDER BY `I_EC_SITIOS`.`ID_CLIENTE_DOMICILIO`";
$valores1 = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);	
valida_error_medoo_and_die(); 
$sitios = array();
$l=0;$k=0;
for($j=0;$j<count($valores1);$j++){
	if($j==0){
		$k=0;
		$sitios[$l]["ID_SERVICIO_CLIENTE_ETAPA"] = $valores1[$j]["ID_SERVICIO_CLIENTE_ETAPA"];
		$sitios[$l]["TIPO_AUDITORIA"] = $valores1[$j]["TIPO_AUDITORIA"];
		$sitios[$l]["ID_CLIENTE_DOMICILIO"] = $valores1[$j]["ID_CLIENTE_DOMICILIO"];
		$sitios[$l]["NOMBRE_DOMICILIO"] = $valores1[$j]["NOMBRE_DOMICILIO"];
		$sitios[$l]["NOMBRE"] = $valores1[$j]["NOMBRE"];
		$sitios[$l]["DURACION_DIAS"] = $valores1[$j]["DURACION_DIAS"];
		$sitios[$l]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores1[$j]["NOMBRE_META_SITIOS"];
		$sitios[$l]["DATOS"][$k]["VALOR"]=$valores1[$j]["VALOR"];
		$sitios[$l]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores1[$j]["TIPO"];
		//$i++;
	}
	else{
		if($valores1[$j]["ID_CLIENTE_DOMICILIO"]!=$sitios[$l]["ID_CLIENTE_DOMICILIO"]){
			$l++;
			$sitios[$l]["ID_SERVICIO_CLIENTE_ETAPA"] = $valores1[$j]["ID_SERVICIO_CLIENTE_ETAPA"];
		$sitios[$l]["TIPO_AUDITORIA"] = $valores1[$j]["TIPO_AUDITORIA"];
		$sitios[$l]["ID_CLIENTE_DOMICILIO"] = $valores1[$j]["ID_CLIENTE_DOMICILIO"];
		$sitios[$l]["NOMBRE_DOMICILIO"] = $valores1[$j]["NOMBRE_DOMICILIO"];
		$sitios[$l]["NOMBRE"] = $valores1[$j]["NOMBRE"];
		$sitios[$l]["DURACION_DIAS"] = $valores1[$j]["DURACION_DIAS"];
		$sitios[$l]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores1[$j]["NOMBRE_META_SITIOS"];
		$sitios[$l]["DATOS"][$k]["VALOR"]=$valores1[$j]["VALOR"];
		$sitios[$l]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores1[$j]["TIPO"];
		}
		else{
			$k++;
			$sitios[$l]["DATOS"][$k]["NOMBRE_META_SITIOS"] = $valores1[$j]["NOMBRE_META_SITIOS"];
			$sitios[$l]["DATOS"][$k]["VALOR"]=$valores1[$j]["VALOR"];
			$sitios[$l]["DATOS"][$k]["TIPO_META_SITIOS"] = $valores1[$j]["TIPO"];
		}
	}
}
$valores[$i]["SITIOS"] = $sitios;
	///////////////////////////////////////////////////////////
	$valores[$i]["AUDITORIA_FECHAS"] = $database->query("SELECT `I_SG_AUDITORIA_FECHAS`.`ID`,`I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_FECHAS`.`FECHA` FROM `I_SG_AUDITORIA_FECHAS` WHERE `I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_FECHAS`.`CICLO`=".$valores[$i]["CICLO"]." ORDER BY `I_SG_AUDITORIA_FECHAS`.`FECHA`")->fetchAll(PDO::FETCH_ASSOC);
	///////////////////////////////////////////////////////////
	$valores[$i]["AUDITORES_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"]]]); 
	$valores[$i]["AUDITORES"] = $database->query("SELECT
	`I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`,
	`I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`,
    `I_SG_AUDITORIA_GRUPOS`.`CICLO`,
    `PERSONAL_TECNICO`.`NOMBRE`,
    `PERSONAL_TECNICO`.`APELLIDO_MATERNO`,
    `PERSONAL_TECNICO`.`APELLIDO_PATERNO`,
    `PERSONAL_TECNICO`.`EMAIL`,
	`PERSONAL_TECNICO_ROLES`.`ACRONIMO`,
    `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_ROL`,
    `TIPOS_SERVICIO`.`NOMBRE` AS `NOMBRE_SERVICIO`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  
FROM 
	`I_SG_AUDITORIA_GRUPOS` 
    INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`
	INNER JOIN	`PERSONAL_TECNICO_ROLES` ON `PERSONAL_TECNICO_ROLES`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_ROL`	
    INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA` 
    INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` 
    INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID`= `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`
    INNER JOIN `I_EC_AUDITORIAS` ON `I_EC_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=`I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_EC_AUDITORIAS`.`TIPO_AUDITORIA` = `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA` AND `I_EC_AUDITORIAS`.`CICLO` = `I_SG_AUDITORIA_GRUPOS`.`CICLO` 
WHERE `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPOS`.`CICLO`=".$valores[$i]["CICLO"])->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die(); 
	
	for($j=0;$j<$valores[$i]["AUDITORES_ASOCIADOS"];$j++){
		
		$valores[$i]["AUDITORES_FECHAS"][$valores[$i]["AUDITORES"][$j]["ID_PERSONAL_TECNICO_CALIF"]] = $database->query("SELECT `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID`,`I_SG_AUDITORIA_GRUPO_FECHAS`.`FECHA` FROM `I_SG_AUDITORIA_GRUPO_FECHAS` WHERE `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`CICLO`=".$valores[$i]["CICLO"]." AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID_PERSONAL_TECNICO_CALIF`=".$valores[$i]["AUDITORES"][$j]["ID_PERSONAL_TECNICO_CALIF"])->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die(); 
	}
	
	/**/
	/*======================================================*/
	//CODIGO PARA RESTRICCIONES
	/*
	
	// RESTRICCIONES PARA GRUPOS
	$valores[$i]["RESTRICCIONES_GRUPOS"] = array();
	//$valores[$i]["RESTRICCIONES_DIA_AUDITOR"] =  array();
	$tiene_auditor_lider = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND" => ["ID_SERVICIO_CLIENTE_ETAPA"=> $id,"TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"], "ID_ROL"=>"AL"]]); 
	valida_error_medoo_and_die(); 

	// Restricci�n de auditor l�der
	if ($tiene_auditor_lider == 0) {
		$mensaje_restriccion = "- Debe estar asignado un lider";
		array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}
	
	// Recupera los auditores asignados a la auditor�a menos a los auditores que no descuentan dias	
	$grupo_auditores = $database->select("I_SG_AUDITORIA_GRUPOS",["[><]PERSONAL_TECNICO_ROLES"=>["I_SG_AUDITORIA_GRUPOS.ID_ROL"=>"ID"]] ,["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA","I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA","I_SG_AUDITORIA_GRUPOS.CICLO","I_SG_AUDITORIA_GRUPOS.ID_PERSONAL_TECNICO_CALIF","I_SG_AUDITORIA_GRUPOS.ID_ROL"], ["AND"=>["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"],"PERSONAL_TECNICO_ROLES.DESC_DIAS"=>1]]); 
	valida_error_medoo_and_die(); 
	$valores[$i]["DIAS_ASIGNADOS"] = 0;

	for ($j=0; $j < count($grupo_auditores) ; $j++) { 
		$grupo_auditores_fechas = $database->select("I_SG_AUDITORIA_GRUPO_FECHAS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$grupo_auditores[$j]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$grupo_auditores[$j]["TIPO_AUDITORIA"],"CICLO"=>$grupo_auditores[$j]["CICLO"],"ID_PERSONAL_TECNICO_CALIF"=>$grupo_auditores[$j]["ID_PERSONAL_TECNICO_CALIF"]]]); 
		valida_error_medoo_and_die(); 
		$grupo_auditores[$j]["FECHAS_ASIGNADAS"] = $grupo_auditores_fechas;
		$valores[$i]["DIAS_ASIGNADOS"] += count($grupo_auditores_fechas);
	}
	
	// Restriccion de dias auditor
	if ($valores[$i]["DIAS_ASIGNADOS"] < $valores[$i]["DURACION_DIAS"]) {
		$mensaje_restriccion = "Faltan dias por asignar (se han asignado ".$valores[$i]["DIAS_ASIGNADOS"].' de '. $valores[$i]["DURACION_DIAS"].')';
		$valores[$i]["RESTRICCIONES_DIA_AUDITOR"] = $mensaje_restriccion;
		
	}

	$sg_sectores = $database->select("I_SG_SECTORES", "ID_SECTOR", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id]); 
	$ids_pt_califs = $database->select("I_SG_AUDITORIA_GRUPOS", "ID_PERSONAL_TECNICO_CALIF", ["AND"=>["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"]]]);
	$sectores_calificados = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["ID_PERSONAL_TECNICO_CALIFICACION"=>$ids_pt_califs]);

	
	// Restricci�n de calificacion del grupo auditor
	if(( $valores[$i]["TIPO_AUDITORIA"] == "3"|| $valores[$i]["TIPO_AUDITORIA"] == "4" ) && ($sectores_calificados == NULL || count(array_diff($sg_sectores, $sectores_calificados)) > 0)){	
		$mensaje_restriccion = "- El grupo no esta calificado en todos los sectores";
		array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}
	//Aqui se chequean si los auditores estan capacitados en todos los sectores si tipo auditoria es vigilancia anual 2
	if($valores[$i]["TIPO_AUDITORIA"] == "7"){
		$ids_pt_califs1 = $database->select("I_SG_AUDITORIA_GRUPOS", "ID_PERSONAL_TECNICO_CALIF", ["AND"=>
																										["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,
																										"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> [6,7],
																										"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"]]]);
		
		$sectores_calificados1 = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["ID_PERSONAL_TECNICO_CALIFICACION"=>$ids_pt_califs1]);
		if($sectores_calificados1 == NULL || count(array_diff($sg_sectores, $sectores_calificados1)) > 0){
			$mensaje_restriccion = "- El grupo no esta calificado en todos los sectores";
			array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
		}
	}
	//Aqui se chequean si los auditores estan capacitados en todos los sectores si tipo auditoria es vigilancia semestral 5+
	if($valores[$i]["TIPO_AUDITORIA"] == "12"){
		$ids_pt_califs1 = $database->select("I_SG_AUDITORIA_GRUPOS", "ID_PERSONAL_TECNICO_CALIF", ["AND"=>
																										["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,
																										"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> [8,9,10,11,12],
																										"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"]]]);
		
		$sectores_calificados1 = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["ID_PERSONAL_TECNICO_CALIFICACION"=>$ids_pt_califs1]);
		if($sectores_calificados1 == NULL || count(array_diff($sg_sectores, $sectores_calificados1)) > 0){
			$mensaje_restriccion = "- El grupo no esta calificado en todos los sectores";
			array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
		}
	}
	//RESTRICCIONES PARA SITIOS (REGLAS DE MUESTREO
	//Sitio Matriz
	$mensaje_actividad_faltante = "";
	$valores[$i]["RESTRICCIONES_SITIOS"] = array();
	$const_sitio = 1;
	if($valores[$i]["TIPO_AUDITORIA"] > "5" && $valores[$i]["TIPO_AUDITORIA"] < "13"){
		$const_sitio = 0.6;
	}
	$SITIO_MATRIZ =  $database->query("SELECT COUNT(*) AS COUNT_MATRIZ FROM `I_SG_AUDITORIA_SITIOS` AS SAS INNER JOIN `I_SG_SITIOS` AS SGS ON SAS.`ID_SERVICIO_CLIENTE_ETAPA` = SGS.`ID_SERVICIO_CLIENTE_ETAPA` AND SAS.`ID_CLIENTE_DOMICILIO` = SGS.`ID_CLIENTE_DOMICILIO` WHERE SGS.`MATRIZ_PRINCIPAL` = 'si' AND SAS.`ID_SERVICIO_CLIENTE_ETAPA`=".$id. " AND SAS.`TIPO_AUDITORIA` = ".$valores[$i]["TIPO_AUDITORIA"]." AND SAS.`CICLO`=".$valores[$i]["CICLO"])->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die(); 
	if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
		$mensaje_actividad_faltante = "Falta sitio matriz. ";
	}
	if(($valores[$i]["TIPO_AUDITORIA"] > "5" && $valores[$i]["TIPO_AUDITORIA"] < "13") ||  $valores[$i]["TIPO_AUDITORIA"] == "3"|| $valores[$i]["TIPO_AUDITORIA"] == "4" ){
		if($valores[$i]["NO_USA_METODO"] == 0){
	
			$SITIO_TOTAL_ACTIVIDAD = $database->query("SELECT COUNT(*) AS COUNT_ACTIVIDAD, SGS.`ID_ACTIVIDAD`, SAC.`ACTIVIDAD` AS ACTIVIDAD FROM `I_SG_SITIOS` AS SGS INNER JOIN `SG_ACTIVIDAD` AS SAC ON SGS.`ID_ACTIVIDAD` = SAC.`ID` WHERE SGS.`MATRIZ_PRINCIPAL` = 'no' AND SGS.`ID_SERVICIO_CLIENTE_ETAPA`=".$id." GROUP BY SGS.`ID_ACTIVIDAD`")->fetchAll(PDO::FETCH_ASSOC);	
			$valores[$i]["TOTAL_SITIOS"] = 1; //el sitio matriz
		
			$SITIO_AUX_ACTIVIDAD = $database->query("SELECT COUNT(*) AS COUNT_ACTIVIDAD, SGS.`ID_ACTIVIDAD` FROM `I_SG_AUDITORIA_SITIOS` AS SAS INNER JOIN `I_SG_SITIOS` AS SGS ON  SAS.`ID_SERVICIO_CLIENTE_ETAPA` = SGS.`ID_SERVICIO_CLIENTE_ETAPA` AND SAS.`ID_CLIENTE_DOMICILIO` = SGS.`ID_CLIENTE_DOMICILIO` WHERE SGS.`MATRIZ_PRINCIPAL` = 'no' AND SAS.`ID_SERVICIO_CLIENTE_ETAPA`=".$id." AND SAS.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND SAS.`CICLO`=".$valores[$i]["CICLO"]." GROUP BY SGS.`ID_ACTIVIDAD`")->fetchAll(PDO::FETCH_ASSOC);	
			$SITIO_ASOCIADO_ACTIVIDAD = array();
			foreach ($SITIO_AUX_ACTIVIDAD as $key => $actividad) {
				$SITIO_ASOCIADO_ACTIVIDAD[$actividad["ID_ACTIVIDAD"]] = $actividad["COUNT_ACTIVIDAD"];
			}
			// Restricción de raiz cuadrada de total de sitios por actividad
			foreach ($SITIO_TOTAL_ACTIVIDAD as $key => $actividad) {
				$aux_actividad = ceil(sqrt($actividad["COUNT_ACTIVIDAD"]) * $const_sitio);
				$valores[$i]["TOTAL_SITIOS"] += $aux_actividad;
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
			$valores[$i]["TOTAL_SITIOS"] = $valores[$i]["SITIOS_AUDITAR"]; 
		}
		// Restricciones de sitios
		if ($valores[$i]["SITIOS_ASOCIADOS"] < $valores[$i]["TOTAL_SITIOS"]) {
			$mensaje_restriccion = '- Se deben cubrir '.$valores[$i]["TOTAL_SITIOS"].' sitios en esta auditoria. '.$mensaje_actividad_faltante;
			array_push($valores[$i]["RESTRICCIONES_SITIOS"], $mensaje_restriccion);
		}
	
	}
	else{
		// Restricciones de sitios
		if($SITIO_MATRIZ[0]["COUNT_MATRIZ"] == 0){
			$mensaje_restriccion = '- '.$mensaje_actividad_faltante;
			array_push($valores[$i]["RESTRICCIONES_SITIOS"], $mensaje_restriccion);
		}
	}
	*/
	/*======================================================*/
	/*======================================================*/
	//Aqui buscamos el estado de la auditoria en dictaminacion
	if($database->count("DICTAMINACIONES", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $id,"TIPO_AUDITORIA" => $valores[$i]["TIPO_AUDITORIA"], "CICLO" => $valores[$i]["CICLO"] ]]) == 0){
		$valores[$i]["ESTADO_DICTAMINACION"] = "Pendiente Solicitud";
	}
	else{
		$valores[$i]["ESTADO_DICTAMINACION"] = $database->get("DICTAMINACIONES","STATUS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $id,"TIPO_AUDITORIA" => $valores[$i]["TIPO_AUDITORIA"], "CICLO" => $valores[$i]["CICLO"] ]]);
	}
}


print_r(json_encode($valores)); 
?> 
