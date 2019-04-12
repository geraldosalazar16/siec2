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

$ID_SERVICIO = $database->get('SERVICIO_CLIENTE_ETAPA','ID_SERVICIO',['ID'=>$id]);
valida_error_medoo_and_die();

if($ID_SERVICIO == 1){
	$valores['AUDITORIAS'] = $database->query("
	SELECT 
	`I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`,
	`I_SG_AUDITORIAS_TIPOS`.`TIPO`,
	`I_SG_AUDITORIAS`.`TIPO_AUDITORIA`,
    `I_SG_AUDITORIAS`.`CICLO`
   FROM `I_SG_AUDITORIAS` 
   INNER JOIN `I_SG_AUDITORIAS_TIPOS` ON `I_SG_AUDITORIAS_TIPOS`.`ID` = `I_SG_AUDITORIAS`.`TIPO_AUDITORIA` 
   WHERE `I_SG_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=".$id." ORDER BY `I_SG_AUDITORIAS`.`CICLO`,`I_SG_AUDITORIAS`.`TIPO_AUDITORIA` ASC")->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die(); 
}
if($ID_SERVICIO == 2 || $ID_SERVICIO == 4){
	$valores['AUDITORIAS'] = $database->query("
	SELECT 
	`I_EC_AUDITORIAS_TIPOS`.`TIPO`,
	`I_EC_AUDITORIAS`.`TIPO_AUDITORIA`,
    `I_EC_AUDITORIAS`.`CICLO`
   FROM `I_EC_AUDITORIAS` 
   INNER JOIN `I_SG_AUDITORIAS_TIPOS` ON `I_SG_AUDITORIAS_TIPOS`.`ID` = `I_EC_AUDITORIAS`.`TIPO_AUDITORIA` 
   WHERE `I_EC_AUDITORIAS`.`ID_SERVICIO_CLIENTE_ETAPA`=".$id)." ORDER BY `I_EC_AUDITORIAS`.`CICLO`,`I_EC_AUDITORIAS`.`TIPO_AUDITORIA` ASC"->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
}

$valores["TOTAL_VIATICOS"] = $database->sum('I_AUDITORIAS_VIATICOS','MONTO',['ID_SERVICIO_CLIENTE_ETAPA'=>$id]); //TOTAL VIATICOS DE TODOS LOS TIPOS DE AUDITORIA DEL SERVICIO
$valores["TOTAL_GASTOS"] = $database->sum('I_AUDITORIAS_COSTOS','MONTO',['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id,'ID_CAT_AUDITORIAS_COSTOS[!]'=>[4,6]]]);	//TOTAL GASTOS DE TODOS LOS TIPOS DE AUDITORIA DEL SERVICIO
// AQUI BUSCO LOS GASTOS DEFINIDOS POR CATALOGO
$catalogo = $database->select('I_CAT_AUDITORIAS_COSTOS',['ID','NOMBRE'],['ORDER'=>'ID']);
valida_error_medoo_and_die();

for ($i=0; $i < count($valores['AUDITORIAS']) ; $i++) { 
	// AQUI BUSCO EL VIATICO PARA ESTE id_sce,id_tipo_auditoria,cilo
	$valores['AUDITORIAS'][$i]["TOTAL_VIATICOS"] = $database->sum('I_AUDITORIAS_VIATICOS','MONTO',['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id,'ID_TIPO_AUDITORIA'=>$valores['AUDITORIAS'][$i]['TIPO_AUDITORIA'],'CICLO'=>$valores['AUDITORIAS'][$i]['CICLO']]]);
	// AQUI BUSCO EL GASTO TOTAL PARA ESTE id_sce,id_tipo_auditoria,ciclo
	$valores['AUDITORIAS'][$i]["TOTAL_GASTOS"] = $database->sum('I_AUDITORIAS_COSTOS','MONTO',['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id,'ID_TIPO_AUDITORIA'=>$valores['AUDITORIAS'][$i]['TIPO_AUDITORIA'],'CICLO'=>$valores['AUDITORIAS'][$i]['CICLO'],'ID_CAT_AUDITORIAS_COSTOS[!]'=>[4,6]]]);
	// AQUI BUSCO LOS AUDITORES POR AUDITORIA
	$valores['AUDITORIAS'][$i]["AUDITORES"] =  $database->query("SELECT
	`PERSONAL_TECNICO`.`NOMBRE`,
    `PERSONAL_TECNICO`.`APELLIDO_MATERNO`,
    `PERSONAL_TECNICO`.`APELLIDO_PATERNO`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_ROL`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  
FROM 
	`I_SG_AUDITORIA_GRUPOS` 
    INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  
    INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID`= `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`
    WHERE `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`=".$valores['AUDITORIAS'][$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPOS`.`CICLO`=".$valores['AUDITORIAS'][$i]["CICLO"]." AND `I_SG_AUDITORIA_GRUPOS`.`ID_ROL` != 6")->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
	for($j=0;$j<count($valores['AUDITORIAS'][$i]["AUDITORES"]);$j++){
		$valores['AUDITORIAS'][$i]["AUDITORES"][$j]['TOTAL_AUDITOR'] = 0;
		$valores['AUDITORIAS'][$i]["AUDITORES"][$j]['TOTAL_AUDITOR_SIN_IVA'] = 0;
		for($k=0;$k<count($catalogo);$k++){
			$a =0;
			$a = $database->sum(
			'I_AUDITORIAS_COSTOS',
			'MONTO',
			['AND'=>
				['ID_SERVICIO_CLIENTE_ETAPA'=>$id,
				'ID_TIPO_AUDITORIA'=>$valores['AUDITORIAS'][$i]['TIPO_AUDITORIA'],
				'CICLO'=>$valores['AUDITORIAS'][$i]['CICLO'],
				'ID_PERSONAL_TECNICO_CALIF'=>$valores['AUDITORIAS'][$i]['AUDITORES'][$j]['ID_PERSONAL_TECNICO_CALIF'],
				'ID_CAT_AUDITORIAS_COSTOS'=>$catalogo[$k]['ID']]
				]);
				valida_error_medoo_and_die();
				
			$valores['AUDITORIAS'][$i]["AUDITORES"][$j]['MONTO'][$k]['VALOR']= $a;
			if($catalogo[$k]['ID'] != 4 && $catalogo[$k]['ID'] != 6){
				$valores['AUDITORIAS'][$i]["AUDITORES"][$j]['TOTAL_AUDITOR'] +=$a;
			}
			if($catalogo[$k]['ID'] != 3 && $catalogo[$k]['ID'] != 5){
				$valores['AUDITORIAS'][$i]["AUDITORES"][$j]['TOTAL_AUDITOR_SIN_IVA'] +=$a;
			}	
			
		}
	}
	
	// AQUI BUSCO LOS EXPERTOS TECNICOS POR AUDITORIA
	$valores['AUDITORIAS'][$i]["EXP_TECNICOS"] =  $database->query("SELECT
	`PERSONAL_TECNICO`.`NOMBRE`,
    `PERSONAL_TECNICO`.`APELLIDO_MATERNO`,
    `PERSONAL_TECNICO`.`APELLIDO_PATERNO`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_ROL`,
    `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  
FROM 
	`I_SG_AUDITORIA_GRUPOS` 
    INNER JOIN `PERSONAL_TECNICO_CALIFICACIONES` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID` = `I_SG_AUDITORIA_GRUPOS`.`ID_PERSONAL_TECNICO_CALIF`  
    INNER JOIN `PERSONAL_TECNICO` ON `PERSONAL_TECNICO`.`ID`= `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`
    WHERE `I_SG_AUDITORIA_GRUPOS`.`ID_SERVICIO_CLIENTE_ETAPA`= ".$id. " AND `I_SG_AUDITORIA_GRUPOS`.`TIPO_AUDITORIA`=".$valores['AUDITORIAS'][$i]["TIPO_AUDITORIA"]." AND `I_SG_AUDITORIA_GRUPOS`.`CICLO`=".$valores['AUDITORIAS'][$i]["CICLO"]." AND `I_SG_AUDITORIA_GRUPOS`.`ID_ROL` = 6")->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die();
	for($j=0;$j<count($valores['AUDITORIAS'][$i]["EXP_TECNICOS"]);$j++){
		$valores['AUDITORIAS'][$i]["EXP_TECNICOS"][$j]['TOTAL_AUDITOR'] = 0;
		$valores['AUDITORIAS'][$i]["EXP_TECNICOS"][$j]['TOTAL_AUDITOR_SIN_IVA'] = 0;
		for($k=0;$k<count($catalogo);$k++){
			$a =0;
			$a = $database->sum(
			'I_AUDITORIAS_COSTOS',
			'MONTO',
			['AND'=>
				['ID_SERVICIO_CLIENTE_ETAPA'=>$id,
				'ID_TIPO_AUDITORIA'=>$valores['AUDITORIAS'][$i]['TIPO_AUDITORIA'],
				'CICLO'=>$valores['AUDITORIAS'][$i]['CICLO'],
				'ID_PERSONAL_TECNICO_CALIF'=>$valores['AUDITORIAS'][$i]['EXP_TECNICOS'][$j]['ID_PERSONAL_TECNICO_CALIF'],
				'ID_CAT_AUDITORIAS_COSTOS'=>$catalogo[$k]['ID']]
				]);
				valida_error_medoo_and_die();
				
			$valores['AUDITORIAS'][$i]["EXP_TECNICOS"][$j]['MONTO'][$k]['VALOR']= $a;
			if($catalogo[$k]['ID'] != 4 && $catalogo[$k]['ID'] != 6){
				$valores['AUDITORIAS'][$i]["EXP_TECNICOS"][$j]['TOTAL_AUDITOR'] +=$a;
			}
			if($catalogo[$k]['ID'] != 3 && $catalogo[$k]['ID'] != 5){
				$valores['AUDITORIAS'][$i]["EXP_TECNICOS"][$j]['TOTAL_AUDITOR_SIN_IVA'] +=$a;
			}	
			
		}
	}
}





print_r(json_encode($valores)); 
?> 
