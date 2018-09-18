<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@gmail.com";
	$tabla_cliente = ["[>]PROSPECTO_ORIGEN(p_origen)" => ["ORIGEN" => "ID"], 
	"[>]USUARIOS(user_creacion)" => ["USUARIO_CREACION" => "ID"], 
	"[>]USUARIOS(user_modificacion)" => ["USUARIO_MODIFICACION" => "ID"],
	"[>]PROSPECTO_COMPETENCIA(p_competencia)" => ["ID_COMPETENCIA" => "ID"],
	"[>]PROSPECTO_ESTATUS_SEGUIMIENTO(p_estatus_seguimiento)" => ["ID_ESTATUS_SEGUIMIENTO" => "ID"],
	"[>]PROSPECTO_TIPO_CONTRATO(p_tipo_contrato)" => ["ID_TIPO_CONTRATO" => "ID"],
	"[>]PROSPECTO_PORCENTAJE(p_porcentaje)" => ["ID_PORCENTAJE" => "ID"]];
	$campos = ["PROSPECTO.ID(ID)","ID_CLIENTE", "PROSPECTO.RFC(RFC)", "PROSPECTO.NOMBRE(NOMBRE)","PROSPECTO.ORIGEN(ORIGEN)", "p_origen.ORIGEN(NOMBRE_ORIGEN)",
	"PROSPECTO.ID_COMPETENCIA","p_competencia.COMPETENCIA(NOMBRE_COMPETENCIA)",
	"PROSPECTO.ID_ESTATUS_SEGUIMIENTO","p_estatus_seguimiento.ESTATUS_SEGUIMIENTO(NOMBRE_ESTATUS_SEGUIMIENTO)",
	"PROSPECTO.ID_TIPO_CONTRATO","p_tipo_contrato.TIPO_CONTRATO(NOMBRE_TIPO_CONTRATO)", 
	"GIRO", "PROSPECTO.FECHA_CREACION", "PROSPECTO.FECHA_MODIFICACION", "ACTIVO", "user_creacion.NOMBRE(USUARIO_CREACION)", 
	"user_modificacion.NOMBRE(USUARIO_MODIFICACION)","p_porcentaje.PORCENTAJE(PORCENTAJE)","p_porcentaje.ID(ID_PORCENTAJE)","PROSPECTO.ID_USUARIO_PRINCIPAL","PROSPECTO.ID_USUARIO_SECUNDARIO"];
	$id = $_REQUEST["id"]; 
	$tipo_documento = $database->get($nombre_tabla, $tabla_cliente, $campos, ["PROSPECTO.ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo );
	print_r(json_encode($tipo_documento)); 
?> 
