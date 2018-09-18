<?php 
	header("access-control-allow-origin: *");
	include  '../../ex_common/query.php';
	
	$nombre_tabla = "TABLA_ENTIDAD_FEDERATIVA";
	$correo = "leovardo.quintero@dhttecno.com";
	$respuesta=array(); 
	
	if(isset($_REQUEST["usuario"])){
		$id_usuario = $_REQUEST["usuario"];
		$query = "SELECT ENTIDAD_FEDERATIVA,PD.COLONIA,IFNULL(COUNT(PD.COLONIA), 0) AS CONTADOR
FROM TABLA_ENTIDAD_FEDERATIVA AS TEF
LEFT JOIN PROSPECTO_DOMICILIO AS PD ON PD.ESTADO = TEF.ENTIDAD_FEDERATIVA AND  PD.FISCAL = 1
GROUP BY TEF.ENTIDAD_FEDERATIVA";
	}else{
		$query = "SELECT ENTIDAD_FEDERATIVA,PD.COLONIA,IFNULL(COUNT(PD.COLONIA), 0) AS CONTADOR
FROM TABLA_ENTIDAD_FEDERATIVA AS TEF
LEFT JOIN PROSPECTO_DOMICILIO AS PD ON PD.ESTADO = TEF.ENTIDAD_FEDERATIVA AND  PD.FISCAL = 1
GROUP BY TEF.ENTIDAD_FEDERATIVA";
		
	}
	
	
	$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
