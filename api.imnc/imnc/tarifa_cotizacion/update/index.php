<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "TARIFA_COTIZACION";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	$ID = $objeto->ID; 
	$DESCRIPCION = $objeto->DESCRIPCION; 
	valida_parametro_and_die($DESCRIPCION,"Es necesario capturar la descripción"); 
	$TARIFA = $objeto->TARIFA; 
	valida_parametro_and_die($TARIFA,"Es necesario capturar la tarifa");
	$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO; 
	valida_parametro_and_die($ID_TIPO_SERVICIO,"Es necesario capturar el tipo de servicio");
	$ACTIVO = $objeto->ACTIVO; 
	$FECHA_MODIFICACION = date('Y/m/d H:i:s');
	$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO; 
	$id = $database->update($nombre_tabla, [ 
	"DESCRIPCION" => $DESCRIPCION,  
	"TARIFA" => $TARIFA, 
	"ACTIVO" => $ACTIVO,
	"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
	"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
	"USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
