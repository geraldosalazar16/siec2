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
	
	$ultimo_id = $database->max($nombre_tabla,"ID");
	$ID = $ultimo_id + 1;
	$ID_PROSPECTO = $objeto->id_prospecto;
	$ID_AREA=$objeto->area;
	$ID_DEPARTAMENTO = $objeto->departamento; 
	$ID_PRODUCTO= $objeto->producto;
    	valida_parametro_and_die1($ID_PRODUCTO,"Es necesario seleccionar un producto");
    	
	$id = $database->insert($nombre_tabla, [ 
	    "ID" => $ID,
	    "ID_PROSPECTO" => $ID_PROSPECTO,
		"ID_AREA" => $ID_AREA, 
		"ID_DEPARTAMENTO" => $ID_DEPARTAMENTO,
		"ID_PRODUCTO" => $ID_PRODUCTO
	]); 
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
