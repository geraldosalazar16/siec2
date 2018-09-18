<?php  
 // error_reporting(E_ALL);
 // ini_set("display_errors",1);

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php'; 



$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
$FECHAS = $objeto->FECHAS; // En formato dd/mm/yyyy separados por comas

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");

$respuesta["resultado"]="ok"; 

$FECHAS = explode(", ", $FECHAS);
$array_fechas = array();

for ($i=0; $i < count($FECHAS) ; $i++) { 
	$fecha = str_replace("/", "-", $FECHAS[$i]);
	$fecha = date("Ymd", strtotime($fecha));
	verifica_fecha_valida($fecha);
	$existe_en_bd = $database->count("SG_AUDITORIA_FECHAS", ["AND"=>["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA,"FECHA"=>$fecha]]);
	valida_error_medoo_and_die();
	if (!$existe_en_bd && $FECHAS[$i] != "" && $FECHAS != "") { // insertar
		$id = $database->insert("SG_AUDITORIA_FECHAS", [ 
			"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
			"FECHA" => $fecha, 
			"FECHA_CREACION" => $FECHA_MODIFICACION,
			"HORA_CREACION" => $HORA_MODIFICACION,
			"ID_USUARIO_CREACION" => $ID_USUARIO_MODIFICACION
		]);
		valida_error_medoo_and_die(); 
	}
	array_push($array_fechas, $fecha);
}

$fechas_a_eliminar = $database->select("SG_AUDITORIA_FECHAS", "*", ["AND"=>["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA,"FECHA[!]"=>$array_fechas]]);
valida_error_medoo_and_die();
//print_r($fechas_a_eliminar);
for ($i=0; $i < count($fechas_a_eliminar) ; $i++) { 
	$auditor_asignado = $database->count("SG_AUDITORIA_GRUPOS",
		["AND"=>
			[
				"ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA, 
				"FECHA_INICIO[<=]"=>$fechas_a_eliminar[$i]["FECHA"],
				"FECHA_FIN[>=]"=>$fechas_a_eliminar[$i]["FECHA"],
			]
		]);
	valida_error_medoo_and_die();
	if ($auditor_asignado) {
		$respuesta["resultado"]="error";
		$respuesta["mensaje"]="El día " . $fechas_a_eliminar[$i]["FECHA"] . " ya tiene auditor asignado";
	}
	else { //Se puede eliminar
		$database->delete("SG_AUDITORIA_FECHAS", 
			["AND"=>["ID_SG_AUDITORIA"=>$ID_SG_AUDITORIA,"FECHA"=>$fechas_a_eliminar[$i]["FECHA"]]]);
		valida_error_medoo_and_die();
	}
}


print_r(json_encode($respuesta)); 


?> 
