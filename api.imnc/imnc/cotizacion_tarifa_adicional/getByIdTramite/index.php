<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "COTIZACION_TARIFA_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
/*	$tarifas_adicionales = $database->select("COTIZACION_TARIFA_ADICIONAL",["[><]TARIFA_COTIZACION_ADICIONAL"=>["COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL"=>"ID"]], ["TARIFA_COTIZACION_ADICIONAL.DESCRIPCION","TARIFA_COTIZACION_ADICIONAL.TARIFA","COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE","COTIZACION_TARIFA_ADICIONAL.ID"], ["ID_TRAMITE"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tarifas_adicionales)); */
	
	
	
$campos_t = [
	"COTIZACION_TARIFA_ADICIONAL.ID",
	"COTIZACION_TARIFA_ADICIONAL.ID_TRAMITE",
	"COTIZACION_TARIFA_ADICIONAL.ID_TARIFA_ADICIONAL",
	"COTIZACION_TARIFA_ADICIONAL.CANTIDAD",
	"TARIFA_COTIZACION_ADICIONAL.DESCRIPCION",
	"TARIFA_COTIZACION_ADICIONAL.TARIFA"
];

$tarifas_adicionales  = $database->select("COTIZACION_TARIFA_ADICIONAL", ["[>]TARIFA_COTIZACION_ADICIONAL" => ["ID_TARIFA_ADICIONAL" => "ID"]],
	$campos_t, ["ID_TRAMITE"=>$id]);
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($tarifas_adicionales)); 
?> 
