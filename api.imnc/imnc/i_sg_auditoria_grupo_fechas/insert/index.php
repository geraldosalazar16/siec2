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

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

if($database->count("I_SG_AUDITORIA_GRUPO_FECHAS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF,"FECHA"=>$FECHA]])==0){

	//Modificacion Geraldo agregar eventos a la validacion
	$ID_PERSONAL_TECNICO	=	$database->get("PERSONAL_TECNICO_CALIFICACIONES","ID_PERSONAL_TECNICO",["ID"=>$ID_PERSONAL_TECNICO_CALIF]);
	valida_error_medoo_and_die();
	$eventos	=	$database->select("PERSONAL_TECNICO_EVENTOS","*",["ID_PERSONAL_TECNICO"=>$ID_PERSONAL_TECNICO]);
	valida_error_medoo_and_die();
	
	// Validar que la fecha no coincida con ningun evento
	for($e=0; $e<count($eventos); $e++){
		$f_ini = date("Ymd",strtotime($eventos[$e]["FECHA_INICIO"]));
		$f_fin = date("Ymd",strtotime($eventos[$e]["FECHA_FIN"]));
		if($FECHA >= $f_ini && $FECHA <= $f_fin){
			imprime_error_and_die("La fecha " . $FECHA . " coincide con el evento ".$eventos[$e]["EVENTO"]);
		}
	}
	// Validar que el auditor este libre para esta fecha
	$consulta="SELECT COUNT(*) AS C

					FROM    `I_SG_AUDITORIA_GRUPO_FECHAS` AS ISAGF
					INNER JOIN 
						`PERSONAL_TECNICO_CALIFICACIONES` AS PTC ON PTC.`ID` = ISAGF.`ID_PERSONAL_TECNICO_CALIF`
					WHERE
						PTC.`ID_PERSONAL_TECNICO` =".$ID_PERSONAL_TECNICO." AND  ISAGF.`FECHA` = ".$FECHA;
	$ocupado = $database->query($consulta)->fetchAll();
	valida_error_medoo_and_die();
	if($ocupado[0]["C"] > 0){
		imprime_error_and_die( "Este auditor tiene la fecha ". $FECHA . " asignada a otra auditoria.");
	}
$idd = $database->insert("I_SG_AUDITORIA_GRUPO_FECHAS",
											
											[
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
												"CICLO"=>$CICLO,
												"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF,
												"FECHA" => $FECHA,
												"FECHA_CREACION" => $FECHA_CREACION,
												"HORA_CREACION" => $HORA_CREACION,
												"FECHA_MODIFICACION" => "",
												"HORA_MODIFICACION" => "",
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												
												
											]); 
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
}
else{
	$respuesta["resultado"]="error"; 
	$respuesta["mensaje"]="Este fecha ya fue agregada para este auditor"; 
}
print_r(json_encode($respuesta)); 
?> 
