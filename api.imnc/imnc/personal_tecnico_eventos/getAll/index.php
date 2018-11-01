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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();

$query = "SELECT 
PT.ID AS ID_PERSONAL_TECNICO,
PT.NOMBRE, 
PT.APELLIDO_PATERNO,
PTE.EVENTO,
PTE.FECHA_INICIO,
PTE.FECHA_FIN
FROM 
PERSONAL_TECNICO PT
INNER JOIN PERSONAL_TECNICO_EVENTOS PTE
ON PT.ID = PTE.ID_PERSONAL_TECNICO";
$personal_tecnico_eventos = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

print_r(json_encode($personal_tecnico_eventos));


//-------- FIN --------------
?>