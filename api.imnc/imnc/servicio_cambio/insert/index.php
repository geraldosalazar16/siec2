<?php 
	include  '../../ex_common/insert.php';

	
	$CORREO = "lqc347@gmail.com";
	
	$model = array();
	foreach ($objeto as $key => $value) {
		$model[$key] = array();
		$model[$key]["ID_SERVICIO_CLIENTE"] = $value->ID_SERVICIO;
        $model[$key]["ID_CAMBIO"] = $value->ID_CAMBIO;
        $model[$key]["DESCRIPCION"] = $value->DESCRIPCION;
        //$model[$key]["ID_USUARIO"] = $value->ID_USUARIO;
	}
	$respuesta = multiInsert("SERVICIO_CLIENTE_CAMBIO", $CORREO, $model);
	print_r($respuesta); 
?>
