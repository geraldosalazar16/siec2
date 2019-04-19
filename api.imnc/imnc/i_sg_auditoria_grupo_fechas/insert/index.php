<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
include  '../../common/common_functions.php';   
 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");
$ID_PERSONAL_TECNICO_CALIF = $objeto->ID_PERSONAL_TECNICO_CALIF; 
valida_parametro_and_die($ID_PERSONAL_TECNICO_CALIF, "Falta la ID_PERSONAL_TECNICO_CALIF");
$FECHA = $objeto->FECHA; 
valida_parametro_and_die($FECHA, "Falta la FECHA");
$ID_NORMA = $objeto->ID_NORMA; 
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");


$respuesta["resultado"]="ok"; 
$mensaje="";
//AQUI ACOMODO LAS FECHAS
$ARR_FECHAS = explode(',',$FECHA);
for($i=0;$i<count($ARR_FECHAS);$i++){
	$year=substr($ARR_FECHAS[$i],0,4);
	$month=substr($ARR_FECHAS[$i],5,2);
	$day=substr($ARR_FECHAS[$i],8,2);
	$FECHAN = $year.$month.$day;
	
	$FECHA1 = substr($FECHAN,6,2)."/".substr($FECHAN,4,2)."/".substr($FECHAN,0,4);
	$FECHAS = $FECHA1.",".$FECHA1;
	
	if($database->count("I_SG_AUDITORIA_GRUPO_FECHAS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF,"FECHA"=>$FECHAN]])==0){
		//*********************************************//
		/*
		$url = $global_apiserver . "/personal_tecnico/isDisponible/";
		$params = array( 'http' =>	array(
				'method'	=>	'POST',
				'content'	=>	'ID="1"&FECHAS="20181220"'//'ID='.$ID_PERSONAL_TECNICO_CALIF.'&FECHAS='.$FECHAS
					)
				);

		$ctx = stream_context_create($params);
		$fp = @fopen($url,'rb',false, $ctx);
		if(!$fp){
			throw new Exception("Problem with $url, $php_errormsg");
		}
		$json_response = @stream_get_contents($fp);
		if($json_response === false){
			throw new Exception("Problem reading data from $url, $php_errormsg");
		}	*/	
		$ID_PT = $database->get("PERSONAL_TECNICO_CALIFICACIONES","ID_PERSONAL_TECNICO",["ID" => $ID_PERSONAL_TECNICO_CALIF]);
		$context = "?ID=".$ID_PT."&FECHAS=".$FECHAS;
		$url = $global_apiserver . "/personal_tecnico/isDisponible/".$context;
		$json_response = file_get_contents($url);
		$json_response = json_decode($json_response);
		if($json_response->disponible == "si"){
			$idd = $database->insert("I_SG_AUDITORIA_GRUPO_FECHAS",
											
											[
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
												"CICLO"=>$CICLO,
												"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF,
												"FECHA" => $FECHAN,
												"ID_NORMA" => $ID_NORMA,
												"FECHA_CREACION" => $FECHA_CREACION,
												"HORA_CREACION" => $HORA_CREACION,
												"FECHA_MODIFICACION" => "",
												"HORA_MODIFICACION" => "",
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												
												
											]); 
			valida_error_medoo_and_die();
	
		}
		else{
			$respuesta["resultado"]="error"; 
			$mensaje.=" Este auditor no esta disponible en la fecha ".$FECHAN.","; 
		}

	}
	else{
		$respuesta["resultado"]="error"; 
		$mensaje.=" La FECHA ".$FECHAN." ya fue agregada para este auditor,";
		
	}
}


$respuesta["mensaje"]=$mensaje;




print_r(json_encode($respuesta)); 
?> 
