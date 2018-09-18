<?php 
	include  '../../ex_common/query.php';
	//Determinar todos los prospectos en estado contratado
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@dhttecno.com";
	
	$json = file_get_contents("php://input");
	$objeto = json_decode($json); 
	$fecha_inicio = $json->f_ini;
	valida_parametro_and_die($fecha_inicio,"Es necesario capturar uan fecha de inicio");
	$fecha_fin = $json->f_fin;
	valida_parametro_and_die($fecha_fin,"Es necesario capturar uan fecha de fin");
	
	$respuesta=array(); 
	
	print_r(json_encode($respuesta)); 
?> 
