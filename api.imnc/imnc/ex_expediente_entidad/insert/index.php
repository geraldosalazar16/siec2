 	<?php  
	include  '../../ex_common/query.php'; 
	include 'funciones.php';
	include  '../../ex_common/archivos.php';
	
	$nombre_tabla = "EX_EXPEDIENTE_ENTIDAD";
	$correo = "lqc347@gmail.com";
	
	$respuesta=array(); 
	$json = file_get_contents("php://input"); 

	$objeto = json_decode($json);  
	$ultimo_id = $database->max($nombre_tabla,"ID");
    $ID=$ultimo_id+1;
    $ID_ENTIDAD = $objeto->ID_ENTIDAD;
    $ID_TIPO_EXPEDIENTE = $objeto->ID_TIPO_EXPEDIENTE;
    $ID_USUARIO = $objeto->ID_USUARIO;
	$TIPO = $objeto->TIPO;
	$id = $database->insert($nombre_tabla, [
    "ID"=>$ID,
    "ID_ENTIDAD" => $ID_ENTIDAD,
    "ID_TIPO_EXPEDIENTE"=>$ID_TIPO_EXPEDIENTE,
    "FECHA_CREACION"=>date("Y-m-d H:i:s"),
    "FECHA_MODIFICACION" => date("Y-m-d H:i:s"),
    "ID_USUARIO_CREACION"=> $ID_USUARIO,
	"ID_USUARIO_MODIFICACION"=> $ID_USUARIO,
	"TIPO"=> $TIPO,
    "ESTADO"=>1
    ]);
	valida_error_medoo_and_die($nombre_tabla,$correo); 
	$respuesta["resultado"]="ok"; 
	$respuesta["id"]=$id;

	if($TIPO == 1){	
		creacion_expediente($ID_TIPO_EXPEDIENTE, $ID_ENTIDAD,$rutaExpediente, $database);		
		crea_instancias_expedientes($ID,$ID_ENTIDAD,$ID_TIPO_EXPEDIENTE,$database);

	}

	print_r(json_encode($respuesta));
	//creacion_expediente(10, 2,$rutaExpediente, $database);	
	
?> 
