<?php
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

$nombre_tabla = "BASE_DOCUMENTOS";

function valida_parametro_and_die($parametro, $mensaje_error){
	$parametro = "".$parametro;
	if ($parametro == "") {
		$respuesta['resultado'] = 'error';
		$respuesta['mensaje'] = $mensaje_error;
		print_r(json_encode($respuesta));
		die();
	}
}

function valida_error_medoo_and_die(){
	global $database, $mailerror;
	if ($database->error()[2]) { //Aqui est� el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}




if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["fileToUpload"]["type"])){
	$referencia=explode('-',$_POST['Referencia']);
	$id_servicio=$_POST['IdServicio'];
	$id_documento=$_POST['IdDocumento'];
	$nombre_ciclo=$_POST['NombreCiclo'];
	$nombre_etapa=$_POST['NombreEtapa'];
	$nombre_seccion=$_POST['NombreSeccion'];
	$target_dir = "../../arch_expediente/".$referencia[1].$referencia[2]."/".$nombre_ciclo."/".$nombre_etapa."/".$nombre_seccion."/";
	// TODO Sustituir por variable global
	//$ubicacion_documento =  "http://apinube.com/imnc/siec2.0/api.imnc/imnc/arch_expediente/".$referencia[1].$referencia[2]."/".$nombre_ciclo."/".$nombre_etapa."/".$nombre_seccion."/";
	$ubicacion_documento =  $global_apiserver."/arch_expediente/".$referencia[1].$referencia[2]."/".$nombre_ciclo."/".$nombre_etapa."/".$nombre_seccion."/";
	$carpeta=$target_dir;
	 
	$uploadOk = 1;
	$FileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);

	$messages="";
	/*// Check if file already exists
	if (file_exists($target_file)) {
		$messages.="Lo sentimos, archivo ya existe.";
		$uploadOk = 0;
	}*/
	// Check file size
	/*
	if ($_FILES["fileToUpload"]["size"] > 20524288) {
		$messages[]= "Lo sentimos, el archivo es demasiado grande.  Tama�o m�ximo admitido: 0.5 MB";
		$uploadOk = 0;
	}*/
	// Allow certain file formats
	
	$FileType = strtolower($FileType); 
	/*
	if($FileType != "pdf") {
		$messages.= "Lo sentimos, solo archivos .pdf son permitidos.";
		$uploadOk = 0;
	}
	*/
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$messages.= "Lo sentimos, tu archivo no fue subido.";
	// if everything is ok, try to upload file
	} 
	else{
		if (!file_exists($carpeta)) {
			mkdir($carpeta, 0777, true);
		}
		$target_file = $carpeta . basename($id_documento.".".$FileType);
		$ubicacion_documento = $ubicacion_documento . basename($id_documento.".".$FileType);
		$resultado_subir = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		if ($resultado_subir) {
		   
		   
			////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////
			$respuesta=array();
			
			$resp1	=	$database->select($nombre_tabla,"ID",["AND" => [
					"ID_CATALOGO_DOCUMENTOS"=>$id_documento,
					"CICLO" => $nombre_ciclo,
					"ID_SERVICIO" => $id_servicio
					] 
				]);
				if ($resp1 == null){
				$UBICACION_DOCUMENTOS	=	$ubicacion_documento;
				$ID_CATALOGO_DOCUMENTOS	=	$id_documento;
				$CICLO					=	$nombre_ciclo;
				$ID_SERVICIO			=	$id_servicio;
				$ESTADO_DOCUMENTO		=	"En Revision";
				$FECHA_CREACION			=	date("YmdHis");
				$ID_USUARIO_CREACION	=	$_POST['ID_USUARIO'];
				$FECHA_MODIFICACION		=	"";
				$ID_USUARIO_MODIFICACION=	"";
			
				$id = $database->insert($nombre_tabla, [ 
					"UBICACION_DOCUMENTOS" => $UBICACION_DOCUMENTOS, 
					"ID_CATALOGO_DOCUMENTOS" => $ID_CATALOGO_DOCUMENTOS, 
					"CICLO" => $CICLO,
					"ID_SERVICIO" => $ID_SERVICIO,
					"ESTADO_DOCUMENTO" => $ESTADO_DOCUMENTO,
					"FECHA_CREACION" => $FECHA_CREACION, 
					"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION, 
					"FECHA_MODIFICACION" => $FECHA_MODIFICACION, 
					"ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
					"EXTENSION_DOCUMENTO" => $FileType
					]);
				}
				else{
					$id = $database->update($nombre_tabla, [ 
					"ESTADO_DOCUMENTO" => "En Revision",
					"FECHA_MODIFICACION" => date("YmdHis"), 
					"ID_USUARIO_MODIFICACION" => $_POST['ID_USUARIO']
				], ["ID"=>$resp1]); 
				
				}
			$respuesta['resultado']="";
			$respuesta['mensaje']="";
			valida_error_medoo_and_die();
			$respuesta['resultado']="ok";
			/*
			if($respuesta['resultado']!="error"){
				$messages.= "El Archivo ha sido subido correctamente.";
			}
			else{
				$messages.= "Lo sentimos, hubo un error subiendo el archivo.".$respuesta['mensaje'];
			}
			*/
			////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////
		   
		   
		} 
		else {
		   $messages.= "Lo sentimos, hubo un error subiendo el archivo.";
		   $respuesta['subir']=$target_file;
		   $respuesta['resultado']="error";
		}
	} 
}
$respuesta['nota']=$messages;
print_r(json_encode($respuesta));

?>
