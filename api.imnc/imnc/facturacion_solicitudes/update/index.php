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
			$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
			print_r(json_encode($respuesta));
			die(); 
		} 
	} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id = $objeto->id;
valida_parametro_and_die($id,"Falta ID de Solicitud");
$id_sce = $objeto->id_sce;
valida_parametro_and_die($id_sce,"Falta ID de Servicio");
$auditoria = $objeto->auditoria;
if(!$auditoria) {
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = 'Falta seleccionar ua auditoria'; 
	print_r(json_encode($respuesta)); 
	die(); 
}
$id_tipo_auditoria = $auditoria->TIPO_AUDITORIA;
$ciclo = $auditoria->CICLO;
$id_estatus = $objeto->estatus;
valida_parametro_and_die($id_estatus,"Falta seleccionar un estatus");
$id_forma_pago = $objeto->forma_pago;
valida_parametro_and_die($id_forma_pago,"Falta seleccionar una forma de pago");
$id_metodo_pago = $objeto->metodo_pago;
valida_parametro_and_die($id_metodo_pago,"Falta seleccionar un método de pago");
$id_uso_factura = $objeto->uso_factura;
valida_parametro_and_die($id_uso_factura,"Falta seleccionar un uso de la factura");
$razon_social = $objeto->razon_social;
if(!$razon_social) {
	$respuesta["resultado"] = "error"; 
	$respuesta["mensaje"] = 'Falta selecionar una razón social'; 
	print_r(json_encode($respuesta)); 
	die(); 
}
$nombre_rs = $razon_social->NOMBRE;
$rfc = $razon_social->RFC;
$monto = $objeto->monto;
valida_parametro_and_die($monto,"Falta seleccionar un monto");
$requiere_orden_compra = $objeto->orden_compra_requerida;
if (!$requiere_orden_compra) {
	$requiere_orden_compra = 'N';
} else {
	$requiere_orden_compra = 'S';
}
$facturar_viaticos = $objeto->facturar_viaticos_requerido;
if (!$facturar_viaticos) {
	$facturar_viaticos = 'N';
} else {
	$facturar_viaticos = 'S';
}
$subir_factura_portal = $objeto->subir_factura_portal;
if (!$subir_factura_portal) {
	$subir_factura_portal = 'N';
} else {
	$subir_factura_portal = 'S';
}
$portal = $objeto->portal;
if ($subir_factura_portal == 'S') {
	valida_parametro_and_die($portal,"Falta seleccionar una portal");
}
$descripcion = $objeto->descripcion;
$id_usuario = $objeto->id_usuario;
valida_parametro_and_die($id_usuario,"Falta seleccionar un usuario");

$FECHA = date("Ymd");
$HORA = date("His");
	
$id = $database->update("FACTURACION_SOLICITUDES", 
[  
	"ID_SERVICIO_CLIENTE_ETAPA" => $id_sce, 
	"ID_TIPO_AUDITORIA" => $id_tipo_auditoria, 
	"CICLO" => $ciclo,
	"ID_ESTATUS" => $id_estatus,
	"ID_FORMA_PAGO" => $id_forma_pago,
	"ID_METODO_PAGO"=>$id_metodo_pago,
	"ID_USO_FACTURA" => $id_uso_factura, 
	"RAZON_SOCIAL" => $nombre_rs, 
	"RFC" => $rfc,
	"MONTO" => $monto,
	"REQUIERE_ORDEN_COMPRA" => $requiere_orden_compra,
	"DESCRIPCION"=>$descripcion,
	"FACTURAR_VIATICOS" => $facturar_viaticos, 
	"SUBIR_FACTURA_PORTAL" => $subir_factura_portal, 
	"PORTAL" => $portal,
	"FECHA_MODIFICACION" => $FECHA,
	"HORA_MODIFICACION" => $HORA,
	"USUARIO_MODIFICACION" => $id_usuario
],[
	"ID" => $id
]); 

valida_error_medoo_and_die(); 

$database->insert("FACTURACION_SOLICITUD_HISTORICO",[
	"ID_SOLICITUD" => $id,
	"CAMBIO" => 'Solicitud modificada desde Facturación/Solicitudes',
	"DESCRIPCION" => 'Actualización general de datos',
	"FECHA" => $FECHA,
	"HORA" => $HORA,
	"USUARIO" => $id_usuario
]);

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
