<?php  
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_TIPO_EXPEDIENTE";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
$NOMBRE = $objeto->NOMBRE; 
$DESCRIPCION = $objeto->DESCRIPCION; 
$VIGENTE = $objeto->VIGENTE;
$ID_EXP_ANT = $objeto->ID_EXP_ANT; 
$FECHA_CREACION = date('Y/m/d H:i:s'); 
$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert($nombre_tabla, [ 
	"ID" => $ID,
"NOMBRE" => $NOMBRE, 
"DESCRIPCION" => $DESCRIPCION, 
"VIGENTE" => 0,
"ID_EXP_ANT" => $ID_EXP_ANT,
"FECHA_CREACION" => $FECHA_CREACION, 
"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION, 
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 

	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
