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
		die();
	}
}

$respuesta=array();
$valor = $_REQUEST["valor"];
$periodicidad = $_REQUEST["periodicidad"];
$nombre = "Propuestas Ganadas";
$objetivo_ganadas = $database->get("OBJETIVOS",
	                            [
	                            	"[><]OBJETIVO_VALORES"=>["ID"=>"OBJETIVOS_ID"],
	                            	"[><]OBJETIVOS_PERIODICIDADES"=>["OBJETIVO_VALORES.OBJETIVOS_PERIODICIDADES_ID"=>"ID"],
								],
	                            [
									"OBJETIVO_VALORES.VALOR_OBJETIVO",
	                            ],
	                            [ "AND"=>
                                    [
										"OBJETIVOS.NOMBRE"=>$nombre,"OBJETIVO_VALORES.VALOR_PERIODICIDAD"=>$valor,"OBJETIVOS_PERIODICIDADES.ID"=>$periodicidad
									]
								]);
$nombre = "Propuestas Emitidas";
$objetivo_emitidas = $database->get("OBJETIVOS",
								[
									"[><]OBJETIVO_VALORES"=>["ID"=>"OBJETIVOS_ID"],
									"[><]OBJETIVOS_PERIODICIDADES"=>["OBJETIVO_VALORES.OBJETIVOS_PERIODICIDADES_ID"=>"ID"],
								],
								[
									"OBJETIVO_VALORES.VALOR_OBJETIVO",
								],
								[ "AND"=>
									[
										"OBJETIVOS.NOMBRE"=>$nombre,"OBJETIVO_VALORES.VALOR_PERIODICIDAD"=>$valor,"OBJETIVOS_PERIODICIDADES.ID"=>$periodicidad
									]
								]);

$meses= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre", "Octubre","Noviembre","Diciembre");
$total = 0;
$mes  = 'Diciembre';
if($periodicidad == 2)
{
	$mes = $valor;
}
$str = '';
foreach ($meses as $item)
{
	$str .= "'".$item."',";
	if($item == $mes)
	{break;}
}
$str = substr($str, 0, -1);
//$str = explode(",",$str);
$nombre = "Propuestas Ganadas";
$query_ganadas = "SELECT O.NOMBRE,SUM(OV.VALOR_OBJETIVO) AS VALOR_OBJETIVO FROM OBJETIVOS O INNER JOIN OBJETIVO_VALORES OV ON O.ID = OV.OBJETIVOS_ID INNER JOIN OBJETIVOS_PERIODICIDADES OP ON OV.OBJETIVOS_PERIODICIDADES_ID = OP.ID WHERE O.NOMBRE = '".$nombre."' AND OP.ID = 2 AND OV.VALOR_PERIODICIDAD IN (".$str.")
GROUP BY O.NOMBRE";

$acum_ganadas = $database->query($query_ganadas)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

$nombre = "Propuestas Emitidas";
$query_emitidas = "SELECT O.NOMBRE,SUM(OV.VALOR_OBJETIVO) AS VALOR_OBJETIVO FROM OBJETIVOS O INNER JOIN OBJETIVO_VALORES OV ON O.ID = OV.OBJETIVOS_ID INNER JOIN OBJETIVOS_PERIODICIDADES OP ON OV.OBJETIVOS_PERIODICIDADES_ID = OP.ID WHERE O.NOMBRE = '".$nombre."' AND OP.ID = 2 AND OV.VALOR_PERIODICIDAD IN (".$str.")
GROUP BY O.NOMBRE";

$acum_emitidas = $database->query($query_emitidas)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

$respuesta["OBJETIVOS"] = array("G"=>$objetivo_ganadas["VALOR_OBJETIVO"],"E"=>$objetivo_emitidas["VALOR_OBJETIVO"]);
$respuesta["OBJETIVOS_ACUM"] = array("G"=>$acum_ganadas[0]["VALOR_OBJETIVO"],"E"=>$acum_emitidas[0]["VALOR_OBJETIVO"]);

print_r(json_encode($respuesta));


//-------- FIN --------------
?>
