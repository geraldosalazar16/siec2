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
		$mailerror->send("I_CLIENTES_RAZONES_SOCIALES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID; 
valida_parametro_and_die($ID, "Falta el ID");
$CLIENTE = $objeto->CLIENTE; 
valida_parametro_and_die($CLIENTE, "Falta el CLIENTE");
$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Falta el NOMBRE");
$RFC = $objeto->RFC; 
valida_parametro_and_die($RFC, "Falta el RFC");
$EF = $objeto->EF; 

$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_MODIFICACION = date("Ymd");
$count1 = $database->count("I_CLIENTES_RAZONES_SOCIALES",['AND'=>['ID_CLIENTE'=>$CLIENTE,'RFC'=>$RFC,'ID[!]'=>$ID]]);
$count2 = $database->count("CLIENTES",['AND'=>['ID'=>$CLIENTE,'RFC'=>$RFC]]);
if($EF == 'N'){ 
	$ss= $database->get("CLIENTES",['RFC_FACTURARIO'],['ID'=>$ID]);
	if($ss['RFC_FACTURARIO']	== $RFC ){
		$count3=0;
	}else{
	
		$count3 = $database->count("CLIENTES",['AND'=>['ID'=>$CLIENTE,'RFC_FACTURARIO'=>$RFC]]);

	}	
}
else{
	$count3 = $database->count("CLIENTES",['AND'=>['ID'=>$CLIENTE,'RFC_FACTURARIO'=>$RFC]]);
}

if(($count1+$count2+$count3)==0){
	if($EF == 'N'){
	$idd = $database->update("CLIENTES",
											
											[
												
												"CLIENTE_FACTURARIO"=>$NOMBRE,
												"RFC_FACTURARIO"=>$RFC,
												"ID_USUARIO_CREACION"=>"",
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												"FECHA_CREACION"=>"",
												"FECHA_MODIFICACION"=>$FECHA_MODIFICACION
												
												
											],["ID"=>$ID]); 
	valida_error_medoo_and_die();
	$respuesta["resultado"]="ok"; 
	}else{
		$idd = $database->update("I_CLIENTES_RAZONES_SOCIALES",
											
											[
												
												"NOMBRE"=>$NOMBRE,
												"RFC"=>$RFC,
												"ID_USUARIO_CREACION"=>"",
												"ID_USUARIO_MODIFICACION"=>$ID_USUARIO,
												"FECHA_CREACION"=>"",
												"FECHA_MODIFICACION"=>$FECHA_MODIFICACION
												
												
											],["ID"=>$ID]); 
	
		valida_error_medoo_and_die();
		$respuesta["resultado"]="ok"; 
	}
}
else{
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Este cliente ya tiene esta razon social."; 
		
}


	 



print_r(json_encode($respuesta)); 
?> 
