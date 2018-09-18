 	<?php  
	include  '../../ex_common/query.php'; 
	include 'funciones.php';
	include  '../../ex_common/archivos.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "lqc347@gmail.com";
	
	
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=10000;

$ID = 1000;
$ID_TIPO_EXPEDIENTE = 3;
$ID_ENTIDAD= 4;
		creacion_expediente($ID_TIPO_EXPEDIENTE, $ID_ENTIDAD,$rutaExpediente, $database);		
		crea_instancias_expedientes($ID,$ID_ENTIDAD,$ID_TIPO_EXPEDIENTE,$database);



	print_r(json_encode($respuesta));
	
?> 