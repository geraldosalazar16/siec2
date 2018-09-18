<?php  
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "EX_TABLA_ENTIDADES";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json); 

	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$TABLA = $objeto->TABLA; 
	$DESCRIPCION = $objeto->DESCRIPCION; 
	$id = $database->insert($nombre_tabla, [
		"ID" => $ID,	
		"TABLA" => $TABLA, 
		"DESCRIPCION" => $DESCRIPCION		
	]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id; 
	print_r(json_encode($respuesta)); 
?> 
