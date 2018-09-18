<?php 
include  '../../ex_common/query.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "galeanaisauro19@gmail.com";
	
	$respuesta=array(); 
	$respuesta = $database->query(
	"select exe.id as ID,te.tipo as ENTIDAD, ext.nombre as EXPEDIENTE, exe.ESTADO as ESTADO
    from EX_EXPEDIENTE_ENTIDAD exe 
    join TIPOS_ENTIDAD te on exe.ID_ENTIDAD=te.ID 
    join EX_TIPO_EXPEDIENTE ext on exe.ID_TIPO_EXPEDIENTE=ext.ID
    order by ENTIDAD
    ;")->fetchAll();
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	print_r(json_encode($respuesta)); 
?> 
