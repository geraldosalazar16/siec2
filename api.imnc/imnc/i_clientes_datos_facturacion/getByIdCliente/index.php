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
		die(); 
	} 
} 

$id_cliente = $_REQUEST["id"];
$valores = $database->select('I_CLIENTES_DATOS_FACTURACION',
							[
								"[><]CLIENTES"=>["I_CLIENTES_DATOS_FACTURACION.ID_CLIENTE"=>"ID"],
								"[><]I_CAT_FORMA_D_PAGO"=>["I_CLIENTES_DATOS_FACTURACION.ID_FORMA_D_PAGO"=>"ID"],
								"[><]I_CAT_METODO_D_PAGO"=>["I_CLIENTES_DATOS_FACTURACION.ID_METODO_D_PAGO"=>"ID"],
								"[><]I_CAT_USO_D_L_FACTURA"=>["I_CLIENTES_DATOS_FACTURACION.ID_USO_D_L_FACTURA"=>"ID"]
							],
							[
								'I_CLIENTES_DATOS_FACTURACION.ID',
								'CLIENTES.NOMBRE(NOMBRE_CLIENTE)',
								'I_CAT_FORMA_D_PAGO.ID(ID_FORMA_D_PAGO)',
								'I_CAT_FORMA_D_PAGO.NOMBRE(NOMBRE_FORMA_D_PAGO)',
								'I_CAT_METODO_D_PAGO.ID(ID_METODO_D_PAGO)',
								'I_CAT_METODO_D_PAGO.NOMBRE(NOMBRE_METODO_D_PAGO)',
								'I_CAT_USO_D_L_FACTURA.ID(ID_USO_D_L_FACTURA)',
								'I_CAT_USO_D_L_FACTURA.NOMBRE(NOMBRE_USO_D_L_FACTURA)'
							],['ID_CLIENTE'=>$id_cliente]);
valida_error_medoo_and_die();




print_r(json_encode($valores)); 
?> 
