<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$id_prospecto = $_REQUEST["id"]; 
	$prospecto_productos = $database->query("SELECT 
    SERVICIOS.NOMBRE AS NOMBRE_SERVICIO, 
    SERVICIOS.ID AS ID_SERVICIO,
    TIPOS_SERVICIO.ID AS ID_TIPO_SERVICIO,
    TIPOS_SERVICIO.NOMBRE AS NOMBRE_TIPO_SERVICIO,
    PROSPECTO_PRODUCTO.ID_PROSPECTO AS ID_PROSPECTO,
    PROSPECTO_PRODUCTO.ALCANCE AS ALCANCE,
    PROSPECTO_PRODUCTO.ID AS ID
    FROM 
    PROSPECTO_PRODUCTO 
    INNER JOIN SERVICIOS 
    ON PROSPECTO_PRODUCTO.ID_SERVICIO = SERVICIOS.ID
    INNER JOIN TIPOS_SERVICIO
    ON PROSPECTO_PRODUCTO.ID_TIPO_SERVICIO = TIPOS_SERVICIO.ID
    WHERE
    PROSPECTO_PRODUCTO.ID_PROSPECTO = ".$id_prospecto)->fetchAll(PDO::FETCH_ASSOC);

    valida_error_medoo_and_die($nombre_tabla ,$correo );
    $nombre_tabla = 'PROSPECTO_PRODUCTO_NORMAS'; 
    for($i=0;$i<count($prospecto_productos);$i++){
        $normas = $database->query(
            "SELECT 
            NORMAS.ID AS ID_NORMA,
            NORMAS.NOMBRE AS NOMBRE_NORMA
            FROM NORMAS
            INNER JOIN PROSPECTO_PRODUCTO_NORMAS PPN
            ON PPN.ID_NORMA = NORMAS.ID
            WHERE PPN.ID_PRODUCTO = ".$prospecto_productos[$i]['ID']
        )->fetchAll(PDO::FETCH_ASSOC);
        valida_error_medoo_and_die($nombre_tabla ,$correo );
        $prospecto_productos[$i]['NORMAS'] = $normas;
    }
	print_r(json_encode($prospecto_productos)); 
?> 
