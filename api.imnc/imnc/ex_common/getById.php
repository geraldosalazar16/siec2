<?php
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
	include  '../../ex_common/ex_valida.php';
	


	function getByIdFrom($TABLA, $CORREO, $columnas = "*", $ByID = "ID"){
		global $database;
		$id = $_REQUEST["id"]; 
		$sectores = $database->get($TABLA, $columnas, [$ByID=>$id]); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		print_r(json_encode($sectores)); 
	}

	function getByIdWithJoinFrom($TABLA, $CORREO, $JOIN, $columnas = "*", $ByID = "ID"){
		global $database;
		$id = $_REQUEST["id"]; 
		$sectores = $database->select($TABLA,$JOIN, $columnas, [$ByID=>$id]); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		return $sectores;
	}
?>