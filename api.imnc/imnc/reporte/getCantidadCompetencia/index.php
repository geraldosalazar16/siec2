<?php 
	header("access-control-allow-origin: *");
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@dhttecno.com";
	$respuesta=array(); 
	
	if(isset($_REQUEST["usuario"])){
		$id_usuario = $_REQUEST["usuario"];
				$query = "SELECT P.ID, IFNULL(COUNT(IF(P.USUARIO_CREACION = '".$id_usuario."' ,P.ID,NULL)), 0) AS CONTADOR,COMPETENCIA,P.USUARIO_CREACION 
				FROM PROSPECTO_COMPETENCIA AS PC 
				LEFT JOIN PROSPECTO AS P ON P.ID_COMPETENCIA = PC.ID 
				GROUP BY PC.ID,P.USUARIO_CREACION";
	}else{
		$query = "SELECT P.ID,  IFNULL(COUNT(P.ID), 0) AS CONTADOR,COMPETENCIA
 FROM PROSPECTO_COMPETENCIA AS PC
LEFT JOIN PROSPECTO AS P ON P.ID_COMPETENCIA = PC.ID
GROUP BY PC.ID";
		
	}
	
	
	$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
