<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_DOCUMENTO";
	$correo = "lqc347@gmail.com";	
	$id = $_REQUEST["id"]; 
	
	$query = "SELECT EX_TIPO_DOCUMENTO.ID, EX_TIPO_DOCUMENTO.NOMBRE ";
	$query .= "FROM EX_TIPO_DOCUMENTO ";
	$query .= "WHERE EX_TIPO_DOCUMENTO.ID NOT IN ";
	$query .= "(SELECT ID_DOCUMENTO FROM EX_EXPEDIENTE_DOCUMENTO where ID_EXPEDIENTE = ".$database->quote($id).")";

	$selectFill = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($selectFill));
?> 
  