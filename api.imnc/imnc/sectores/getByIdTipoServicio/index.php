<?php 
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

$id_tipo_servicio = $_REQUEST["id_tipo_servicio"];
if($id_tipo_servicio == 20){
	//$sectores = $database->query("SELECT * FROM `SECTORES` WHERE `ID_TIPO_SERVICIO`=1 OR `ID_TIPO_SERVICIO` =2 OR `ID_TIPO_SERVICIO`=12 ORDER BY `ID_TIPO_SERVICIO` ASC ")->fetchAll(PDO::FETCH_ASSOC);
	$sectores = $database->query("SELECT * FROM `SECTORES` WHERE `ID_TIPO_SERVICIO` IN (SELECT `ID` FROM `TIPOS_SERVICIO` WHERE `ID_SERVICIO` = 1)")->fetchAll(PDO::FETCH_ASSOC);
}
else{
	$sectores = $database->select("SECTORES", "*", ["ID_TIPO_SERVICIO"=>$id_tipo_servicio]);
}
valida_error_medoo_and_die();

print_r(json_encode($sectores));


//-------- FIN --------------
?>