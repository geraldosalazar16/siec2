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
$id_rol = $_REQUEST["id_rol"];
$id_pt = $_REQUEST["id_pt"];
$consecutivo = "";

 // ==============================================================================
 $texto_servicio	=	"XXX";
 $texto_auditor = "";
$datos_servicio	=	$database->get("TIPOS_SERVICIO",["ID_SERVICIO","ACRONIMO"], ["ID"=>$id]);
valida_error_medoo_and_die();

// ==============================================================================	

if($id_rol != "XX")
{
	$texto_auditor = $database->get("PERSONAL_TECNICO_ROLES", "ACRONIMO", ["ID"=>$id_rol]);
	valida_error_medoo_and_die();
}
else
{
	$texto_auditor = "XX";
	
}	
// ==============================================================================	



//Revisa si para este auditor y este tipo de servicio existe ya un consecutivo, de ser asi lo selecciona sino busca el maximo consecutivo y le suma 1
$registro = "";
if($id != "XXX" && $id_rol !="XX")
{

	switch($datos_servicio["ID_SERVICIO"]){
		case 1:
			$texto_servicio	=	"SG".$datos_servicio["ACRONIMO"];
			$consulta = "SELECT `REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` WHERE `ID_TIPO_SERVICIO` = ".$id." AND `REGISTRO` != 'NO APLICA'";
			$consulta1 = "SELECT `REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` WHERE `ID_TIPO_SERVICIO` = ".$id." AND `ID_PERSONAL_TECNICO` = ".$id_pt." AND`REGISTRO` != 'NO APLICA'";
			break;
		case 2:
			$texto_servicio	=	"PRO";
			$consulta = "SELECT `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` INNER JOIN `TIPOS_SERVICIO` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`= `TIPOS_SERVICIO`.`ID` WHERE `TIPOS_SERVICIO`.`ID_SERVICIO` = 2 AND `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` != 'NO APLICA'";
			$consulta1 = "SELECT `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` INNER JOIN `TIPOS_SERVICIO` ON `PERSONAL_TECNICO_CALIFICACIONES`.`ID_TIPO_SERVICIO`= `TIPOS_SERVICIO`.`ID` WHERE `TIPOS_SERVICIO`.`ID_SERVICIO` = 2 AND `PERSONAL_TECNICO_CALIFICACIONES`.`ID_PERSONAL_TECNICO`= ".$id_pt." AND  `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` != 'NO APLICA'";
			break;
		default:
			$texto_servicio	=	"XXX";
			$consulta = "SELECT `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` WHERE `ID`=0";
			$consulta1 = "SELECT `PERSONAL_TECNICO_CALIFICACIONES`.`REGISTRO` FROM `PERSONAL_TECNICO_CALIFICACIONES` WHERE `ID`=0";
			break;
	}

	//	Ahora reviso si para este auditor y para este tipo de servicio existe algun registro
	//$registro = $database->get("PERSONAL_TECNICO_CALIFICACIONES", "REGISTRO", ["AND"=>["ID_PERSONAL_TECNICO"=>$id_pt,"ID_TIPO_SERVICIO"=>$id,"REGISTRO[!]"=>'NO APLICA']]);
	$registro = $database->query($consulta1)->fetchAll();
	if(count($registro)>0){
		//Obtener el consecutivo a partir del registro
			$a_y_c1	=	explode("-",$registro[0]["REGISTRO"]);
			$mayor_consecutivo = (int)$a_y_c1[1];
	}
	else{
		
		
		$registros = $database->query($consulta)->fetchAll();
		$mayor_consecutivo = 0;
		for($i = 0;$i<count($registros);$i++)
		{
			//Obtener el consecutivo a partir de los registros
			$a_y_c1	=	explode("-",$registros[$i]["REGISTRO"]);
			if(isset($a_y_c1[1])){
				$consecutivo = (int)$a_y_c1[1];
			}
			else{
				$consecutivo = 0;
			}
			if($mayor_consecutivo < $consecutivo)
			{
				$mayor_consecutivo = $consecutivo;
			}
		}
		$mayor_consecutivo++;
		
	
	}
	$consecutivo = substr("000".$mayor_consecutivo,-3);
	
}
else
{
	$consecutivo = "000";
}



$final = $texto_auditor.$texto_servicio."-".$consecutivo;
//print_r(json_encode($final));
print_r($final);


//-------- FIN --------------
?>