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


$id = $_REQUEST["id"];
$ref = $_REQUEST["ref"];
$etapa = $_REQUEST["etapa"];
$norma = $_REQUEST["norma"];
$dict_const = $_REQUEST["dict_const"];
$anio = date('y');
$consecutivo = "";
if(substr($ref,0,1) == 'C'){
	$ciclo = (int)substr($ref,1,1);
}
else{
	$ciclo=1;
}

$id_etapa = "";
$referencia_servicio = "";
if($id != 18){
	// ==============================================================================
	$texto_servicio	=	"";
	$id_servicio	=	$database->get("TIPOS_SERVICIO", "ID_SERVICIO", ["ID"=>$id]);
	valida_error_medoo_and_die();
	switch($id_servicio){
		case 1:
			$texto_servicio	=	"S";
			break;
		case 2:
			$texto_servicio	=	"S";
			break;
		default:
			$texto_servicio	=	$id_servicio;
			break;
	}
	
	// ==============================================================================	

	if($etapa != "XX")
	{
		$id_etapa = $database->get("ETAPAS_PROCESO", "ID", ["ID_ETAPA"=>$etapa]);
		valida_error_medoo_and_die();
	}
	else
	{
		$id_etapa = "XX";
		$consecutivo = "XXX";
	}	
	//En los casos de renovacion hay que incrementar el ciclo
	if($id_etapa == "REN"||$id_etapa == "Ren")
	{
		$ciclo = $ciclo+1;
	}	

	//Selecciona el texto usado para crear las referencias de un tipo de servicio especifico
	if($id == "XXX")
	{
		$referencia_servicio = "XX";
	}
	else
	{
		$referencia_servicio = $database->get("TIPOS_SERVICIO", "ID_REFERENCIA", ["ID"=>$id]);
		valida_error_medoo_and_die();
	}

	//Si no es inicial no se genera el consecutivo
	if($id_etapa == "ASIG" || $id_etapa == "Tran")
	{
		//Referencia LIKE $texto_servicio+referencia_servicio+año me va a entregar todas las referencias de este año con esa referencia de servicio
		$consulta = "SELECT REFERENCIA FROM SERVICIO_CLIENTE_ETAPA WHERE REFERENCIA LIKE '%".$texto_servicio.$referencia_servicio.'-'.$anio."%'";
		/*
		$referencias = $database->select("SERVICIO_CLIENTE_ETAPA", "REFERENCIA", ["REFERENCIA[~]"=>'SC'.$referencia_servicio.'-'.$anio]);
		//valida_error_medoo_and_die();
		*/
		$referencias = $database->query($consulta)->fetchAll();
		$mayor_consecutivo = 0;
		if(count($referencias)> 0)
		{
			for($i = 0;$i<count($referencias);$i++)
			{
				//Obtener el consecutivo a partir de la referencia
				$a_y_c1	=	explode("-",$referencias[$i]["REFERENCIA"]);
				$consecutivo = (int)substr($a_y_c1[2],2,3);
				//$consecutivo = (int)substr($referencias[$i]["REFERENCIA"],11,3);
				if($mayor_consecutivo < $consecutivo)
				{
					$mayor_consecutivo = $consecutivo;
				}
			}
		}
		else
		{
			$mayor_consecutivo = 0;
		}
		$mayor_consecutivo++;
		$consecutivo = substr("000".$mayor_consecutivo,-3);
	}
	if($id_etapa != "XX" && $id_etapa != "ASIG" && $id_etapa != "Tran")
	{
		//Si no es inicial solo tengo que cambiar el idetapa de la referencia
		//Por tanto saco el año y el consecutivo de la referencia vieja C1-SCSGC-17001-IN 
		/* 
		$anio = substr($ref,9,2);
		$consecutivo = substr($ref,11,3);
		*/
		$a_y_c	=	explode("-",$ref);
		$anio = substr($a_y_c[2],0,2);
		$consecutivo = substr($a_y_c[2],2,3);
	}

	$final = "C".$ciclo."-".$texto_servicio.$referencia_servicio."-".$anio.$consecutivo."-".$id_etapa;
	//print_r(json_encode($final));
}
else{
	if($etapa != "XX")
	{
		$id_etapa = $database->get("ETAPAS_PROCESO", "ID", ["ID_ETAPA"=>$etapa]);
		valida_error_medoo_and_die();
	}
	else
	{
		$id_etapa = "XX";
		$consecutivo = "XXX";
	}	
	//Si no es inicial no se genera el consecutivo
	if($id_etapa == "ASIG" || $id_etapa == "Tran")
	{
		//Referencia LIKE $texto_servicio+referencia_servicio+año me va a entregar todas las referencias de este año con esa referencia de servicio
		$consulta = "SELECT REFERENCIA FROM SERVICIO_CLIENTE_ETAPA WHERE REFERENCIA LIKE '".$anio."%'";
		/*
		$referencias = $database->select("SERVICIO_CLIENTE_ETAPA", "REFERENCIA", ["REFERENCIA[~]"=>'SC'.$referencia_servicio.'-'.$anio]);
		//valida_error_medoo_and_die();
		*/
		$referencias = $database->query($consulta)->fetchAll();
		$mayor_consecutivo = 0;
		if(count($referencias)> 0)
		{
			for($i = 0;$i<count($referencias);$i++)
			{
				//Obtener el consecutivo a partir de la referencia
				$a_y_c1	=	explode("V",$referencias[$i]["REFERENCIA"]);
				$consecutivo = (int)$a_y_c1[count($a_y_c1)-1];
				//$consecutivo = (int)substr($referencias[$i]["REFERENCIA"],11,3);
				if($mayor_consecutivo < $consecutivo)
				{
					$mayor_consecutivo = $consecutivo;
				}
			}
		}
		else
		{
			$mayor_consecutivo = 0;
		}
		$mayor_consecutivo++;
		$consecutivo = substr("00000".$mayor_consecutivo,-5);
	}
	if($id_etapa != "XX" && $id_etapa != "ASIG" && $id_etapa != "Tran")
	{
		//Si no es inicial solo tengo que sacar el consecutivo de la referencia
		
		$a_y_c1	=	explode("V",$ref);
		$anio = substr($a_y_c[0],0,2);
		$consecutivo = $a_y_c1[count($a_y_c1)-1];
		
	}
	//Aqui se verifica si es dictamen o constancia
	if($dict_const != "ZZ"){
			if($dict_const == "Dictamen"){
				$d_y_c = "USD";
			}
			if($dict_const == "Constancia"){
				$d_y_c = "USC";
			}		
	}
	else{
		$d_y_c = "XXX";
	}
	// Se verifica si tiene cargada norma o no.
	if($norma != "YYY"){
		$norma =$norma;				
	}
	else{
		$norma = "YYYYY";
	}	
	
	
	$final = $anio."006".$d_y_c.$norma."V".$consecutivo;
}
print_r($final);


//-------- FIN --------------
?>