<?php 
include  '../../ex_common/query.php'; 

$nombre_tabla = "PROSPECTO_CITAS";
$correo = "leovardo.quintero@dhttecno.com";
$id = $_REQUEST["id"];
$entidad = $_REQUEST["entidad"];

$respuesta=array(); 

	if($entidad == 1){
		$query = "SELECT ID_CLIENTE FROM PROSPECTO WHERE ID = ".$id;
		$otro_id = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
		if(sizeof($otro_id) > 0 && $otro_id[0]["ID_CLIENTE"] != 0){
			$respuesta = $database->select($nombre_tabla,["[>]TIPO_ASUNTO"=>["TIPO_ASUNTO"=>"ID"]],
						[
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ID_COTIZACION(id_cotizacion)",
		"COLOR(color)",
		], ["OR" => ["AND" =>[
		"ID_PROSPECTO"=>$id , "ENTIDAD" => 1
		]],["AND" => ["ID_PROSPECTO"=>$otro_id[0]["ID_CLIENTE"] , "ENTIDAD" => 2]]]); 
		valida_error_medoo_and_die($nombre_tabla,$correo); 
		}else{
			$respuesta = $database->select($nombre_tabla,["[>]TIPO_ASUNTO"=>["TIPO_ASUNTO"=>"ID"]],
						[
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ID_COTIZACION(id_cotizacion)",
		"COLOR(color)",
		], ["AND" =>[
		"ID_PROSPECTO"=>$id , "ENTIDAD" => 1
		]]); 
		valida_error_medoo_and_die($nombre_tabla,$correo); 
		}
	}else if($entidad == 2){
		$query = "SELECT ID FROM PROSPECTO WHERE ID_CLIENTE = ".$id;
		
		$otro_id = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);

		if(sizeof($otro_id) > 0 && $otro_id[0]["ID"] != 0){
			$respuesta = $database->select($nombre_tabla,["[>]TIPO_ASUNTO"=>["TIPO_ASUNTO"=>"ID"]],
						[
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ID_COTIZACION(id_cotizacion)",
		"COLOR(color)",
		], ["OR" => ["AND" =>[
		"ID_PROSPECTO"=>$id , "ENTIDAD" => 2
		]],["AND" => ["ID_PROSPECTO"=>$otro_id[0]["ID"] , "ENTIDAD" => 1]]]); 
		valida_error_medoo_and_die($nombre_tabla,$correo); 
		}else{
			$respuesta = $database->select($nombre_tabla,["[>]TIPO_ASUNTO"=>["TIPO_ASUNTO"=>"ID"]],
						[
		"PROSPECTO_CITAS.ID(id_calendario)",
		"ASUNTO(asunto)",
		"FECHA_INICIO(fecha_inicio)",
		"FECHA_FIN(fecha_fin)",
		"TIPO_ASUNTO(tipo_asunto)",
		"RECORDATORIO(recordatorio)",
		"ID_PROSPECTO(id_prospecto)",
		"OBSERVACIONES(observaciones)",
		"ID_COTIZACION(id_cotizacion)",
		"COLOR(color)",
		], ["AND" =>[
		"ID_PROSPECTO"=>$id , "ENTIDAD" => 2
		]]); 
		valida_error_medoo_and_die($nombre_tabla,$correo); 
		}
	}
	



	print_r(json_encode($respuesta));
 ?>