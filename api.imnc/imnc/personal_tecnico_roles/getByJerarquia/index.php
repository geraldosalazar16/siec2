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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "isauro.mendoza@dhttecno.com");
		die();
	}
}

$id = $_REQUEST["id"];

$jerarquia = $database->query("SELECT MIN(PT.JERARQUIA) as minimo FROM PERSONAL_TECNICO_ROLES PT INNER JOIN PERSONAL_TECNICO_CALIFICACIONES PTC  WHERE PTC.ID_ROL=PT.ID AND PTC.ID_PERSONAL_TECNICO=".$database->quote($id).";")->fetchAll(PDO::FETCH_ASSOC);
$rol = $database->query("SELECT * FROM PERSONAL_TECNICO_ROLES 
	WHERE JERARQUIA >= ".$database->quote($jerarquia[0]['minimo']).";")->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

print_r(json_encode($rol));


//-------- FIN --------------
?>