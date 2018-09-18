<?php
	include  '../../ex_common/query.php'; 
	
	$nombre_tabla = "PERFIL_PERMISOS";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$perfil = array();
	$id_perfil = $_REQUEST["id_perfil"]; 
	$permisos = $database->query("SELECT PERMISOS.ID,PERMISOS.PERMISO FROM PERMISOS")->fetchAll(PDO::FETCH_ASSOC);
	$cont = 0;
	foreach($permisos as $permiso)
	{
	   //print_r(json_encode($permiso)); 
	    $valor = $database->query("SELECT PERFIL_PERMISOS.ID AS ID_PERFIL_PERMISOS,PERFIL_PERMISOS.VALOR FROM PERFIL_PERMISOS WHERE ID_PERFIL = ".$id_perfil." AND ID_PERMISO = ".$permiso["ID"])->fetchAll(PDO::FETCH_ASSOC);
	    $perfil[$cont]["ID_PERMISO"] = $permiso["ID"];
	    $perfil[$cont]["PERMISO"] = $permiso["PERMISO"];
	    $perfil[$cont]["ID_PERFIL_PERMISOS"] = $valor[0]["ID_PERFIL_PERMISOS"];
	    if(!isset($valor[0]["VALOR"]))
	    {
	        $valor[0]["VALOR"] = 0;
	    }
	    $perfil[$cont]["VALOR"] = $valor[0]["VALOR"];
	    $cont++;
	}
	/*
	$perfil = $database->query("SELECT PERFIL_PERMISOS.ID,PERMISOS.ID,PERMISOS.PERMISO,PERFIL_PERMISOS.VALOR,PERFIL_PERMISOS.ID_PERMISO FROM PERMISOS LEFT JOIN PERFIL_PERMISOS ON PERMISOS.ID = PERFIL_PERMISOS.ID_PERMISO WHERE PERFIL_PERMISOS.ID_PERFIL = ".$id_perfil)->fetchAll(PDO::FETCH_ASSOC);
	*/
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($perfil)); 
?> 
