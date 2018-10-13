<?php  
	include  '../../ex_common/query.php';
function valida_parametro_and_die1($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro) or $parametro == "ninguno" or $parametro == 0 or $parametro == "elige") { 
		$respuesta["resultado"] = "error"; 
		$respuesta["mensaje"] = $mensaje_error; 
		print_r(json_encode($respuesta)); 
		die(); 
	} 
}	
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);
	
	//$ID = $objeto->id;
	$ID = $_REQUEST["id"];
      
	$id_producto = $database->delete($nombre_tabla, ["ID"=>$ID]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	//Borrar de prospecto_sectores
	$id_producto = $database->delete("PROSPECTO_SECTORES", ["ID_PRODUCTO"=>$ID]); 
	valida_error_medoo_and_die("PROSPECTO_SECTORES",$correo); 
	//Borrar de prospecto_producto_normas
	$id_producto = $database->delete("PROSPECTO_PRODUCTO_NORMAS", ["ID_PRODUCTO"=>$ID]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	
	valida_error_medoo_and_die("PROSPECTO_PRODUCTO_NORMAS",$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 