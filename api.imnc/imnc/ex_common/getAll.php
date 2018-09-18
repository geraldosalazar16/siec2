<?php
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
	include  '../../ex_common/ex_valida.php';

	function getAllFrom($TABLA, $CORREO, $columnas = "*"){
		global $database;
		$sectores = $database->select($TABLA, $columnas);
		valida_error_medoo_and_die($TABLA, $CORREO); 
		print_r(json_encode($sectores));
	}
?>