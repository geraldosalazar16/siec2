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
		$mailerror->send("I_CLIENTES_DATOS_FACTURACION", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$CLIENTE = $objeto->CLIENTE; 
valida_parametro_and_die($CLIENTE, "Falta el CLIENTE");
$FORMA_DE_PAGO = $objeto->FORMA_DE_PAGO;
valida_parametro_and_die($FORMA_DE_PAGO, "Falta la FORMA_DE_PAGO");
$METODO_DE_PAGO = $objeto->METODO_DE_PAGO; 
valida_parametro_and_die($METODO_DE_PAGO, "Falta la METODO_DE_PAGO");
$USO_DE_LA_FACTURA = $objeto->USO_DE_LA_FACTURA; 
valida_parametro_and_die($USO_DE_LA_FACTURA, "Falta el USO_DE_LA_FACTURA");
$ID_USUARIO = $objeto->ID_USUARIO; 
valida_parametro_and_die($ID_USUARIO, "Falta el ID_USUARIO");
$FECHA_CREACION = date("Ymd");
if($database->count("I_CLIENTES_DATOS_FACTURACION",['ID_CLIENTE'=>$CLIENTE])!=1){
	$idd = $database->insert("I_CLIENTES_DATOS_FACTURACION",
											
											[
												"ID_CLIENTE"=>$CLIENTE,
												"ID_FORMA_D_PAGO"=>$FORMA_DE_PAGO,
												"ID_METODO_D_PAGO"=>$METODO_DE_PAGO,
												"ID_USO_D_L_FACTURA"=>$USO_DE_LA_FACTURA,
												"ID_USUARIO_CREACION"=>$ID_USUARIO,
												"ID_USUARIO_MODIFICACION"=>"",
												"FECHA_CREACION"=>$FECHA_CREACION,
												"FECHA_MODIFICACION"=>""
												
												
											]); 
valida_error_medoo_and_die();
$respuesta["resultado"]="ok";  
}
else{
		$respuesta["resultado"]="error"; 
		$respuesta["mensaje"]="Este cliente ya tiene insertados datos"; 
		
}


print_r(json_encode($respuesta)); 
?> 
