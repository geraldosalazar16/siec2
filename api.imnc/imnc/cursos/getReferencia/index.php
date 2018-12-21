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

$id = $_REQUEST["id"]; //ID del servicio para filtrar por el
$tipo = $_REQUEST["tipo"]; // El tipo de curso P o D
$referencia = "";


if($id && $tipo)
{
    $year=date("y");
    $parametro = $tipo."-".$year;
    $referencias = "";
    if($tipo=="D")
    {
        $referencias = $database->query("SELECT REFERENCIA FROM SERVICIO_CLIENTE_ETAPA WHERE ID_SERVICIO = ".$id." AND REFERENCIA LIKE '". $parametro."%' ORDER BY REFERENCIA")->fetchAll(PDO::FETCH_ASSOC);

    }
    if($tipo=="P")
    {
        $referencias = $database->query("SELECT REFERENCIA FROM CURSOS_PROGRAMADOS WHERE  REFERENCIA LIKE '". $parametro."%' ORDER BY REFERENCIA")->fetchAll(PDO::FETCH_ASSOC);

    }

    valida_error_medoo_and_die();
    if(count($referencias)>0)
    {
        $ultimo =  end($referencias);
        $consecutivo = substr($ultimo["REFERENCIA"], -3, 3);
        $consecutivo = (int)$consecutivo;
        $consecutivo++;
        if(strlen($consecutivo)==1)
        {
            $consecutivo = "00".(string)$consecutivo;
        }
        else
        {
           if(strlen($consecutivo)==2)
             $consecutivo = "0".(string)$consecutivo;
        }

        $referencia = $parametro.$consecutivo;

    }
    else
    {
        $referencia = $parametro."001";
    }
}



print_r($referencia);
//print_r(json_encode($cursos));


//-------- FIN --------------
?>