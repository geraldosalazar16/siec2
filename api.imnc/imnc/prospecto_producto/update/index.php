<?php  
	include  '../../ex_common/query.php';
function valida_parametro_and_die1($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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
	
	$ID_PROSPECTO = $objeto->id_prospecto;
	valida_parametro_and_die1($ID_PROSPECTO,"Es necesario seleccionar un prospecto");
	$ID_SERVICIO=$objeto->area;
	valida_parametro_and_die1($ID_SERVICIO,"Es necesario seleccionar un servicio");
	$ID_TIPO_SERVICIO = $objeto->departamento; 
	valida_parametro_and_die1($ID_TIPO_SERVICIO,"Es necesario seleccionar un tipo de servicio");
	$ID_NORMA= $objeto->producto;
	valida_parametro_and_die1($ID_NORMA,"Es necesario seleccionar una norma");
	$ALCANCE= $objeto->alcance;
	if(!$ALCANCE){
		$ALCANCE = "";
	}
      
	$id = $database->update($nombre_tabla, [ 
		"ID_SERVICIO" => $ID_SERVICIO, 
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"ID_NORMA" => $ID_NORMA,
		"ALCANCE" => $ALCANCE
	], ["AND" => [
			"ID_PROSPECTO"=>$ID_PROSPECTO,
			"ID_SERVICIO" => $ID_SERVICIO, 
			"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
			"ID_NORMA" => $ID_NORMA
		]
	]); 
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 