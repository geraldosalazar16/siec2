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
		$mailerror->send("I_AUDITORIAS_COSTOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
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

$ID_PT	=	$objeto->ID_PT; 
valida_parametro_and_die($ID_PT, "Falta ID de PERSONAL TECNICO");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_TIPO_AUDITORIA");


/*******************************************************************/
$INPUT	=	json_decode($objeto->INPUT,true);
// AQUI BUSCO LOS GASTOS DEFINIDOS POR CATALOGO
$catalogo = $database->select('I_CAT_AUDITORIAS_COSTOS',['ID','NOMBRE'],['ORDER'=>'PRIORIDAD']);
valida_error_medoo_and_die();
$nombre_tabla = "I_AUDITORIAS_COSTOS";
for($i=0;$i<count($catalogo);$i++){
	if($INPUT[$i]['VALOR'] != 0){ // AQUI COMPRUEBO SI EL GASTO ES DISTINTO DE 0
		if($database->count('I_AUDITORIAS_COSTOS',['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO,'ID_PERSONAL_TECNICO_CALIF'=>$ID_PT,'ID_CAT_AUDITORIAS_COSTOS'=>$catalogo[$i]['ID']]]) != 1){ // AQUI COMPRUEBO SI ES EDICION O NUEVO REGISTRO
			//SI ES DISTINTO DE 1 ES NUEVO REGISTRO
			$id1 = $database->insert($nombre_tabla, [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_TIPO_AUDITORIA"	=> 	$ID_TA,
				"CICLO"	=>	$CICLO,
				"ID_PERSONAL_TECNICO_CALIF" => $ID_PT, 
				"ID_CAT_AUDITORIAS_COSTOS" => $catalogo[$i]['ID'],
				"MONTO" =>$INPUT[$i]['VALOR'],
				"ID_USUARIO_CREACION"=>$ID_USUARIO,
				"ID_USUARIO_MODIFICACION"=>"",
				"FECHA_CREACION"=>date("Ymd"),
				"FECHA_MODIFICACION"=>""
				]);
				valida_error_medoo_and_die();	
			/*
			if($catalogo[$i]['ID'] != 4 &&  $catalogo[$i]['ID'] != 6 ){
				$id1 = $database->insert($nombre_tabla, [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_TIPO_AUDITORIA"	=> 	$ID_TA,
				"CICLO"	=>	$CICLO,
				"ID_PERSONAL_TECNICO_CALIF" => $ID_PT, 
				"ID_CAT_AUDITORIAS_COSTOS" => $catalogo[$i]['ID'],
				"MONTO" =>$INPUT[$i]['VALOR'],
				"ID_USUARIO_CREACION"=>$ID_USUARIO,
				"ID_USUARIO_MODIFICACION"=>"",
				"FECHA_CREACION"=>date("Ymd"),
				"FECHA_MODIFICACION"=>""
				]);
				valida_error_medoo_and_die();	
				if($catalogo[$i]['ID'] == 3){
					$id1 = $database->insert($nombre_tabla, [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_TIPO_AUDITORIA"	=> 	$ID_TA,
						"CICLO"	=>	$CICLO,
						"ID_PERSONAL_TECNICO_CALIF" => $ID_PT, 
						"ID_CAT_AUDITORIAS_COSTOS" => 4,
						"MONTO" =>$INPUT[$i]['VALOR']*0.84,
						"ID_USUARIO_CREACION"=>$ID_USUARIO,
						"ID_USUARIO_MODIFICACION"=>"",
						"FECHA_CREACION"=>date("Ymd"),
						"FECHA_MODIFICACION"=>""
						]);
						valida_error_medoo_and_die();
					
				}
				if($catalogo[$i]['ID'] == 5){
					$id1 = $database->insert($nombre_tabla, [ 
						"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
						"ID_TIPO_AUDITORIA"	=> 	$ID_TA,
						"CICLO"	=>	$CICLO,
						"ID_PERSONAL_TECNICO_CALIF" => $ID_PT, 
						"ID_CAT_AUDITORIAS_COSTOS" => 6,
						"MONTO" =>$INPUT[$i]['VALOR']*0.84,
						"ID_USUARIO_CREACION"=>$ID_USUARIO,
						"ID_USUARIO_MODIFICACION"=>"",
						"FECHA_CREACION"=>date("Ymd"),
						"FECHA_MODIFICACION"=>""
						]);
						valida_error_medoo_and_die();
					
				}
			}
			else{
				continue;
			}
			*/
		}
		else{
			// SI ES IGUAL a 1 ES UNA EDICION
			$id1 = $database->update($nombre_tabla, 
					["MONTO" => $INPUT[$i]['VALOR'],
					"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
					"FECHA_MODIFICACION"=>date("Ymd")],
					['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO,'ID_PERSONAL_TECNICO_CALIF'=>$ID_PT,'ID_CAT_AUDITORIAS_COSTOS'=>$catalogo[$i]['ID']]
				]);			
				valida_error_medoo_and_die();
			/*
			if($catalogo[$i]['ID'] != 4 &&  $catalogo[$i]['ID'] != 6){
				$id1 = $database->update($nombre_tabla, 
					["MONTO" => $INPUT[$i]['VALOR'],
					"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
					"FECHA_MODIFICACION"=>date("Ymd")],
					['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO,'ID_PERSONAL_TECNICO_CALIF'=>$ID_PT,'ID_CAT_AUDITORIAS_COSTOS'=>$catalogo[$i]['ID']]
				]);			
				valida_error_medoo_and_die();
				if($catalogo[$i]['ID'] == 3){
					$id1 = $database->update($nombre_tabla, 
						["MONTO" => $INPUT[$i]['VALOR']*0.84,
						"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
						"FECHA_MODIFICACION"=>date("Ymd")],
						['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO,'ID_PERSONAL_TECNICO_CALIF'=>$ID_PT,'ID_CAT_AUDITORIAS_COSTOS'=>4]
					]);			
					valida_error_medoo_and_die();
				}
				if($catalogo[$i]['ID'] == 5){
					$id1 = $database->update($nombre_tabla, 
						["MONTO" => $INPUT[$i]['VALOR']*0.84,
						"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
						"FECHA_MODIFICACION"=>date("Ymd")],
						['AND'=>['ID_SERVICIO_CLIENTE_ETAPA'=>$id_servicio_cliente_etapa,'ID_TIPO_AUDITORIA'=>$ID_TA,'CICLO'=>$CICLO,'ID_PERSONAL_TECNICO_CALIF'=>$ID_PT,'ID_CAT_AUDITORIAS_COSTOS'=>6]
					]);			
					valida_error_medoo_and_die();
				}
			}
			else{
				continue;
			}	*/	
		}
	}
	
	
}

$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
