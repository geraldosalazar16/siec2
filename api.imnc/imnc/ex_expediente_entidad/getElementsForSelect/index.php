<?php
	include  '../../ex_common/query.php';  
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "lqc347@gmail.com";
	
	$tipo = $_REQUEST["tipo"];
	$id_expediente = $_REQUEST["id_expediente"];
	$query = "";
	if($tipo == 1){
		$query = "SELECT ID,DESCRIPCION";
		$query .= " FROM EX_TABLA_ENTIDADES";
		$query .= " WHERE EX_TABLA_ENTIDADES.ID NOT IN (";
		$query .= " SELECT ID_ENTIDAD FROM EX_EXPEDIENTE_ENTIDAD WHERE ID_TIPO_EXPEDIENTE = ".$database->quote($id_expediente)." AND tipo = ".$database->quote($tipo).")";
	}else{
		$query = "SELECT ID_ETAPA AS ID,CONCAT(ETAPA,' - ',SERVICIOS.NOMBRE) AS DESCRIPCION";
		$query .= " FROM ETAPAS_PROCESO,SERVICIOS";
		$query .= " WHERE SERVICIOS.ID = ETAPAS_PROCESO.ID_SERVICIO AND ETAPAS_PROCESO.ID_ETAPA NOT IN (";
		$query .= " SELECT ID_ENTIDAD FROM EX_EXPEDIENTE_ENTIDAD WHERE ID_TIPO_EXPEDIENTE = ".$database->quote($id_expediente)." AND tipo = ".$database->quote($tipo).")";
	}
	$elementos = $database->query($query)->fetchAll(); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($elementos));
?> 
