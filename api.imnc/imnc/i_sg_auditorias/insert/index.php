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
		$mailerror->send("I_SG_AUDITORIAS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_SERVICIO_CLIENTE_ETAPA = $objeto->ID; 
valida_parametro_and_die($ID_SERVICIO_CLIENTE_ETAPA, "Falta el ID_SERVICIO_CLIENTE_ETAPA");
$DURACION_DIAS = $objeto->DURACION_DIAS;
$CICLO =  $objeto->CICLO;
$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA; 
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");
$STATUS_AUDITORIA = $objeto->STATUS_AUDITORIA; 
valida_parametro_and_die($STATUS_AUDITORIA, "Falta el STATUS_AUDITORIA");
$NO_USA_METODO = $objeto->NO_USA_METODO; 
$SITIOS_AUDITAR = $objeto->SITIOS_AUDITAR; 
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");
$idd = $database->insert("I_SG_AUDITORIAS",
											
											[
												"ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,
												"DURACION_DIAS"=>$DURACION_DIAS,
												"TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
												"CICLO"=>$CICLO,
												"STATUS_AUDITORIA" => $STATUS_AUDITORIA,
												"NO_USA_METODO" => $NO_USA_METODO,
												"SITIOS_AUDITAR" => $SITIOS_AUDITAR,
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												"FECHA_CREACION"=>date("Y-m-d H:i:s"),
												"FECHA_MODIFICACION"=>""
												
												
											]); 
valida_error_medoo_and_die();
if($STATUS_AUDITORIA==2)
{
	$facturacion = $database->get("FACTURACION_SOLICITUDES",["ID"],["AND"=>["ID_SERVICIO_CLIENTE_ETAPA"=>$ID_SERVICIO_CLIENTE_ETAPA,"ID_TIPO_AUDITORIA"=>$TIPO_AUDITORIA,"CICLO"=>$CICLO]]);
	valida_error_medoo_and_die();
    if(!$facturacion)
	{
		$id = $database->insert("FACTURACION_SOLICITUDES",
			[
				"ID_SERVICIO_CLIENTE_ETAPA" => $ID_SERVICIO_CLIENTE_ETAPA,
				"ID_TIPO_AUDITORIA" => $TIPO_AUDITORIA,
				"CICLO" => $CICLO,
				"ID_ESTATUS" => 2,
				"FECHA_CREACION" => $FECHA_CREACION,
				"HORA_CREACION" => $HORA_CREACION,
				"USUARIO_CREACION" => $ID_USUARIO
			]);
		valida_error_medoo_and_die();
	}
}
if($objeto->DURACION_DIAS_INTEGRAL == "NO INTEGRAL"){
	$respuesta["resultado"]="ok"; 
}
else{

	$INPUT	=	json_decode($objeto->DURACION_DIAS_INTEGRAL,true);
	foreach($INPUT as $i => $valor){
	if($database->count("SCE_NORMAS", [	"AND"=>["ID_SCE" => $ID_SERVICIO_CLIENTE_ETAPA,"ID_NORMA"=>$i,"ID_TIPO_AUDITORIA"=>0,"CICLO"=>0]])>0){
	$id1 = $database->update("SCE_NORMAS", [ 
					"DIAS_AUDITOR" => $valor,
					"ID_TIPO_AUDITORIA" => $TIPO_AUDITORIA,
					"CICLO" => $CICLO],[
				"AND"=>["ID_SCE" => $ID_SERVICIO_CLIENTE_ETAPA,"ID_NORMA" => $i,"ID_TIPO_AUDITORIA" => 0,"CICLO" => 0]
				]);
	valida_error_medoo_and_die();
	}
	else{
		$id1 = $database->insert("SCE_NORMAS", [ 
					"ID_SCE" => $ID_SERVICIO_CLIENTE_ETAPA,
					"ID_NORMA"=>$i,
					"DIAS_AUDITOR" => $valor,
					"ID_TIPO_AUDITORIA"=>$TIPO_AUDITORIA,
					"CICLO" => $CICLO]
				);
		valida_error_medoo_and_die();
		}
	
	}
	$respuesta["resultado"]="ok";
}


 
print_r(json_encode($respuesta)); 
?> 
