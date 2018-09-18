<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PERFILES";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$DESCRIPCION = $objeto->PERFIL; 
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"PERFIL" => $DESCRIPCION, 
		"FECHA_CREACION" => $FECHA_CREACION, 
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$res = $database->select("PERMISOS", "*");
	$ultimo_id = $database->count("PERFIL_PERMISOS","ID");
	for($i = 0 ; $i < sizeof($res);$i++){
		$ultimo_id = $ultimo_id + 1;
		$database->insert("PERFIL_PERMISOS", [
		"ID" => $ultimo_id,
		"ID_PERFIL" =>	$ID,
		"ID_PERMISO" => $res[$i]["ID"],
		"FECHA_CREACION" => $FECHA_CREACION, 
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	]); 
	valida_error_medoo_and_die("PERFIL_PERMISOS",$correo); 
	}
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
