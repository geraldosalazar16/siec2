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

$id = $objeto->id_solicitud;
valida_parametro_and_die($id,"Falta ID de Solicitud");
$nuevo_estatus = $objeto->nuevo_estatus;
valida_parametro_and_die($nuevo_estatus,"Falta ID de Estatus");
$id_usuario = $objeto->id_usuario;
valida_parametro_and_die($id_usuario,"Falta ID de Usuario");
$descripcion = $objeto->descripcion;
$estatus_anterior = $objeto->estatus_anterior;


$FECHA = date("Ymd");
$HORA = date("His");
	
$database->update("FACTURACION_SOLICITUDES", 
	[  
		"ID_ESTATUS" => $nuevo_estatus,
		"FECHA_MODIFICACION" => $FECHA,
		"HORA_MODIFICACION" => $HORA,
		"USUARIO_MODIFICACION" => $id_usuario
	],[
		"ID" => $id
	]); 
valida_error_medoo_and_die(); 

$estatus = $database->select("FACTURACION_SOLICITUD_ESTATUS","*");
$nombre_estatus_anterior;
foreach ($estatus as $key => $value) {
	if ($value['ID'] == $estatus_anterior) {
		$nombre_estatus_anterior = $value['ESTATUS'];
	}
}
$nombre_estatus_nuevo;
foreach ($estatus as $key => $value) {
	if ($value['ID'] == $nuevo_estatus) {
		$nombre_estatus_nuevo = $value['ESTATUS'];
	}
}

$cambio = 'De ' . $nombre_estatus_anterior .' a ' . $nombre_estatus_nuevo;
// Actualizar histórico
if ($nuevo_estatus == '2' && $estatus_anterior == '1') { // Emitida
	$descripcion = 'Cambio de Pendiente a Emitida. Factura almacenada';
}
if ($nuevo_estatus == '6' && $estatus_anterior == '2') { // Pagada parcialmente
	$descripcion = 'Cambio de Emitida a Pagada parcialmente. Complemento de pago y evidencia de pago almacenados';
}
if ($nuevo_estatus == '4' && $estatus_anterior == '2') { // Pagada
	$descripcion = 'Cambio de Emitida a Pagada. Evidencia de pago almacenada';
}
if ($nuevo_estatus == '7' && $estatus_anterior == '2') { // Cancelada
	$descripcion = 'Cambio de Emitida a Cancelada. Evidencia de cancelación almacenada';
}

$database->insert("FACTURACION_SOLICITUD_HISTORICO",[
	"ID_SOLICITUD" => $id,
	"CAMBIO" => $cambio,
	"DESCRIPCION" => $descripcion,
	"FECHA" => $FECHA,
	"HORA" => $HORA,
	"USUARIO" => $id_usuario
]);
valida_error_medoo_and_die();

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
