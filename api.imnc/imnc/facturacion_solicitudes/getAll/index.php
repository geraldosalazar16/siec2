<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php';  

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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
} 

$respuesta=array(); 

$solicitudes = $database->select("FACTURACION_SOLICITUDES", [
	"[>]SERVICIO_CLIENTE_ETAPA" => ["ID_SERVICIO_CLIENTE_ETAPA" => "ID"],
	"[>]CLIENTES" => ["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE" => "ID"],
	"[>]FACTURACION_SOLICITUD_ESTATUS" => ["ID_ESTATUS" => "ID"],
	"[>]I_CAT_FORMA_D_PAGO" => ["ID_FORMA_PAGO" => "ID"],
	"[>]I_CAT_METODO_D_PAGO" => ["ID_METODO_PAGO" => "ID"],
	"[>]I_CAT_USO_D_L_FACTURA" => ["ID_USO_FACTURA" => "ID"]
],[
	"FACTURACION_SOLICITUDES.ID",
	"FACTURACION_SOLICITUDES.ID_ESTATUS",
	"FACTURACION_SOLICITUD_ESTATUS.ESTATUS",
	"FACTURACION_SOLICITUDES.ID_FORMA_PAGO",
	"I_CAT_FORMA_D_PAGO.NOMBRE(FORMA_PAGO)",
	"FACTURACION_SOLICITUDES.ID_METODO_PAGO",
	"I_CAT_METODO_D_PAGO.NOMBRE(METODO_PAGO)",
	"FACTURACION_SOLICITUDES.ID_USO_FACTURA",
	"I_CAT_USO_D_L_FACTURA.NOMBRE(USO_FACTURA)",
	"FACTURACION_SOLICITUDES.RAZON_SOCIAL",
	"FACTURACION_SOLICITUDES.RFC",
	"CLIENTES.NOMBRE(CLIENTE)",
	"FACTURACION_SOLICITUDES.MONTO",
	"FACTURACION_SOLICITUDES.REQUIERE_ORDEN_COMPRA",
	"FACTURACION_SOLICITUDES.DESCRIPCION",
	"FACTURACION_SOLICITUDES.FACTURAR_VIATICOS",
	"FACTURACION_SOLICITUDES.SUBIR_FACTURA_PORTAL",
	"FACTURACION_SOLICITUDES.PORTAL"
]);
valida_error_medoo_and_die();

print_r(json_encode($solicitudes)); 
?> 
