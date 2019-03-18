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

$ESCRITORIO= $objeto->ESCRITORIO;
if(!$ESCRITORIO){$ESCRITORIO="";}

$SILLA = $objeto->SILLA;
if(!$SILLA){$SILLA="";}

$TELEFONO_FIJO = $objeto->TELEFONO_FIJO;
if(!$TELEFONO_FIJO){$TELEFONO_FIJO="";}

$MOVIL = $objeto->MOVIL;
if(!$MOVIL){$MOVIL="";}
$count = $database->count("PERSONAL_INTERNO_MOBILIARIO",["NO_EMPLEADO" => $NO_EMPLEADO]);
if($count==0)
{
    $id = $database->insert("PERSONAL_INTERNO_MOBILIARIO", [
        "NO_EMPLEADO" => $NO_EMPLEADO,
        "ESCRITORIO"=>$ESCRITORIO,
        "SILLA"=>$SILLA,
        "TELEFONO_FIJO"=>$TELEFONO_FIJO,
        "MOVIL"=>$MOVIL,

    ]);
    valida_error_medoo_and_die();

}else
{
    $id = $database->update("PERSONAL_INTERNO_MOBILIARIO", [
        "ESCRITORIO"=>$ESCRITORIO,
        "SILLA"=>$SILLA,
        "TELEFONO_FIJO"=>$TELEFONO_FIJO,
        "MOVIL"=>$MOVIL,

    ],["NO_EMPLEADO" => $NO_EMPLEADO]);


}



$respuesta['resultado']="ok";

print_r(json_encode($respuesta));


?>