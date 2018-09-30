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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 
$id = $_REQUEST["id"]; 

											
$valores = $database->query("SELECT `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIAS`.`DURACION_DIAS`, `I_SG_AUDITORIAS_TIPOS`.`TIPO`,`I_SG_AUDITORIA_STATUS`.`STATUS`,`I_SG_AUDITORIAS`.`NO_USA_METODO`,`I_SG_AUDITORIAS`.`SITIOS_AUDITAR`,`I_SG_AUDITORIAS`.`ID_USUARIO_CREACION`,`I_SG_AUDITORIAS`.`ID_USUARIO_MODIFICACION`,`I_SG_AUDITORIAS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIAS`.`STATUS_AUDITORIA`,`I_SG_AUDITORIAS`.`CICLO` FROM `I_SG_AUDITORIAS` INNER JOIN `I_SG_AUDITORIAS_TIPOS` ON `I_SG_AUDITORIAS_TIPOS`.`ID` = `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` INNER JOIN `I_SG_AUDITORIA_STATUS` ON `I_SG_AUDITORIA_STATUS`.`ID` = `I_SG_AUDITORIAS`.`STATUS_AUDITORIA` WHERE `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die(); 

for ($i=0; $i < count($valores) ; $i++) { 
	$valores[$i]["SITIOS_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_SITIOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"]]]);
	$valores[$i]["SITIOS"] = $database->query("SELECT `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`,`CLIENTES_DOMICILIOS`.`NOMBRE_DOMICILIO`, `TIPOS_SERVICIO`.`NOMBRE`,`I_SG_AUDITORIAS`.`DURACION_DIAS`,  `I_SG_SITIOS`.`CANTIDAD_PERSONAS`,`I_SG_SITIOS`.`CANTIDAD_TURNOS`,`I_SG_SITIOS`.`NUMERO_TOTAL_EMPLEADOS`,`I_SG_SITIOS`.`NUMERO_EMPLEADOS_CERTIFICACION`,`I_SG_SITIOS`.`CANTIDAD_DE_PROCESOS`,`I_SG_SITIOS`.`ID_ACTIVIDAD`,`I_SG_SITIOS`.`TEMPORAL_O_FIJO`,`I_SG_SITIOS`.`MATRIZ_PRINCIPAL` FROM `I_SG_AUDITORIA_SITIOS` INNER JOIN `I_SG_SITIOS` ON `I_SG_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_SG_SITIOS`.`ID_CLIENTE_DOMICILIO`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO` INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` INNER JOIN `CLIENTES_DOMICILIOS` ON `CLIENTES_DOMICILIOS`.`ID`= `I_SG_AUDITORIA_SITIOS`.`ID_CLIENTE_DOMICILIO`INNER JOIN `I_SG_AUDITORIAS` ON `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=`I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` = `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA` WHERE `I_SG_AUDITORIA_SITIOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_SITIOS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_SITIOS`.`CICLO`=".$valores[$i]["CICLO"])->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die(); 
	///////////////////////////////////////////////////////////
	$valores[$i]["AUDITORIA_FECHAS"] = $database->query("SELECT `I_SG_AUDITORIA_FECHAS`.`ID`,`I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_FECHAS`.`FECHA` FROM `I_SG_AUDITORIA_FECHAS` WHERE `I_SG_AUDITORIA_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_FECHAS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_FECHAS`.`CICLO`=".$valores[$i]["CICLO"]." ORDER BY `I_SG_AUDITORIA_FECHAS`.`FECHA`")->fetchAll(PDO::FETCH_ASSOC);
	///////////////////////////////////////////////////////////
	$valores[$i]["AUDITORES_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"]]]); 
	$valores[$i]["AUDITORES"] = $database->query("SELECT `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`,`I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`,`I_SG_AUDITORIA_GRUPOS`.`CICLO`,`PERSONAL_TECNICO`.`NOMBRE`,`PERSONAL_TECNICO`.`APELLIDO_MATERNO`,`PERSONAL_TECNICO`.`APELLIDO_PATERNO`,`PERSONAL_TECNICO`.`EMAIL`,`PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO`,`I_SG_AUDITORIA_GRUPOS`.`ID_ROL`,`TIPOS_SERVICIO`.`NOMBRE` AS `NOMBRE_SERVICIO`,`I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  FROM `I_SG_AUDITORIA_GRUPOS` INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  INNER JOIN `SERVICIO_CLIENTE_ETAPA` ON `SERVICIO_CLIENTE_ETAPA`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA` INNER JOIN `TIPOS_SERVICIO` ON `TIPOS_SERVICIO`.`ID` = `SERVICIO_CLIENTE_ETAPA`.`ID_TIPO_SERVICIO` INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID`= `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`INNER JOIN `I_SG_AUDITORIAS` ON `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=`I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA` AND `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` = `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA` WHERE `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPOS`.`CICLO`=".$valores[$i]["CICLO"])->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die(); 
	
	for($j=0;$j<$valores[$i]["AUDITORES_ASOCIADOS"];$j++){
		
		$valores[$i]["AUDITORES_FECHAS"][$valores[$i]["AUDITORES"][$j]["ID_PERSONAL_TECNICO_CALIF"]] = $database->query("SELECT `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID`,`I_SG_AUDITORIA_GRUPO_FECHAS`.`FECHA` FROM `I_SG_AUDITORIA_GRUPO_FECHAS` WHERE `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`TIPO_AUDITORIA`=".$valores[$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`CICLO`=".$valores[$i]["CICLO"]." AND `I_SG_AUDITORIA_GRUPO_FECHAS`.`ID_PERSONAL_TECNICO_CALIF`=".$valores[$i]["AUDITORES"][$j]["ID_PERSONAL_TECNICO_CALIF"])->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die(); 
	}
	
	/*======================================================*/
	//CODIGO PARA RESTRICCIONES
	
	//	REGLA DONDE DIA_AUDITOR=CANT_FECHAS*CANT_AUDITORES
	//$valores[$i]["FECHAS_AUDITORES_ASOCIADOS"] = $database->count("I_SG_AUDITORIA_GRUPO_FECHAS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$valores[$i]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"]]]); 
/*	if(count($valores[$i]["AUDITORIA_FECHAS"])*count($valores[$i]["AUDITORES"])!=$valores[$i]["DURACION_DIAS"]){
		$valores[$i]["REGLA_DIA_AUDITOR"] = "- cantidad de fechas (".count($valores[$i]["AUDITORIA_FECHAS"]).") * cantidad de auditores (".count($valores[$i]["AUDITORES"]).") debe ser igual a dia auditor (".$valores[$i]["DURACION_DIAS"].")";
	}
	else{
		$valores[$i]["REGLA_DIA_AUDITOR"]="";
	}
	*/
	// RESTRICCIONES PARA GRUPOS
	$valores[$i]["RESTRICCIONES_GRUPOS"] = array();
	//$valores[$i]["RESTRICCIONES_DIA_AUDITOR"] =  array();
	$tiene_auditor_lider = $database->count("I_SG_AUDITORIA_GRUPOS", ["AND" => ["ID_SERVICIO_CLIENTE_ETAPA"=> $id,"TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"], "ID_ROL"=>"TECL"]]); 
	valida_error_medoo_and_die(); 

	// Restricci�n de auditor l�der
	if ($tiene_auditor_lider == 0) {
		$mensaje_restriccion = "- Debe estar asignado un lider";
		//$valores[$i]["RESTRICCIONES_GRUPOS"]= $mensaje_restriccion;
		array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}
	
	// Recupera los auditores asignados a la auditor�a menos a los expertos t�nicos (ExTec), los ExTec no cuentan en la restricci�n de d�as	
	//$grupo_auditores = $database->select("I_SG_AUDITORIA_GRUPOS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=> $id,"TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"CICLO"=>$valores[$i]["CICLO"], "ID_ROL[!]"=>"ExTec"]]); 
	$grupo_auditores = $database->select("I_SG_AUDITORIA_GRUPOS",["[><]PERSONAL_TECNICO_ROLES"=>["I_SG_AUDITORIA_GRUPOS.ID_ROL"=>"ID"]] ,["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA","I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA","I_SG_AUDITORIA_GRUPOS.CICLO","I_SG_AUDITORIA_GRUPOS.ID_PERSONAL_TECNICO_CALIF","I_SG_AUDITORIA_GRUPOS.ID_ROL"], ["AND"=>["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"],"PERSONAL_TECNICO_ROLES.DESC_DIAS"=>1]]); 
	valida_error_medoo_and_die(); 
	$valores[$i]["DIAS_ASIGNADOS"] = 0;

	for ($j=0; $j < count($grupo_auditores) ; $j++) { 
		$grupo_auditores_fechas = $database->select("I_SG_AUDITORIA_GRUPO_FECHAS", "*", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$grupo_auditores[$j]["ID_SERVICIO_CLIENTE_ETAPA"],"TIPO_AUDITORIA"=>$grupo_auditores[$j]["TIPO_AUDITORIA"],"CICLO"=>$grupo_auditores[$j]["CICLO"],"ID_PERSONAL_TECNICO_CALIF"=>$grupo_auditores[$j]["ID_PERSONAL_TECNICO_CALIF"]]]); 
		valida_error_medoo_and_die(); 
		$grupo_auditores[$j]["FECHAS_ASIGNADAS"] = $grupo_auditores_fechas;
		$valores[$i]["DIAS_ASIGNADOS"] += count($grupo_auditores_fechas);
	}
	
	// Restricci�n de d�as auditor
	if ($valores[$i]["DIAS_ASIGNADOS"] < $valores[$i]["DURACION_DIAS"]) {
		$mensaje_restriccion = "Faltan dias por asignar (se han asignado ".$valores[$i]["DIAS_ASIGNADOS"].' de '. $valores[$i]["DURACION_DIAS"].')';
		$valores[$i]["RESTRICCIONES_DIA_AUDITOR"] = $mensaje_restriccion;
		//array_push($valores[$i]["RESTRICCIONES_DIA_AUDITOR"], $mensaje_restriccion);
	}

	$sg_sectores = $database->select("I_SG_SECTORES", "ID_SECTOR", ["ID_SERVICIO_CLIENTE_ETAPA"=>$id]); 
	$ids_pt_califs = $database->select("I_SG_AUDITORIA_GRUPOS", "ID_PERSONAL_TECNICO_CALIF", ["AND"=>["I_SG_AUDITORIA_GRUPOS.ID_SERVICIO_CLIENTE_ETAPA"=> $id,"I_SG_AUDITORIA_GRUPOS.TIPO_AUDITORIA"=> $valores[$i]["TIPO_AUDITORIA"],"I_SG_AUDITORIA_GRUPOS.CICLO"=>$valores[$i]["CICLO"]]]);
	$sectores_calificados = $database->select("PERSONAL_TECNICO_CALIF_SECTOR", "ID_SECTOR", ["ID_PERSONAL_TECNICO_CALIFICACION"=>$ids_pt_califs]);

	//print_r($sg_sectores);
	//print_r($ids_pt_califs);
	//print_r($sectores_calificados);
	//print_r(count(array_diff($sg_sectores,$sectores_calificados)));
	
	// Restricci�n de calificacion del grupo aditor
	if( $valores[$i]["TIPO_AUDITORIA"] != "E1" && ($sectores_calificados == NULL || count(array_diff($sg_sectores, $sectores_calificados)) > 0)){
		$mensaje_restriccion = "- El grupo no esta calificado en todos los sectores";
		array_push($valores[$i]["RESTRICCIONES_GRUPOS"], $mensaje_restriccion);
	}
	/*======================================================*/
	
}


print_r(json_encode($valores)); 
?> 
