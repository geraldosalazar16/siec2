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

$id_tipo_servicio = $_REQUEST["id"];

$normas = $database->query("SELECT 
NORMAS.ID AS ID,
NORMAS.NOMBRE AS NOMBRE
FROM 
NORMAS_TIPOSERVICIO 
INNER JOIN NORMAS 
ON NORMAS.ID = NORMAS_TIPOSERVICIO.ID_NORMA
WHERE
NORMAS_TIPOSERVICIO.ID_TIPO_SERVICIO = ".$id_tipo_servicio)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

print_r(json_encode($normas));


//-------- FIN --------------
?>
