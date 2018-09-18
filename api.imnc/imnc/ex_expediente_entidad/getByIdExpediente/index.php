<?php
	include  '../../ex_common/query.php'; 
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "lqc347@gmail.com";
	
	$id = $_REQUEST["id_expediente"]; 
	$respuesta=array(); 
	$respuesta = $database->query(
	"SELECT EX_EXPEDIENTE_ENTIDAD.ID as ID, EX_TABLA_ENTIDADES.descripcion as ENTIDAD, 
	EX_TIPO_EXPEDIENTE.nombre as EXPEDIENTE,EX_EXPEDIENTE_ENTIDAD.estado as ESTADO 
	FROM EX_TIPO_EXPEDIENTE,EX_EXPEDIENTE_ENTIDAD,EX_TABLA_ENTIDADES 
	where EX_TIPO_EXPEDIENTE.id = id_tipo_expediente AND 
	EX_TABLA_ENTIDADES.id = id_entidad AND tipo = 1
	AND EX_TIPO_EXPEDIENTE.id = ".$database->quote($id)."
	UNION
	SELECT EX_EXPEDIENTE_ENTIDAD.ID as ID, ETAPAS_PROCESO.ID as ENTIDAD, 
	EX_TIPO_EXPEDIENTE.nombre as EXPEDIENTE,EX_EXPEDIENTE_ENTIDAD.estado as ESTADO 
	FROM EX_TIPO_EXPEDIENTE,EX_EXPEDIENTE_ENTIDAD,ETAPAS_PROCESO 
	where EX_TIPO_EXPEDIENTE.id = id_tipo_expediente AND 
	ETAPAS_PROCESO.ID_ETAPA = id_entidad AND tipo = 0
	AND EX_TIPO_EXPEDIENTE.id = ".$database->quote($id)." 
    order by ENTIDAD
    ;")->fetchAll(PDO::FETCH_ASSOC);
	valida_error_medoo_and_die($nombre_tabla ,$correo ); 
	print_r(json_encode($respuesta)); 
?> 
