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
/*$filtro = $_REQUEST["filtro"];

if ($filtro == "vigentes") {
	$fecha_hoy = date("Ymd");
	$tipos_servicio = $database->select("TIPOS_SERVICIO", "*", ["FECHA_FIN[>=]" => $fecha_hoy]);
}
else{
	$tipos_servicio = $database->select("TIPOS_SERVICIO", "*");	
}
*/
$tipos_servicio = $database->select("TIPOS_SERVICIO",["[><]SERVICIOS"=>["TIPOS_SERVICIO.ID_SERVICIO"=>"ID"],], ["SERVICIOS.NOMBRE(NOMBRE_SERVICIO)","TIPOS_SERVICIO.ID","TIPOS_SERVICIO.ACRONIMO","TIPOS_SERVICIO.NOMBRE","TIPOS_SERVICIO.ID_REFERENCIA"]);	
valida_error_medoo_and_die();

//Agregar las normas cada tipo de servicio
for($i=0;$i<count($tipos_servicio);$i++){
	$aaa="";
	$normas_ts	=	$database->select("NORMAS_TIPOSERVICIO","*",["ID_TIPO_SERVICIO"=>$tipos_servicio[$i]['ID']]);
	if(isset($normas_ts)){
		for($j=0;$j<count($normas_ts);$j++){
			$aaa	.=	$normas_ts[$j]["ID_NORMA"]." ;".PHP_EOL;
		}
	}
	
	$tipos_servicio[$i]["NORMA_ID"] = $aaa;
}

print_r(json_encode($tipos_servicio));


//-------- FIN --------------
?>