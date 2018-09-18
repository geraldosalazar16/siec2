<?php
	include  '../../common/conn-apiserver.php'; 
	include  '../../common/conn-medoo.php'; 
	include  '../../common/conn-sendgrid.php'; 
	include  '../../ex_common/ex_valida.php';

	$json = file_get_contents("php://input"); 
	$objeto = json_decode($json); 
	
	function insertIn($TABLA, $CORREO, $model, $ID_NAME = "ID"){
		global $database;
		$model[$ID_NAME] = $database->max($TABLA, $ID_NAME) + 1;
		$id = $database->insert($TABLA, $model); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		print_r(json_encode($respuesta)); 
	}

	function insertInto($TABLA, $CORREO, $model, $ID_NAME = "ID"){
		global $database;
		$model[$ID_NAME] = $database->max($TABLA, $ID_NAME) + 1;
		$id = $database->insert($TABLA, $model); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		return json_encode($respuesta);
		//print_r(json_encode($respuesta)); 
	}

	function multiInsert($TABLA, $CORREO, &$models, $ID_NAME = "ID"){
		global $database;
		$last = $database->max($TABLA, $ID_NAME) + 1;
		$i = 0;
		foreach ($models as $key => $value) {
			$models[$key][$ID_NAME] = $last + $i;
			$i++;
		}
		$id = $database->insert($TABLA, $models); 
		valida_error_medoo_and_die($TABLA, $CORREO); 
		$respuesta["resultado"]="ok"; 
		$respuesta["id"]=$id; 
		return json_encode($respuesta);
		//print_r(json_encode($respuesta)); 
	}
?>