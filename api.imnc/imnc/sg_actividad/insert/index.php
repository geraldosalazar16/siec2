<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "SG_ACTIVIDAD";
	$correo = "jesus.popocatl@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 

	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$ACTIVIDAD = $objeto-> ACTIVIDAD; 
	$FECHA_CREACION = date('Y/m/d H:i:s');
	$USUARIO_CREACION = $objeto-> ID_USUARIO; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
	$USUARIO_MODIFICACION = $objeto-> ID_USUARIO; 

	valida_parametro_and_die($ACTIVIDAD,"Falta Actividad");
	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"ACTIVIDAD" => $ACTIVIDAD,
		"USUARIO_CREACION" => $USUARIO_CREACION, 
		"FECHA_CREACION" => $FECHA_CREACION, 
		"USUARIO_MODIFICACION" => $USUARIO_MODIFICACION, 
		"FECHA_MODIFICACION" => $FECHA_MODIFICACION,
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["ID"]=$ID; 
	print_r(json_encode($respuesta)); 
?> 
