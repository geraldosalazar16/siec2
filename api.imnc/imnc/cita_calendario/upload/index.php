<?php
include  '../../ex_common/query.php'; 
include  '../../ex_common/archivos.php';
	
	function getDirectoryByIdArchivo($id_prospecto,$id_cita, $database){
		global $rutaExpediente;
		$direc = $rutaExpediente."/Prospecto/".$id_prospecto."/Citas/cita_".$id_cita;
		$direc = str_replace(" ","_",$direc);
		if (!file_exists($direc)) {
			mkdir($direc, 0777, true);	
		}
		return $direc;
	}
	$id_prospecto = $_POST['prospecto']['prospecto'];
	$id_cita = $_POST['prospecto']['cita'];
	$USUARIO_CREACION = $_POST['prospecto']['usuario'];
	$fecha = date('Ymd');
	$nombre_tabla = "PROSPECTO_CITAS_ARCHIVOS";
	$correo = "";
	$ultimo_id = $database->max($nombre_tabla,"ID");

	$output_dir = getDirectoryByIdArchivo($id_prospecto, $id_cita, $database)."/";
	
	echo "1.-".$id_prospecto ;
	echo "2.-".$id_cita;
	echo "3.-".$USUARIO_CREACION ;
	echo "4.-".$fecha;
	echo "5.-".$nombre_tabla;
	echo "6.-".$correo;
	echo "7.-".$ultimo_id;
	echo "8.-".$output_dir;
	foreach ($_FILES as $key => $value) {
		$fileName = $fecha."_".$value["name"];
		$fileName = str_replace(" ","_",$fileName);
		$ultimo_id++;
		$id = $database->insert($nombre_tabla, [
			"ID" => $ultimo_id,
			"ID_CITA_HISTORIAL" => $id_cita,
			"NOMBRE_ARCHIVO" => $fileName,
			"USUARIO_CREACION" => $USUARIO_CREACION, 
			"FECHA_CREACION" => date('Y/m/d H:i:s'),
		]);
		valida_error_medoo_and_die($nombre_tabla,$correo); 
		move_uploaded_file($value["tmp_name"], $output_dir.$fileName);
	}
?>