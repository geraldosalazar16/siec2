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
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("I_AUDITORIAS_VIATICOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id_servicio_cliente_etapa = $objeto->ID; 
valida_parametro_and_die($id_servicio_cliente_etapa, "Falta ID de servicio cliente etapa");

$ID_TA	=	$objeto->TA; 
valida_parametro_and_die($ID_TA, "Falta ID de TIPO de AUDITORIA");

$CICLO	=	$objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta CICLO");

$MONTO	=	$objeto->MONTO; 
valida_parametro_and_die($MONTO, "Falta el MONTO");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_TIPO_AUDITORIA");


/*******************************************************************/

$nombre_tabla = "I_AUDITORIAS_VIATICOS";

	
		if($database->count('I_AUDITORIAS_VIATICOS',['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO]]) != 1){ // AQUI COMPRUEBO SI ES EDICION O NUEVO REGISTRO
			//SI ES DISTINTO DE 1 ES NUEVO REGISTRO
			$id1 = $database->insert($nombre_tabla, [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_TIPO_AUDITORIA"	=> 	$ID_TA,
				"CICLO"	=>	$CICLO,
				"MONTO" =>$MONTO,
				"ID_USUARIO_CREACION"=>$ID_USUARIO,
				"ID_USUARIO_MODIFICACION"=>"",
				"FECHA_CREACION"=>date("Ymd"),
				"FECHA_MODIFICACION"=>""
				]);
			valida_error_medoo_and_die();	
		}
		else{
			// SI ES IGUAL a 1 ES UNA EDICION
			$id1 = $database->update($nombre_tabla, 
					["MONTO" => $MONTO,
					"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
					"FECHA_MODIFICACION"=>date("Ymd")],
					['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO]
				]);			
				valida_error_medoo_and_die();
		}
	
	
	


$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
