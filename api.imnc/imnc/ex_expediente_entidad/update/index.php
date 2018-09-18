<?php  
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "lqc347@gmail.com";

	$valor=0;
    $id = $_REQUEST["id"]; 
    $estado = $_REQUEST["estado"];
	$id_usuario = $_REQUEST["id_usuario"];
    if($estado==1){
    	$valor=0;
    }else{
    	$valor=1;
    }
	$id = $database->update($nombre_tabla, [ "ESTADO" => $valor,"ID_USUARIO_MODIFICACION"=>$id_usuario ], ["ID"=>$id]); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	print_r(json_encode($respuesta)); 
?> 