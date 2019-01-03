<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PROSPECTO_PRODUCTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
    $id_prospecto = $_REQUEST["id"]; 
    $query = "SELECT 
    SERVICIOS.NOMBRE AS NOMBRE_SERVICIO, 
    SERVICIOS.ID AS ID_SERVICIO,
    TIPOS_SERVICIO.ID AS ID_TIPO_SERVICIO,
    TIPOS_SERVICIO.NOMBRE AS NOMBRE_TIPO_SERVICIO,
    PROSPECTO_PRODUCTO.ID_PROSPECTO AS ID_PROSPECTO,
    PROSPECTO_PRODUCTO.ALCANCE AS ALCANCE,
    PROSPECTO_PRODUCTO.ID AS ID,
    PROSPECTO_PRODUCTO.ID_COTIZACION AS ID_COTIZACION
    FROM 
    PROSPECTO_PRODUCTO 
    INNER JOIN SERVICIOS 
    ON PROSPECTO_PRODUCTO.ID_SERVICIO = SERVICIOS.ID
    INNER JOIN TIPOS_SERVICIO
    ON PROSPECTO_PRODUCTO.ID_TIPO_SERVICIO = TIPOS_SERVICIO.ID
    WHERE
    PROSPECTO_PRODUCTO.ID_PROSPECTO = ".$id_prospecto;
	$prospecto_productos = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
    valida_error_medoo_and_die($nombre_tabla ,$correo );

    //Agregar informacion adicional al producto
    $nombre_tabla = 'PROSPECTO_PRODUCTO_NORMAS'; 
    for($i=0;$i<count($prospecto_productos);$i++){
        if($prospecto_productos[$i]["ID_SERVICIO"]==3)
        {
            $query = "SELECT  (CASE WHEN PPC.MODALIDAD = 'programado' THEN PPC.ID_CURSO_PROGRAMADO ELSE CASE WHEN PPC.MODALIDAD = 'insitu' THEN PPC.ID_CURSO END END) AS ID_CURSO,PPC.MODALIDAD,(CASE WHEN PPC.MODALIDAD = 'programado' THEN (SELECT C.NOMBRE  FROM CURSOS C INNER JOIN CURSOS_PROGRAMADOS CP ON C.ID_CURSO = CP.ID_CURSO WHERE PPC.ID_CURSO_PROGRAMADO = CP.ID) ELSE CASE WHEN PPC.MODALIDAD = 'insitu' THEN(SELECT C.NOMBRE  FROM CURSOS C  WHERE PPC.ID_CURSO = C.ID_CURSO)END END) AS NOMBRE_CURSO,CANTIDAD_PARTICIPANTES AS CANTIDAD FROM PROSPECTO_PRODUCTO_CURSO PPC WHERE PPC.ID_PRODUCTO =".$prospecto_productos[$i]['ID'];
            $curso = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
            valida_error_medoo_and_die($nombre_tabla ,$correo );
            if($curso[0]["NOMBRE_CURSO"]==null)
                $curso[0]["NOMBRE_CURSO"]="";

            $prospecto_productos[$i]['CURSO'] = $curso[0];
            $prospecto_productos[$i]['NORMAS'] = [];
        }
        else
        {
            //Agregar las normas del producto
            $query = "SELECT 
        NORMAS.ID AS ID_NORMA,
        NORMAS.NOMBRE AS NOMBRE_NORMA
        FROM NORMAS
        INNER JOIN PROSPECTO_PRODUCTO_NORMAS PPN
        ON PPN.ID_NORMA = NORMAS.ID
        WHERE PPN.ID_PRODUCTO = ".$prospecto_productos[$i]['ID'];
            $normas = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
            valida_error_medoo_and_die($nombre_tabla ,$correo );
            $prospecto_productos[$i]['NORMAS'] = $normas;
            $prospecto_productos[$i]['CURSO'] = "";
        }


        //Determinar si el producto tiene una cotizacion asociada
        $query = "SELECT 
        COTIZACIONES.ID AS ID_COTIZACION
        FROM COTIZACIONES
        WHERE COTIZACIONES.ID_PROSPECTO = ".$id_prospecto.
        " AND COTIZACIONES.ID_SERVICIO = ".$prospecto_productos[$i]["ID_SERVICIO"].
        " AND COTIZACIONES.ID_TIPO_SERVICIO = ".$prospecto_productos[$i]["ID_TIPO_SERVICIO"];
        $cotizaciones = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
        valida_error_medoo_and_die("COTIZACIONES" ,$correo );
        $prospecto_productos[$i]['TIENE_COTIZACION'] = count($cotizaciones);

    }
	print_r(json_encode($prospecto_productos)); 
?> 
