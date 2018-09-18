<?php 
	include  '../../ex_common/insert.php';

	
	$CORREO = "lqc347@gmail.com";
	
	$model = array();
	foreach ($objeto as $key => $value) {
		$model[$key] = array();
		$model[$key]["ID_TRAMITE"] = $value->ID_TRAMITE;
        $model[$key]["ID_CAMBIO"] = $value->ID_CAMBIO;
        $model[$key]["DESCRIPCION"] = $value->DESCRIPCION;
	}
	$respuesta = multiInsert("SERVICIO_COTIZACION_CAMBIO", $CORREO, $model);
	print_r($respuesta); 
?>
