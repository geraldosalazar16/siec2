<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PROPUESTA_ESTADO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$DESCRIPCION = $objeto->DESCRIPCION; 
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"ESTADO" => $DESCRIPCION, 
		"FECHA_CREACION" => $FECHA_CREACION, 
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
		"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
