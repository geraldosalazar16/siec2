<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$respuesta=array();
	$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
	$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

	$id_prospecto = $objeto->id_prospecto; 
	valida_parametro_and_die($id_prospecto, "Falta ID de prospecto");
	$id_servicio = $objeto->id_servicio; 
	valida_parametro_and_die($id_servicio, "Falta ID de servicio");
	$id_tipo_servicio = $objeto->id_tipo_servicio; 
	valida_parametro_and_die($id_tipo_servicio, "Falta ID de tipo de servicio");
	$id_norma = $objeto->id_norma; 
	valida_parametro_and_die($id_norma, "Falta ID de norma");

	$query = "SELECT 
    SERVICIOS.NOMBRE AS NOMBRE_SERVICIO, 
    SERVICIOS.ID AS ID_SERVICIO,
    TIPOS_SERVICIO.ID AS ID_TIPO_SERVICIO,
    TIPOS_SERVICIO.NOMBRE AS NOMBRE_TIPO_SERVICIO,
    NORMAS.ID AS ID_NORMA,
    NORMAS.NOMBRE AS NOMBRE_NORMA,
	PP.ID_ESTATUS_SEGUIMIENTO,
	PP.ALCANCE AS ALCANCE
    FROM 
    PROSPECTO_PRODUCTO PP
    LEFT JOIN SERVICIOS 
    ON PP.ID_SERVICIO = SERVICIOS.ID
    LEFT JOIN TIPOS_SERVICIO
    ON PP.ID_TIPO_SERVICIO = TIPOS_SERVICIO.ID
    LEFT JOIN NORMAS
    ON PP.ID_NORMA = NORMAS.ID
    WHERE
	PP.ID_PROSPECTO = ".$id_prospecto.
	" AND PP.ID_SERVICIO=".$id_servicio.
	" AND PP.ID_TIPO_SERVICIO=".$id_tipo_servicio.
	" AND PP.ID_NORMA ='".$id_norma."'";

	$prospecto_producto = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);

	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_producto)); 
?> 
