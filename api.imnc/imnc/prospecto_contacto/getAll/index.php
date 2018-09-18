<?php 
	include  '../../ex_common/query.php';
	
	/*
	$nombre_tabla = "PROSPECTO_CONTACTO";
	$correo = "isaurogaleana19@gmail.com";
	$id = $_REQUEST["id"]; 
	
	$respuesta=array(); 
	$campos = [
	"PROSPECTO_CONTACTO.ID",
	"PROSPECTO_CONTACTO.ID_PROSPECTO_DOMICILIO",
	"PROSPECTO_CONTACTO.NOMBRE",
	"PROSPECTO_CONTACTO.CORREO",
	"PROSPECTO_CONTACTO.TELEFONO",
	"PROSPECTO_CONTACTO.CELULAR",
	"PROSPECTO_CONTACTO.PUESTO",
	"PROSPECTO_CONTACTO.ACTIVO",
	"PROSPECTO_CONTACTO.DATOS_ADICIONALES",
	"PROSPECTO_DOMICILIO.NOMBRE(NOMBRE_DOMICILIO)"
	];

	$respuesta = $database->select($nombre_tabla, ["[>]PROSPECTO_DOMICILIO" => ["ID_PROSPECTO_DOMICILIO" => "ID"]], $campos,["PROSPECTO.ID_PROSPECTO"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
	*/
	$nombre_tabla = "PROSPECTO_CONTACTO";
	$correo = "isaurogaleana19@gmail.com";
	$id = $_REQUEST["id"]; 
	
	$respuesta=array(); 
	$campos = [
	"PROSPECTO_CONTACTO.ID",
	"PROSPECTO_CONTACTO.ID_PROSPECTO_DOMICILIO",
	"PROSPECTO_CONTACTO.NOMBRE",
	"PROSPECTO_CONTACTO.CORREO",
	"PROSPECTO_CONTACTO.TELEFONO",
	"PROSPECTO_CONTACTO.CELULAR",
	"PROSPECTO_CONTACTO.PUESTO",
	"PROSPECTO_CONTACTO.ACTIVO",
	"PROSPECTO_CONTACTO.DATOS_ADICIONALES",
	"PROSPECTO_DOMICILIO.NOMBRE(NOMBRE_DOMICILIO)"
	];

	$respuesta = $database->select($nombre_tabla, ["[>]PROSPECTO_DOMICILIO" => ["ID_PROSPECTO_DOMICILIO" => "ID"]], $campos,["PROSPECTO_CONTACTO.ID_PROSPECTO"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
