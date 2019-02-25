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
		$mailerror->send("I_SG_AUDITORIA_GRUPOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
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
$ID_PERSONAL_TECNICO_CALIF = $objeto->ID_PERSONAL_TECNICO_CALIF; 
valida_parametro_and_die($ID_PERSONAL_TECNICO_CALIF, "Falta la ID_PERSONAL_TECNICO_CALIF");
$id1 = $database->delete("I_SG_AUDITORIA_GRUPOS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF"=>$ID_PERSONAL_TECNICO_CALIF]]);
valida_error_medoo_and_die();

if($database->count("I_SG_AUDITORIA_GRUPO_FECHAS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF"=>$ID_PERSONAL_TECNICO_CALIF]])>0){
	$id2 = $database->delete("I_SG_AUDITORIA_GRUPO_FECHAS", ["AND"=>["ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA,"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO,"ID_PERSONAL_TECNICO_CALIF"=>$ID_PERSONAL_TECNICO_CALIF]]);
valida_error_medoo_and_die();
}

$respuesta['resultado']="ok";
$respuesta['id']=$id1;

print_r(json_encode($respuesta));


//-------- FIN --------------
?>