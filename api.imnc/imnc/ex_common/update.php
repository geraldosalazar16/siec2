<?php
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
	include  '../../ex_common/ex_valida.php';

	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	
	function updateIn($TABLA, $CORREO, $model, $ID, $ID_NAME = "ID"){
		global $database;
		$id = $database->update($TABLA, $model, [$ID_NAME=>$ID]); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		$respuesta["resultado"]="ok"; 
		print_r(json_encode($respuesta)); 
	}

	function multiUpdate($TABLA, $CORREO, $models, $ID_NAME = "ID"){
		global $database;
		foreach ($models as $key => $value) {
			$id = $database->update($TABLA, $value, [$ID_NAME=>$value[$ID_NAME]]); 
		}
		valida_error_medoo_and_die($TABLA, $CORREO); 
		$respuesta["resultado"]="ok"; 
		return json_encode($respuesta); 
	}
?>