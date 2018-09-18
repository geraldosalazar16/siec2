<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "TIPO_ASUNTO";
	$correo = "arlette.roman@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 

	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$DESCRIPCION = $objeto-> descripcion; 
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$USUARIO_CREACION = $objeto-> id_usuario_registro; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$USUARIO_MODIFICACION = $objeto-> id_usuario_modificacion; 
	$COLOR = $objeto-> color;
	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"DESCRIPCION" => $DESCRIPCION,
		"USUARIO_CREACION" => $USUARIO_CREACION, 
		"FECHA_CREACION" => $FECHA_CREACION, 
		"USUARIO_MODIFICACION" => $USUARIO_MODIFICACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
		"COLOR" => $COLOR
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["ID"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
