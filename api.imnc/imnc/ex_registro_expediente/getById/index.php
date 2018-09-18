<?php 
	include  '../init.php'; 
	include  '../../ex_common/getById.php';

	function encriptar($cadena){
		$key='EXPEDIENTEDHT2016CMLJJJAI';
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
		return urlencode($encrypted); //Devuelve el string encriptado
	}

	$id = $_REQUEST["id"];
	$JOIN = [
		"[>]EX_EXPEDIENTE_ENTIDAD(exp_ent)" => ["EX_REGISTRO_EXPEDIENTE.ID_EXPEDIENTE_ENTIDAD" 
			=> "ID"],
		"[>]EX_TIPO_EXPEDIENTE(tipo_exp)" => ["exp_ent.ID_TIPO_EXPEDIENTE" => "ID"],
	];

	$columnas = [
		"EX_REGISTRO_EXPEDIENTE.ID(ID)",
	  	"EX_REGISTRO_EXPEDIENTE.ID_REGISTRO(ID_REGISTRO)",
	  	"EX_REGISTRO_EXPEDIENTE.ID_EXPEDIENTE_ENTIDAD(ID_EXPEDIENTE_ENTIDAD)",
	  	"tipo_exp.NOMBRE(NOMBRE_EXPEDIENTE_ENTIDAD)",
	];

	$aux = getByIdWithJoinFrom($TABLA, $CORREO, $JOIN, $columnas, "EX_REGISTRO_EXPEDIENTE.ID");
	if(count($aux) == 0){
		$respuesta["resultado"]="No hay registro con id = ".$id; 
		print_r(json_encode($respuesta)); 
		die();
	}
	$registro = $aux[0];
	$archivos = $database->query("
			select 	arc_exp.ID as ID_ARCHIVO_EXPEDIENTE,
	  				exp_doc.ID as ID_EXPEDIENTE_DOCUMENTO,
	  				exp_doc.OBLIGATORIO as OBLIGATORIO,
	  				tipo_doc.NOMBRE as NOMBRE_DOCUMENTO,
	  				arc_doc.ID as ID_ULT_ARCHIVO,
	  				arc_doc.NOMBRE_ARCHIVO as ULT_NOMBRE_ARCHIVO,
	  				arc_doc.FECHA_VENCIMIENTO_INICIAL as ULT_FECHA_VENCIMIENTO_INICIAL,
	  				arc_doc.FECHA_VENCIMIENTO_FINAL as ULT_FECHA_VENCIMIENTO_FINAL,
	  				arc_doc.VALIDACION as ULT_VALIDACION
	  		from EX_ARCHIVO_EXPEDIENTE as arc_exp
	  		left join EX_ARCHIVO_DOCUMENTO as arc_doc
	  		on (arc_doc.ID_ARCHIVO_EXPEDIENTE = arc_exp.ID )
	  		left join EX_EXPEDIENTE_DOCUMENTO as exp_doc
	  		on (exp_doc.ID = arc_exp.ID_EXPEDIENTE_DOCUMENTO)
	  		left join EX_TIPO_DOCUMENTO as tipo_doc
	  		on (tipo_doc.ID = exp_doc.ID_DOCUMENTO)
	  		where (arc_doc.ID IS NULL or arc_doc.ID in (
			select max(ID) from EX_ARCHIVO_DOCUMENTO
			group by ID_ARCHIVO_EXPEDIENTE)) and 
			arc_exp.ID_REGISTRO_EXPEDIENTE =".$id.";")->fetchAll(); 

	foreach ($archivos as $key => $value) {
		if(is_null($value["ID_ULT_ARCHIVO"])){
			$archivos[$key]["ID_ENCRIPTADO"] = 0;
            continue;
        }
		$archivos[$key]["ID_ENCRIPTADO"] = encriptar($value["ID_ULT_ARCHIVO"]);
	} 
	$registro["archivosDocumentos"] = $archivos;
	valida_error_medoo_and_die($TABLA, $CORREO);
	print_r(json_encode($registro)); 

?>