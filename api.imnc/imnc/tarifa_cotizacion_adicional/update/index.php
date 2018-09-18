<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "TARIFA_COTIZACION_ADICIONAL";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	$ID = $objeto->ID; 
	$DESCRIPCION = $objeto->DESCRIPCION; 
	valida_parametro_and_die($DESCRIPCION,"Es necesario capturar la descripción"); 
	$TARIFA = $objeto->TARIFA; 
	valida_parametro_and_die($TARIFA,"Es necesario capturar la tarifa");
	$ACTIVO = $objeto->ACTIVO; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s');
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO; 
	$id = $database->update($nombre_tabla, [ 
	"DESCRIPCION" => $DESCRIPCION,  
	"TARIFA" => $TARIFA, 
	"ACTIVO" => $ACTIVO,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
	"USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
