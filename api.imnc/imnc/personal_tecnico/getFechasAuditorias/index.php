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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();
$id = $_REQUEST["id"];

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


//-------- FIN --------------
?>