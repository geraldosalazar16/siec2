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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta));
		die(); 
	} 
} 
$respuesta=array(); 
//$servicio_cliente_etapa = $database->select("SERVICIO_CLIENTE_ETAPA", "*");
$servicio_cliente_etapa = $database->select("SERVICIO_CLIENTE_ETAPA", [
	"[><]SERVICIOS" => ["ID_SERVICIO" => "ID"],
	"[><]TIPOS_SERVICIO" => ["ID_TIPO_SERVICIO" => "ID"],
	"[><]CLIENTES" => ["ID_CLIENTE" => "ID"],
	"[><]ETAPAS_PROCESO" => ["ID_ETAPA_PROCESO" => "ID_ETAPA"]
], [
	"SERVICIO_CLIENTE_ETAPA.ID",
	"SERVICIO_CLIENTE_ETAPA.ID_CLIENTE",
	"SERVICIO_CLIENTE_ETAPA.ID_SERVICIO",
	"SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO",
	"SERVICIO_CLIENTE_ETAPA.REFERENCIA",
	"SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO",
	"SERVICIO_CLIENTE_ETAPA.SG_INTEGRAL",
	"SERVICIO_CLIENTE_ETAPA.FECHA_CREACION",
	"SERVICIO_CLIENTE_ETAPA.HORA_CREACION",
	"SERVICIO_CLIENTE_ETAPA.FECHA_MODIFICACION",
	"SERVICIO_CLIENTE_ETAPA.HORA_MODIFICACION",
	"SERVICIO_CLIENTE_ETAPA.ID_USUARIO_CREACION",
	"SERVICIO_CLIENTE_ETAPA.ID_USUARIO_MODIFICACION",
	"SERVICIO_CLIENTE_ETAPA.CAMBIO",
	"SERVICIO_CLIENTE_ETAPA.ID_REFERENCIA_SEG",
	"SERVICIO_CLIENTE_ETAPA.OBSERVACION_CAMBIO",
	"SERVICIO_CLIENTE_ETAPA.ID_NORMA",
	"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
	"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPO_SERVICIO)",
	"CLIENTES.NOMBRE(NOMBRE_CLIENTE)",
	"ETAPAS_PROCESO.ETAPA(NOMBRE_ETAPA)"
]);

for ($i=0; $i < count($servicio_cliente_etapa) ; $i++) { 
	/*
	$servicio_nombre = $database->get("SERVICIOS", "NOMBRE", ["ID"=>$servicio_cliente_etapa[$i]["ID_SERVICIO"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_SERVICIO"] = $servicio_nombre;

	$tipo_servicio_nombre = $database->get("TIPOS_SERVICIO", "NOMBRE", ["ID"=>$servicio_cliente_etapa[$i]["ID_TIPO_SERVICIO"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_TIPO_SERVICIO"] = $tipo_servicio_nombre;

	$cliente_nombre = $database->get("CLIENTES", "NOMBRE", ["ID"=>$servicio_cliente_etapa[$i]["ID_CLIENTE"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_CLIENTE"] = $cliente_nombre;

	$etapa_nombre = $database->get("ETAPAS_PROCESO", "ETAPA", ["ID_ETAPA"=>$servicio_cliente_etapa[$i]["ID_ETAPA_PROCESO"]]);
	$servicio_cliente_etapa[$i]["NOMBRE_ETAPA"] = $etapa_nombre;

	*/
	if($servicio_cliente_etapa[$i]["ID_SERVICIO"]==3)
	{
        $curso = $database->query("SELECT C.ID_CURSO,C.NOMBRE as ID_NORMA ,SC.CANTIDAD_PARTICIPANTES FROM SCE_CURSOS SC INNER JOIN CURSOS C ON SC.ID_CURSO = C.ID_CURSO WHERE SC.ID_SCE =".$servicio_cliente_etapa[$i]["ID"]." LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
        $servicio_cliente_etapa[$i]["NORMAS"] = $curso;
	}
	else{
		$normas = $database->query("SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`= ".$servicio_cliente_etapa[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
		$servicio_cliente_etapa[$i]["NORMAS"] = $normas;
    }

}
valida_error_medoo_and_die(); 
print_r(json_encode($servicio_cliente_etapa)); 
?> 
