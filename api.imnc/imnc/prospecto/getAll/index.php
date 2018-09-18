<?php 
	include  '../../ex_common/query.php';
	
$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@gmail.com";
	$tabla_cliente = ["[>]PROSPECTO_ESTATUS_SEGUIMIENTO(p_estatus_seguimiento)" => ["ID_ESTATUS_SEGUIMIENTO" => "ID"],
	"[>]PROSPECTO_TIPO_CONTRATO(p_tipo_contrato)" => ["ID_TIPO_CONTRATO" => "ID"],
	"[>]PROSPECTO_PORCENTAJE(p_porcentaje)" => ["ID_PORCENTAJE" => "ID"]];
	$campos = ["PROSPECTO.ID(ID)","ID_CLIENTE", "PROSPECTO.RFC(RFC)", "PROSPECTO.NOMBRE(NOMBRE)","PROSPECTO.ORIGEN(ORIGEN)",
	"PROSPECTO.ID_ESTATUS_SEGUIMIENTO","p_estatus_seguimiento.ESTATUS_SEGUIMIENTO(NOMBRE_ESTATUS_SEGUIMIENTO)",
	"PROSPECTO.ID_TIPO_CONTRATO","p_tipo_contrato.TIPO_CONTRATO(NOMBRE_TIPO_CONTRATO)", 
	"GIRO", "PROSPECTO.FECHA_CREACION", "PROSPECTO.FECHA_MODIFICACION", "ACTIVO","p_porcentaje.PORCENTAJE(PORCENTAJE)" ];
 
	$tipo_documento = $database->select($nombre_tabla, $tabla_cliente, $campos); 
	valida_error_medoo_and_die($nombre_tabla ,$correo );
	print_r(json_encode($tipo_documento)); 
