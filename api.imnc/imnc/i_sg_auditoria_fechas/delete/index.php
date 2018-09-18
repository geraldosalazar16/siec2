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
		$mailerror->send("I_SG_AUDITORIA_FECHAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 


$ID = $objeto->ID; 
valida_parametro_and_die($ID, "Falta el ID");

$id1 = $database->delete("I_SG_AUDITORIA_FECHAS", ["ID" => $ID]);
valida_error_medoo_and_die();

$respuesta['resultado']="ok";
$respuesta['id']=$id1;

print_r(json_encode($respuesta));


//-------- FIN --------------
?>