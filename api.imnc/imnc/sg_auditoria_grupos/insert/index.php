<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';
include  '../../common/common_functions.php';   


$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$FECHAS_ASIGNADAS = $objeto->FECHAS_ASIGNADAS; // En formato dd/mm/yyyy separados por comas
valida_parametro_and_die($FECHAS_ASIGNADAS, "Es necesario asignarle fechas al auditor");
$FECHAS_ASIGNADAS = explode(", ", $FECHAS_ASIGNADAS);

$ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
valida_parametro_and_die($ID_SG_AUDITORIA, "Falta ID_SG_AUDITORIA");

$ID_PERSONAL_TECNICO_CALIF = $objeto->ID_PERSONAL_TECNICO_CALIF; 
valida_parametro_and_die($ID_PERSONAL_TECNICO_CALIF, "Es necesario seleccionar un auditor");
$otros_grupos_del_auditor = $database->select("SG_AUDITORIA_GRUPOS", "ID", ["ID_PERSONAL_TECNICO_CALIF"=>$ID_PERSONAL_TECNICO_CALIF]);
valida_error_medoo_and_die();

$arreglo_fechas_yyyymmdd = array();
for ($i=0; $i < count($FECHAS_ASIGNADAS) ; $i++) { 
	$fecha = str_replace("/", "-", $FECHAS_ASIGNADAS[$i]);
	$fecha = date("Ymd", strtotime($fecha));
	verifica_fecha_valida($fecha);

	// Validar que todas las fechas pertenezcan a la auditoria
	$existe_en_auditoria = $database->count("SG_AUDITORIA_FECHAS", ["AND"=>["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA,"FECHA"=>$fecha]]);
	valida_error_medoo_and_die();
	if (!$existe_en_auditoria) { 
		imprime_error_and_die("La fecha " . $FECHAS_ASIGNADAS[$i] . " no existe en la auditoría");
	}
	$consulta_servicio_actual = "SELECT SCE.ID_CLIENTE,SCE.SG_INTEGRAL FROM SG_AUDITORIAS AS SGA,SG_TIPOS_SERVICIO AS SGTS, SERVICIO_CLIENTE_ETAPA AS SCE WHERE SGA.ID = '".$ID_SG_AUDITORIA."' AND SGA.ID_SG_TIPO_SERVICIO = SGTS.ID AND SGTS.ID_SERVICIO_CLIENTE_ETAPA = SCE.ID";
	$servicio_actual = $database->query($consulta_servicio_actual)->fetchAll(PDO::FETCH_ASSOC);
	// Validar que el auditor esté libre para todas las fechas
	if (count($otros_grupos_del_auditor) > 0) {
		if($servicio_actual[0]["SG_INTEGRAL"] != "S"){
			$ocupado = $database->count("SG_AUDITORIA_GRUPO_FECHAS", ["AND"=>["ID_SG_AUDITORIA_GRUPO"=>$otros_grupos_del_auditor,"FECHA"=>$fecha]]);
			valida_error_medoo_and_die();
			if ($ocupado) { 
				imprime_error_and_die("No es integral. Este auditor tiene la fecha " . $FECHAS_ASIGNADAS[$i] . " asignada a otra auditoría.");
			}
		}else{
			$arreglo_grupos = "-1";
			for($j = 0 ; $j < count($otros_grupos_del_auditor);$j++){
				$arreglo_grupos .= ",".$otros_grupos_del_auditor[$j];
			}
			$consulta_ocupado = "SELECT count(SGAGF.ID) AS cantidad FROM SG_AUDITORIA_GRUPO_FECHAS AS SGAGF, SG_AUDITORIA_GRUPOS AS SGAG , SG_AUDITORIAS AS SGA, SG_TIPOS_SERVICIO AS SGTS, SERVICIO_CLIENTE_ETAPA AS SCE WHERE (SGAG.ID IN (".$arreglo_grupos.") AND  FECHA = ".$fecha." AND  SGAGF.ID_SG_AUDITORIA_GRUPO = SGAG.ID AND SGA.ID = SGAG.ID_SG_AUDITORIA AND SGTS.ID = SGA.ID_SG_TIPO_SERVICIO AND SCE.ID = SGTS.ID_SERVICIO_CLIENTE_ETAPA) AND NOT (SCE.SG_INTEGRAL = 'S' AND SCE.ID_CLIENTE = ".$servicio_actual[0]["ID_CLIENTE"].")";
			$res_ocupado = $database->query($consulta_ocupado)->fetchAll(PDO::FETCH_ASSOC);
			$ocupado = $res_ocupado[0]["cantidad"];
			valida_error_medoo_and_die();
			if ($ocupado) { 
				imprime_error_and_die("Este auditor tiene la fecha " . $FECHAS_ASIGNADAS[$i] . " asignada a otra auditoría.");
			}
		}
		
	}

	array_push($arreglo_fechas_yyyymmdd, $fecha);
	
}

$ID_ROL = $objeto->ID_ROL; 
valida_parametro_and_die($ID_ROL, "Es necesario seleccionar un rol");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id_sg_auditoria_grupo = $database->insert("SG_AUDITORIA_GRUPOS", 
[ 
	"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
	"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF, 
	"FECHA_INICIO" => "00000000", 
	"FECHA_FIN" => "00000000", 
	"ID_ROL" => $ID_ROL, 
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
]); 
valida_error_medoo_and_die(); 

// Inserta todas las fechas en SG_AUDITORIA_GRUPOS_FECHAS
for ($i=0; $i < count($arreglo_fechas_yyyymmdd) ; $i++) { 
	$id_sg_auditoria_grupo_fechas = $database->insert("SG_AUDITORIA_GRUPO_FECHAS", 
	[ 
		"ID_SG_AUDITORIA_GRUPO" => $id_sg_auditoria_grupo, 
		"FECHA" => $arreglo_fechas_yyyymmdd[$i],
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
	]); 
	valida_error_medoo_and_die(); 
}


$respuesta["resultado"]="ok"; 
$respuesta["id"]=$id_sg_auditoria_grupo; 
print_r(json_encode($respuesta)); 	



?> 
