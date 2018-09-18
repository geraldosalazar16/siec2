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
		$mailerror->send("SG_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}  

$respuesta=array(); 

$id_sg_tipo_servicio = $_REQUEST["id_sg_tipo_servicio"];
$id_auditoria = "";
if(array_key_exists("id_auditoria",$_REQUEST)){
	$id_auditoria =  $_REQUEST["id_auditoria"];
}
$campos = [
	"SG_SITIOS.ID",
	"SG_SITIOS.CANTIDAD_PERSONAS",
	"SG_ACTIVIDAD.ACTIVIDAD",
	"SG_SITIOS.CANTIDAD_TURNOS",
	"SG_SITIOS.NUMERO_TOTAL_EMPLEADOS",
	"SG_SITIOS.NUMERO_EMPLEADOS_CERTIFICACION",
	"SG_SITIOS.CANTIDAD_DE_PROCESOS",
	"SG_SITIOS.NOMBRE_PROCESOS",
	"SG_SITIOS.TEMPORAL_O_FIJO",
	"SG_SITIOS.ID_CLIENTE_DOMICILIO",
	"SG_SITIOS.ID_SG_TIPO_SERVICIO"
];
$sg_sitios = $database->select("SG_SITIOS", ["[>]SG_ACTIVIDAD" => ["ID_ACTIVIDAD" => "ID"]] , $campos, ["ID_SG_TIPO_SERVICIO" => $id_sg_tipo_servicio]); 
valida_error_medoo_and_die(); 
for ($i=0; $i < count($sg_sitios) ; $i++) { 
	$domicilio_nombre = $database->get("CLIENTES_DOMICILIOS", "NOMBRE_DOMICILIO", ["ID"=>$sg_sitios[$i]["ID_CLIENTE_DOMICILIO"]]);
	valida_error_medoo_and_die(); 
	$sg_sitios[$i]["NOMBRE_DOMICILIO"] = $domicilio_nombre;

	$tipo_servicio = $database->get("SG_TIPOS_SERVICIO", "ID_TIPO_SERVICIO", ["ID"=>$sg_sitios[$i]["ID_SG_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die(); 
	$sg_sitios[$i]["CLAVE_TIPO_SERVICIO"] = $tipo_servicio;

	if ($id_auditoria != "") {
		$existe_en_auditoria = $database->count("SG_AUDITORIA_SITIOS", ["AND" => ["ID_SG_SITIO"=>$sg_sitios[$i]["ID"], "ID_SG_AUDITORIA"=>$id_auditoria]]);
		valida_error_medoo_and_die(); 
		$sg_sitios[$i]["EXISTE_EN_AUDITORIA"] = $existe_en_auditoria;
	}

}
print_r(json_encode($sg_sitios)); 
?> 
