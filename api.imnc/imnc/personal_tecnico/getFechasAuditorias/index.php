<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);
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
		die();
	}
}

$respuesta=array();
$id = $_REQUEST["id"];
/*
$ids_califs = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "ID", ["ID_PERSONAL_TECNICO" => $id]);
valida_error_medoo_and_die();

$datos_auditoria = $database->select("SG_AUDITORIA_GRUPOS", ["ID", "ID_SG_AUDITORIA"], ["ID_PERSONAL_TECNICO_CALIF" => $ids_califs]);
valida_error_medoo_and_die();

for ($i=0; $i < count($datos_auditoria) ; $i++) { 
	$id_sg_tipo_servicio = $database->get("SG_AUDITORIAS", "ID_SG_TIPO_SERVICIO", ["ID" => $datos_auditoria[$i]["ID_SG_AUDITORIA"]]);
	valida_error_medoo_and_die();
	$datos_auditoria[$i]["ID_SG_TIPO_SERVICIO"] = $id_sg_tipo_servicio;

	$sg_tipos_servicio = $database->get("SG_TIPOS_SERVICIO", ["ID_SERVICIO_CLIENTE_ETAPA",	"ID_TIPO_SERVICIO",	"ID_NORMA"], ["ID" => $datos_auditoria[$i]["ID_SG_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die();
	$datos_auditoria[$i]["ID_SERVICIO_CLIENTE_ETAPA"] = $sg_tipos_servicio["ID_SERVICIO_CLIENTE_ETAPA"];
	$datos_auditoria[$i]["ID_TIPO_SERVICIO"] = $sg_tipos_servicio["ID_TIPO_SERVICIO"];
	$datos_auditoria[$i]["ID_NORMA"] = $sg_tipos_servicio["ID_NORMA"];

	$sg_auditoria_grupo_fechas = $database->select("SG_AUDITORIA_GRUPO_FECHAS", "FECHA", ["ID_SG_AUDITORIA_GRUPO" => $datos_auditoria[$i]["ID"]]);
	valida_error_medoo_and_die();
	$datos_auditoria[$i]["FECHAS_ASIGNADAS"] = $sg_auditoria_grupo_fechas;
}

print_r(json_encode($datos_auditoria));
*/
$ids_califs = $database->select("PERSONAL_TECNICO_CALIFICACIONES", "ID", ["ID_PERSONAL_TECNICO" => $id]);
valida_error_medoo_and_die();

$datos_auditoria = $database->select("I_SG_AUDITORIA_GRUPOS", [
	"ID_SERVICIO_CLIENTE_ETAPA", 
	"TIPO_AUDITORIA",
	"CICLO"
], [
	"ID_PERSONAL_TECNICO_CALIF" => $ids_califs
]);
valida_error_medoo_and_die();

for ($i=0; $i < count($datos_auditoria) ; $i++) { 
	$id_sce = $datos_auditoria[$i]["ID_SERVICIO_CLIENTE_ETAPA"];
	$datos_sce = $database->get("SERVICIO_CLIENTE_ETAPA",[
		"ID_TIPO_SERVICIO",
		"REFERENCIA"
	],[
		"ID" => $id_sce
	]);
	$id_tipo_servicio = $datos_sce["ID_TIPO_SERVICIO"];
	$referencia = $datos_sce["REFERENCIA"];
	valida_error_medoo_and_die();

	$datos_auditoria[$i]["ID_TIPO_SERVICIO"] = $id_tipo_servicio;
	$datos_auditoria[$i]["REFERENCIA"] = $referencia;
	
	$normas = $database->select("SCE_NORMAS","ID_NORMA",["ID_SCE" => $id_sce]);
	valida_error_medoo_and_die();
	$datos_auditoria[$i]["NORMAS"] = $normas;

	$sg_auditoria_grupo_fechas = $database->select("I_SG_AUDITORIA_GRUPO_FECHAS", "FECHA", [
		"AND" => [
			"ID_SERVICIO_CLIENTE_ETAPA" => $id_sce,
			"TIPO_AUDITORIA" => $datos_auditoria[$i]["TIPO_AUDITORIA"],
			"CICLO" => $datos_auditoria[$i]["CICLO"],
			"ID_PERSONAL_TECNICO_CALIF" => $ids_califs
		]
	]);
	valida_error_medoo_and_die();
	$datos_auditoria[$i]["FECHAS_ASIGNADAS"] = $sg_auditoria_grupo_fechas;
}

print_r(json_encode($datos_auditoria));
//-------- FIN --------------
?>