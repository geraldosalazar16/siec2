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

$id = $_REQUEST["id"];


$cursos = $database->select("CURSOS",["[><]CURSOS_PROGRAMADOS"=>["CURSOS.ID_CURSO"=>"ID_CURSO"]],["CURSOS.NOMBRE","CURSOS_PROGRAMADOS.ID","CURSOS_PROGRAMADOS.FECHAS"],["AND"=>["CURSOS.ID_TIPO_SERVICIO"=>$id,"CURSOS.ISACTIVO"=>"1"]]);
valida_error_medoo_and_die();
$aux =  null;
$HOY = date("Ymd");
for ($c=0; $c < count($cursos) ; $c++) {
    $FECHAS = explode("-", $cursos[$c]["FECHAS"]);
    $FECHA_F = explode("/", $FECHAS[1]);
    $FECHA_F = date("Ymd", strtotime($FECHA_F[2] . $FECHA_F[1] . $FECHA_F[0]));

    if($FECHA_F>$HOY)
        $aux[] = $cursos[$c];
}
print_r(json_encode($aux));
//print_r(json_encode($cursos));


//-------- FIN --------------
?>