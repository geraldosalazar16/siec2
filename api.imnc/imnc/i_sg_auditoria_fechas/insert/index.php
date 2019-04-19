<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

function valida_error_medoo_and_die(){ 
	global $database, $mailerror; 
	if ($database->error()[2]) { 
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("I_SG_AUDITORIA_FECHAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID_SERVICIO_CLIENTE_ETAPA; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");
$FECHA = $objeto->FECHA; 
valida_parametro_and_die($FECHA, "Falta la FECHA");

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
	$FECHA1 = $year.$month.$day;
	
	if($database->count("I_SG_AUDITORIA_FECHAS",["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"FECHA" => $FECHA1]])==0){
		$idd = $database->insert("I_SG_AUDITORIA_FECHAS",
											
											[
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
												"CICLO"=>$CICLO,
												"FECHA" => $FECHA1,
												"FECHA_CREACION" => $FECHA_CREACION,
												"HORA_CREACION" => $HORA_CREACION,
												"FECHA_MODIFICACION" => "",
												"HORA_MODIFICACION" => "",
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												
												
											]); 
		valida_error_medoo_and_die();
		//$respuesta["resultado"]="ok";  
	}
	else{
		$respuesta["resultado"]="error"; 
		$mensaje.=" La FECHA ".$FECHA1." ya ha sido insertada para esta auditoria,"; 
	}
}
$respuesta["mensaje"]=$mensaje;

print_r(json_encode($respuesta)); 
?> 
