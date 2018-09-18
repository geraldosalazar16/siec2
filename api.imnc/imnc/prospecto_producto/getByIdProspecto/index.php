<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id_prospecto = $_REQUEST["id"]; 
	$prospecto_producto = $database->query("SELECT 
    PROSPECTO_PRODUCTO.ID AS ID_PROSPECTO_PRODUCTO,
    PRODUCTOS.NOMBRE AS NOMBRE_PRODUCTO, 
    AREAS.NOMBRE AS NOMBRE_AREA, 
    AREAS.ID AS ID_AREA,	
    DEPARTAMENTOS.NOMBRE AS NOMBRE_DEPARTAMENTO
    FROM 
    PROSPECTO_PRODUCTO 
    LEFT JOIN AREAS 
    ON PROSPECTO_PRODUCTO.ID_AREA = AREAS.ID
    LEFT JOIN DEPARTAMENTOS
    ON PROSPECTO_PRODUCTO.ID_DEPARTAMENTO = DEPARTAMENTOS.ID
    LEFT JOIN PRODUCTOS
    ON PROSPECTO_PRODUCTO.ID_PRODUCTO = PRODUCTOS.ID
    WHERE
    PROSPECTO_PRODUCTO.ID_PROSPECTO = ".$id_prospecto)->fetchAll();

	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($prospecto_producto)); 
?> 
