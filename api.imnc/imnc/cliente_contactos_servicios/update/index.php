<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}


function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "") { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("CLIENTES_CONTACTOS_SERVICIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$id_contacto = $objeto->ID_CONTACTO; 
$id_servicio=$objeto->ID_SERVICIO;

$id1 = $database->delete("CLIENTES_CONTACTOS_SERVICIOS", ["ID_CONTACTO" => $id_contacto]); 
valida_error_medoo_and_die(); 



$nombre_tabla="CLIENTES_CONTACTOS_SERVICIOS";
for($i=0;$i<count($id_servicio);$i++){
	$id1 = $database->insert($nombre_tabla, [ 
				"ID_CONTACTO" => $id_contacto, 
         		"ID_SERVICIO" => $id_servicio[$i], 
				]);
	valida_error_medoo_and_die();
}

$respuesta["resultado"]="ok"; 
print_r(json_encode($respuesta)); 
?> 
