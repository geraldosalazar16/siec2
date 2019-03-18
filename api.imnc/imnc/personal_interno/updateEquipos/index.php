<?php 
//error_reporting(E_ALL);
//ini_set("display_errors",1);


include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include '../../ex_common/archivos.php';

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
		die();
	}
}

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$NO_EMPLEADO = $objeto->NO;
valida_parametro_and_die($NO_EMPLEADO, "Es necesario capturar un No. empleado");

$COMPUTADORA = $objeto->COMPUTADORA;
if(!$COMPUTADORA){$COMPUTADORA="";}

$MODELO= $objeto->MODELO;
if(!$MODELO){$MODELO="";}

$SOFTWARE = $objeto->SOFTWARE;
if(!$SOFTWARE){$SOFTWARE="";}

$LICENCIAMIENTO = $objeto->LICENCIAMIENTO;
if(!$LICENCIAMIENTO){$LICENCIAMIENTO="";}

$ACCION = $objeto->ACCION;

$ID =  $objeto->ID;
if($ACCION=='insertar')
{
    $id = $database->insert("PERSONAL_INTERNO_EQUIPOS", [
        "NO_EMPLEADO" => $NO_EMPLEADO,
        "COMPUTADORA"=>$COMPUTADORA,
        "MODELO"=>$MODELO,
        "SOFTWARE"=>$SOFTWARE,
        "LICENCIAMIENTO"=>$LICENCIAMIENTO,

    ]);
    valida_error_medoo_and_die();

}else
{
    $id = $database->update("PERSONAL_INTERNO_EQUIPOS", [
        "COMPUTADORA"=>$COMPUTADORA,
        "MODELO"=>$MODELO,
        "SOFTWARE"=>$SOFTWARE,
        "LICENCIAMIENTO"=>$LICENCIAMIENTO,

    ],["ID" => $ID]);


}



$respuesta['resultado']="ok";

print_r(json_encode($respuesta));


?>