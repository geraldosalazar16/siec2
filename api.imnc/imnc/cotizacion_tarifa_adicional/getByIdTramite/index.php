<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "COTIZACION_TARIFA_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id = $_REQUEST["id"]; 
	$id_cot = $_REQUEST["id_cot"];
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
	$campos_t, ["AND"=>["ID_TRAMITE"=>$id,"ID_COTIZACION"=>$id_cot]]);
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	for($i =0 ; $i<count($tarifas_adicionales);$i++){
		$tarifas_adicionales[$i]["COSTO_TOTAL"] = $tarifas_adicionales[$i]["TARIFA"]*$tarifas_adicionales[$i]["CANTIDAD"];
	}
	
	print_r(json_encode($tarifas_adicionales)); 
?> 
