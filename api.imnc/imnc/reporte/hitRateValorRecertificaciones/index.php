<?php 
	include  '../../ex_common/query.php';
	//Determinar todos los prospectos en estado contratado
	
	$nombre_tabla = "PROSPECTO";
	$correo = "leovardo.quintero@dhttecno.com";
	$json = file_get_contents("php://input");
	$objeto = json_decode($json); 
	$fecha_inicio = $objeto->f_ini;
	valida_parametro_and_die($fecha_inicio,"Es necesario capturar una fecha de inicio");
	$fecha_fin = $objeto->f_fin;
	valida_parametro_and_die($fecha_fin,"Es necesario capturar una fecha de fin");
	
	$respuesta=array(); 
	//Para buscar las recertificaciones se debe buscar en la tabla tareas_servicios_contratados las que son de tipo Renovacion y que se ajustan al rango de fechas en cuestion.
	$cons1	=	$database->count("TAREAS_SERVICIOS_CONTRATADOS",["AND"=>["ID_TAREA"=>12,"FECHA_INICIO[>=]"=>$fecha_inicio,"FECHA_FIN[<=]"=>$fecha_fin]]);
	// Para verificar que una cotizacion fue realizada se busca la existencia del documento de cotizacion en el modulo expediente. Este es el documento Propuesta Economica en la etapa Renovacion.
	$cons2	=	$database->count("BASE_DOCUMENTOS",["AND"=>["ID_CATALOGO_DOCUMENTOS"=>177,"FECHA_CREACION[>=]"=>$fecha_inicio,"FECHA_CREACION[<=]"=>$fecha_fin]]);
	$respuesta["resultado"]="ok";
	$respuesta["recertificaciones"]=$cons1;
	$respuesta["cotizaciones"]=$cons2;
	
	print_r(json_encode($respuesta)); 
?> 
