<?php 
	header("access-control-allow-origin: *");
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array(); 
	if(isset($_REQUEST["usuario"])){
		$id_usuario = $_REQUEST["usuario"];
		$query = "SELECT P.ID,  IFNULL(COUNT(P.ID), 0) AS CONTADOR,PES.ESTATUS_SEGUIMIENTO
 FROM PROSPECTO_ESTATUS_SEGUIMIENTO AS PES
LEFT JOIN PROSPECTO AS P ON P.ID_ESTATUS_SEGUIMIENTO = PES.ID
GROUP BY PES.ID";
	}else{
	$query = "SELECT P.ID,  IFNULL(COUNT(P.ID), 0) AS CONTADOR,PES.ESTATUS_SEGUIMIENTO
 FROM PROSPECTO_ESTATUS_SEGUIMIENTO AS PES
LEFT JOIN PROSPECTO AS P ON P.ID_ESTATUS_SEGUIMIENTO = PES.ID
GROUP BY PES.ID";
	}
	$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
