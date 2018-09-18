<?php 
include  '../../common/conn-apiserver.php'; 

include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}


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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "leovardo.quintero@dhttecno.com");
		die();
	}
}
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id_calificacion = $_REQUEST["id_calificacion"];
$id_tipo_servicio = $_REQUEST["id_tipo_servicio"];
$id_usuario = $_REQUEST["id_usuario"];
$query_calificacion = "SELECT * FROM PERSONAL_TECNICO_CALIFICACIONES WHERE ID = ".$id_calificacion;
$res_calificacion = $database->query($query_calificacion)->fetchAll(PDO::FETCH_ASSOC);

$IdPTC = $database->insert("PERSONAL_TECNICO_CALIFICACIONES", [
	"ID_PERSONAL_TECNICO" => $res_calificacion[0]["ID_PERSONAL_TECNICO"],
	"ID_ROL" => $res_calificacion[0]["ID_ROL"],
	"ID_TIPO_SERVICIO" => $id_tipo_servicio,
	"REGISTRO" => $res_calificacion[0]["REGISTRO"],
	"FECHA_INICIO" => $res_calificacion[0]["FECHA_INICIO"],
	"FECHA_FIN" => $res_calificacion[0]["FECHA_FIN"],
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $id_usuario,
	"ID_USUARIO_MODIFICACION" => $id_usuario
]);
valida_error_medoo_and_die();
$query_sectores = "SELECT * FROM PERSONAL_TECNICO_CALIF_SECTOR WHERE ID_PERSONAL_TECNICO_CALIFICACION = ".$res_calificacion[0]["ID"];
$res_sectores = $database->query($query_sectores)->fetchAll(PDO::FETCH_ASSOC);
for($i = 0 ; $i < sizeof($res_sectores);$i++){
	$idPTCS = $database->insert("PERSONAL_TECNICO_CALIF_SECTOR", [
	"ID_PERSONAL_TECNICO_CALIFICACION" => $IdPTC,
	"ID_SECTOR" => $res_sectores[$i]["ID_SECTOR"],
	"SECTOR_NACE" => $res_sectores[$i]["SECTOR_NACE"],
	"ESQUEMA_CERTIFICACION" => $res_sectores[$i]["ESQUEMA_CERTIFICACION"],
	"ALCANCE" => $res_sectores[$i]["ALCANCE"],
	"APROBACION_UVIC" => $res_sectores[$i]["APROBACION_UVIC"],
	"FECHA_INICIO" => $res_sectores[$i]["FECHA_INICIO"],
	"FECHA_FIN" => $res_sectores[$i]["FECHA_FIN"],
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"ID_USUARIO_CREACION" => $id_usuario,
	"ID_USUARIO_MODIFICACION" => $id_usuario
]);
}
$respuesta['resultado']="ok";
$respuesta['id']=$IdPTC;
print_r(json_encode($respuesta));


//-------- FIN --------------
?>