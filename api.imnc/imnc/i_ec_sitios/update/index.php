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
		$mailerror->send("I_EC_SITIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id_servicio_cliente_etapa = $objeto->ID; 
valida_parametro_and_die($id_servicio_cliente_etapa, "Falta ID de servicio cliente etapa");

$DOMICILIO	=	$objeto->DOMICILIO; 
valida_parametro_and_die($DOMICILIO, "Falta ID de servicio cliente etapa");

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");

/*******************************************************************/
$INPUT	=	json_decode($objeto->INPUT,true);
$nombre_tabla = "I_EC_SITIOS";
foreach($INPUT as $i => $valor){
	$id1 = $database->insert($nombre_tabla, [ 
				"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa, 
				"ID_CLIENTE_DOMICILIO"	=> 	$DOMICILIO,
				"ID_META_SITIOS"	=>	$i,
				"VALOR" => $valor, 
				
				]);
	$id1 = $database->update($nombre_tabla, 
					["VALOR" => $valor ],
					["AND"=>["ID_META_SITIOS"	=>	$i,"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa,"ID_CLIENTE_DOMICILIO"	=> 	$DOMICILIO]
				]);			
	valida_error_medoo_and_die();
}
/*******************************************************************/

//$INPUT = explode(";",$objeto->INPUT); 
//$KEY	=	explode(";",$objeto->KEY);
/*
///////////////////////////////////////////////////////////////////////////////////////////////////
$nombre_tabla = "I_TIPOS_SERVICIOS";
for($i=0;$i<count($INPUT)-1;$i++){
	$id1 = $database->update($nombre_tabla, ["VALOR" => $INPUT[$i] ],["AND"=>["ID_META_SCE"	=>	$KEY[$i],"ID_SERVICIO_CLIENTE_ETAPA" => $id_servicio_cliente_etapa]]);
	valida_error_medoo_and_die();
}

///////////////////////////////////////////////////////////////////////////////////////////////////
*/
$respuesta["resultado"]="ok"; 
//$respuesta["prueba"]=$a; 
//$respuesta["prueba1"]=$INPUT; 
print_r(json_encode($respuesta)); 
?> 
