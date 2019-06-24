<?php  
	include  '../../common/conn-apiserver.php';  
	include  '../../common/conn-medoo.php';  

	function valida_parametro_and_die($parametro, $mensaje_error){ 
		$parametro = "" . $parametro;		 
		if ($parametro == "") { 
			$respuesta["resultado"] = "error"; 
			$respuesta["mensaje"] = $mensaje_error; 
			print_r(json_encode($respuesta)); 
			die(); 
		} 
	} 
	function valida_error_medoo_and_die(){ 
		global $database, $mailerror; 
		if ($database->error()[2]) { 
			$respuesta["resultado"]="error"; 
			$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
			print_r(json_encode($respuesta));
			die(); 
		} 
	} 

$respuesta=array(); 
// $json = file_get_contents("php://input"); 
// $objeto = json_decode($json); 

// $id_solicitud = $objeto->id_solicitud;
$id_solicitud = $_POST['id_solicitud'];
valida_parametro_and_die($id_solicitud,"Falta ID de Solicitud");
// $tipo_documento = $objeto->tipo_documento;
$tipo_documento = $_POST['tipo_documento'];
valida_parametro_and_die($tipo_documento,"Falta Tipo de Documento");
// $id_usuario = $objeto->id_usuario;
$id_usuario = $_POST['id_usuario'];
valida_parametro_and_die($id_usuario,"Falta ID de usuario");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

foreach ($_FILES as $archivo) {
	// move_uploaded_file($archivo["tmp_name"], $archivo["name"]);
	$partes = explode(".", $archivo["name"]);
	$extension = $partes[sizeof($partes) - 1];
	$nombre_almacenamiento = md5($archivo["name"] . $FECHA_CREACION . $HORA_CREACION);
	$id = $database->insert("FACTURACION_SOLICITUD_DOCUMENTOS",[  
		"ID_SOLICITUD" => $id_solicitud, 
		"TIPO_DOCUMENTO" => $tipo_documento, 
		"NOMBRE_DOCUMENTO" => $archivo["name"],
		"NOMBRE_ALMACENAMIENTO" => $nombre_almacenamiento . "." . $extension,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION"=> $HORA_CREACION,
		"USUARIO_CREACION" => $id_usuario
	]); 
	valida_error_medoo_and_die();
	// Guardar el archivo
	$target_dir = "../../arch_facturacion/";
	$FileType = pathinfo($archivo["name"],PATHINFO_EXTENSION);
	$FileType = strtolower($FileType);
	$target_file = $target_dir . basename($nombre_almacenamiento.".".$FileType);
	$resultado_subir = move_uploaded_file($archivo["tmp_name"], $target_file);
	if (!$resultado_subir) {			
		$messages.= "Lo sentimos, hubo un error subiendo el archivo.";
		$respuesta['subir']=$target_file;
		$respuesta['resultado']="error";
		die();
	}
}
	
 
$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
