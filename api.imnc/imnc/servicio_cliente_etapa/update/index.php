<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Falta ID de servicio_cliente_etapa");; 

$ID_CLIENTE = $objeto->ID_CLIENTE; 
valida_parametro_and_die($ID_CLIENTE, "Es necesario seleccionar un cliente");

$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");

$ID_TIPO_SERVICIO	= $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un tipo de servicio");

$ID_NORMA	= $objeto->ID_NORMA; 
valida_parametro_and_die($ID_NORMA, "Es necesario seleccionar una NORMA");

$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO; 
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");

//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
$CAMBIO= $objeto->CAMBIO;
//$ID_REFERENCIA_SEG= $objeto->ID_REFERENCIA_SEG;
//$OBSERVACION_CAMBIO= $objeto->OBSERVACION_CAMBIO;

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");
/****************************************************/
$ID_ETAPA_ANTERIOR	=	$database->get("SERVICIO_CLIENTE_ETAPA","ID_ETAPA_PROCESO",["ID"=>$ID]);
/****************************************************/

$id = $database->update("SERVICIO_CLIENTE_ETAPA", [ 
		"ID_CLIENTE" => $ID_CLIENTE, 
		"ID_SERVICIO" => $ID_SERVICIO,
		"ID_TIPO_SERVICIO"=>	$ID_TIPO_SERVICIO,
		"ID_NORMA"=>	$ID_NORMA,		
		"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO, 
		//"SG_INTEGRAL" => $SG_INTEGRAL, 
		"REFERENCIA" => $REFERENCIA, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"HORA_MODIFICACION" => $HORA_MODIFICACION,
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
		"CAMBIO"=>$CAMBIO,
		//"ID_REFERENCIA_SEG"=>$ID_REFERENCIA_SEG,
		//"OBSERVACION_CAMBIO"=>$OBSERVACION_CAMBIO
	], 
	["ID"=>$ID]
); 
valida_error_medoo_and_die(); 
/*******************************************************/
if($ID_ETAPA_ANTERIOR!=$ID_ETAPA_PROCESO){
	$idet=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
			"ID_SERVICIO_CONTRATADO" => $ID, 
			"MODIFICACION" => "MODIFICACION DE ETAPA", 
			"ESTADO_ANTERIOR"=>	$ID_ETAPA_ANTERIOR,
			"ESTADO_ACTUAL"=>	$ID_ETAPA_PROCESO,
			"USUARIO" => $ID_USUARIO_MODIFICACION, 
			"FECHA_USUARIO" => $FECHA_MODIFICACION,
			"FECHA_MODIFICACION" => date("Ymd"),
	
]); 

}
/*******************************************************/

/*CODIGO PARA OBTENER EL CICLO DE ESTE SERVICIO*/
$C1=explode("-",$REFERENCIA);
$C2=explode("C",$C1[0]);
$CICLO=$C2[1];


$CHK	=	explode(";",$objeto->CHK);
$DESCRIPCION	=	explode(";",$objeto->DESCRIPCION);
$respuesta["resultado"]	=	"ok"; 
$respuesta["id"]	=	$ID_ETAPA_ANTERIOR;

/*Este codigo es para agregar los cambios que se realicen*/
if($CAMBIO=="S"){
	
	for($i=0;$i<count($CHK)-1;$i++){
			
			if($database->count("I_SERVICIOS_CONTRATADOS_CAMBIOS",["AND"=>[
																			"ID_SERVICIO_CONTRATADO"=>$ID,
																			"ID_TIPO_CAMBIO"=>$CHK[$i],
																			"ID_ETAPA"=>$ID_ETAPA_PROCESO,
																			"CICLO"=>$CICLO,]
																	]
								)== 1){
				$camb_desc	=	$database->get("I_SERVICIOS_CONTRATADOS_CAMBIOS","DESCRIPCION",["AND"=>[
																			"ID_SERVICIO_CONTRATADO"=>$ID,
																			"ID_TIPO_CAMBIO"=>$CHK[$i],
																			"ID_ETAPA"=>$ID_ETAPA_PROCESO,
																			"CICLO"=>$CICLO,]
																	]);				
				$id1	=	$database->update("I_SERVICIOS_CONTRATADOS_CAMBIOS",["DESCRIPCION"=>$DESCRIPCION[$i],"FECHA"=>date("Ymd")],["AND"=>[
																			"ID_SERVICIO_CONTRATADO"=>$ID,
																			"ID_TIPO_CAMBIO"=>$CHK[$i],
																			"ID_ETAPA"=>$ID_ETAPA_PROCESO,
																			"CICLO"=>$CICLO,]
																	]);	
				if($camb_desc	!=	$DESCRIPCION[$i]){
					$idc1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
							"ID_SERVICIO_CONTRATADO" => $ID, 
							"MODIFICACION" => "MODIFICACION CAMBIO", 
							"ESTADO_ANTERIOR"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$camb_desc,
							"ESTADO_ACTUAL"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$DESCRIPCION[$i],
							"USUARIO" => $ID_USUARIO_MODIFICACION, 
							"FECHA_USUARIO" => $FECHA_MODIFICACION,
							"FECHA_MODIFICACION" => date("Ymd"),						
					]); 
				}														
			
			}else{
				$id1	=	$database->insert("I_SERVICIOS_CONTRATADOS_CAMBIOS",[
																				"ID_SERVICIO_CONTRATADO"=>$ID,
																				"ID_TIPO_CAMBIO"=>$CHK[$i],
																				"ID_ETAPA"=>$ID_ETAPA_PROCESO,
																				"CICLO"=>$CICLO,
																				"DESCRIPCION"=>$DESCRIPCION[$i],
																				"FECHA"=>date("Ymd")
										]);
				$idc1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
						"ID_SERVICIO_CONTRATADO" => $ID, 
						"MODIFICACION" => "INSERCION CAMBIO", 
						"ESTADO_ANTERIOR"=>	"",
						"ESTADO_ACTUAL"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$DESCRIPCION[$i],
						"USUARIO" => $ID_USUARIO_MODIFICACION, 
						"FECHA_USUARIO" => $FECHA_MODIFICACION,
						"FECHA_MODIFICACION" => date("Ymd"),						
				]); 
			}
			
		
	}
}

print_r(json_encode($respuesta)); 
?> 