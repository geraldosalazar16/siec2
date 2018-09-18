<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "TIPO_ASUNTO";
	$correo = "arlette.roman@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
$ID = $objeto-> id_tipo_asunto;  
$DESCRIPCION = $objeto-> descripcion;
$COLOR = $objeto-> color; 
$FECHA_MODIFICACION = date('Y/m/d H:i:s');
$USUARIO_MODIFICACION = $objeto-> id_usuario_modificacion; 
	$id = $database->update($nombre_tabla, [ 

"DESCRIPCION" => $DESCRIPCION, 
"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
"USUARIO_MODIFICACION" => $USUARIO_MODIFICACION,
"COLOR" => $COLOR
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
