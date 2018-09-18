<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_TABLA_ENTIDADES";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
$ID = $objeto->ID; 
$TABLA = $objeto->TABLA; 
$DESCRIPCION = $objeto->DESCRIPCION; 
	$id = $database->update($nombre_tabla, [ 
"TABLA" => $TABLA, 
"DESCRIPCION" => $DESCRIPCION
	], ["ID"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
