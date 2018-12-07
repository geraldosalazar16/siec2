<?php
	include  '../../ex_common/query.php';  
	

	$correo = 'bmyorthxxx@gmail.com';

	$nombre_curso = $_REQUEST["nombre"]; 
	
	$curso = $database->count('CURSOS', "*",  ["NOMBRE"=>$nombre_curso]); 
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	echo '{"cantidad" : "'.$curso.'"}';
?> 
