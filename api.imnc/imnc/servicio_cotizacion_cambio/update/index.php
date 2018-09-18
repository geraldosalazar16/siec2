<?php 
	include  '../../ex_common/update.php';

	
	$CORREO = "lqc347@gmail.com";
	
	$model = array();
	foreach ($objeto as $key => $value) {
		$model[$key] = array();
		$model[$key]["ID"] = $value->ID;
		$model[$key]["ID_TRAMITE"] = $value->ID_TRAMITE;
        $model[$key]["ID_CAMBIO"] = $value->ID_CAMBIO;
        $model[$key]["DESCRIPCION"] = $value->DESCRIPCION;
        //$model[$key]["ID_USUARIO"] = $value->ID_USUARIO;
	}
	$respuesta = multiUpdate("SERVICIO_COTIZACION_CAMBIO", $CORREO, $model);
	print_r($respuesta); 
?>
