<?php 
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	if(isset($_REQUEST["usuario"])){
		$id_usuario = $_REQUEST["usuario"];
		$query = "SELECT P.ID,  IFNULL(COUNT(IF(P.USUARIO_CREACION = '".$id_usuario."',P.ID,NULL)), 0) AS CONTADOR,PO.ORIGEN,P.USUARIO_CREACION
 FROM PROSPECTO_ORIGEN AS PO
LEFT JOIN PROSPECTO AS P ON P.ORIGEN = PO.ID
GROUP BY PO.ID,P.USUARIO_CREACION";
	}else{
	$query = "SELECT P.ID,  IFNULL(COUNT(P.ID), 0) AS CONTADOR,PO.ORIGEN
 FROM PROSPECTO_ORIGEN AS PO
LEFT JOIN PROSPECTO AS P ON P.ORIGEN = PO.ID
GROUP BY PO.ID";
	}
	$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
