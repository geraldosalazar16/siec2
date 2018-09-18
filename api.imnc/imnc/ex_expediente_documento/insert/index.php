<?php  
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_DOCUMENTO";
	$correo = "lqc347@gmail.com";

	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
$ID_EXPEDIENTE = $objeto->ID_EXPEDIENTE; 
$ID_DOCUMENTO = $objeto->ID_DOCUMENTO; 
$OBLIGATORIO = $objeto->OBLIGATORIO;
$HABILITADO = $objeto->HABILITADO; 
$FECHA_CREACION = date('Y/m/d H:i:s'); 
$ID_USUARIO_CREACION = $objeto->ID_USUARIO_CREACION; 
$FECHA_MODIFICACION = date('Y/m/d H:i:s'); 
$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO_MODIFICACION; 
	$id = $database->insert($nombre_tabla, [ 
	"ID" => $ID,
"ID_EXPEDIENTE" => $ID_EXPEDIENTE, 
"ID_DOCUMENTO" => $ID_DOCUMENTO, 
"OBLIGATORIO" => $OBLIGATORIO,
"HABILITADO" => $HABILITADO,
"FECHA_CREACION" => $FECHA_CREACION, 
"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION, 
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo);

	$nombre_tabla = "EX_TIPO_EXPEDIENTE";
	$id = $database->update($nombre_tabla, [ 
"VIGENTE" => 1,
"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION
	], ["AND" => ["ID"=>$ID_EXPEDIENTE, "VIGENTE" => 0] ]); 
	valida_error_medoo_and_die($nombre_tabla,$correo);

	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
