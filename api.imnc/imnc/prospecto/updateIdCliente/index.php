<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "PROSPECTO";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json);

    $ID=$_REQUEST["ID"];
	$ID_CLIENTE = $_REQUEST["ID_CLIENTE"];
    
      
	$id = $database->update($nombre_tabla, [ 
		
		"ID_CLIENTE" => $ID_CLIENTE
	], ["ID"=>$ID]); 
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 
