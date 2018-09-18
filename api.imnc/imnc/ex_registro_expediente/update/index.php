<?php 
	include  '../init.php';
	include  '../../ex_common/insert.php';

	/*$model =  array(		
			"ID_REGISTRO" => $objeto->ID_REGISTRO, 
			"ID_EXPEDIENTE_ENTIDAD" => $objeto->ID_EXPEDIENTE_ENTIDAD, 
			"FECHA_CREACION" => date("Y-m-d H:i:s"), 
			"USUARIO_CREACION" => $objeto->USUARIO, 
			"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
			"USUARIO_MODIFICACION" => $objeto->USUARIO, 
		);*/

	$archivos = $objeto->archivosDocumentos;
	//$model_arch =  array();
	$models_doc = array();
	foreach ($archivos as $key => $value) {
		/*$model_arch[] =  array(
				"ID_EXPEDIENTE_DOCUMENTO" => $value->ID_EXPEDIENTE_DOCUMENTO, 
				"ID_REGISTRO_EXPEDIENTE" => $value->ID_REGISTRO_EXPEDIENTE, 
				"FECHA_CREACION" => date("Y-m-d H:i:s"), 
				"USUARIO_CREACION" => $value->USUARIO, 
				"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
				"USUARIO_MODIFICACION" => $value->USUARIO, 
			);*/
		$models_doc[] =  array(		
			"NOMBRE_ARCHIVO" => $value->NOMBRE_ARCHIVO, 
			"FECHA_VENCIMIENTO_INICIAL" => $value->FECHA_VENCIMIENTO_INICIAL,
			"FECHA_VENCIMIENTO_FINAL" => $value->FECHA_VENCIMIENTO_FINAL,
			"VALIDACION" => $value->VALIDACION,   
			"ID_ARCHIVO_EXPEDIENTE" => $value->ID_ARCHIVO_EXPEDIENTE, 
			"FECHA_CREACION" => date("Y-m-d H:i:s"), 
			"USUARIO_CREACION" => $value->USUARIO, 
			"FECHA_MODIFICACION" => date("Y-m-d H:i:s"), 
			"USUARIO_MODIFICACION" => $value->USUARIO, 
		);
	}
	$respuesta = multiInsert("EX_ARCHIVO_DOCUMENTO", $CORREO, $models_doc);
	print_r($respuesta); 
?> 
