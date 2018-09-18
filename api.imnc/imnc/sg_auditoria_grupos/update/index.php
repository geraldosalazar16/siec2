<?php  

// SERVICIO INACTIVO

// include  '../../common/conn-apiserver.php';  
// include  '../../common/conn-medoo.php';  
// include  '../../common/conn-sendgrid.php'; 

// function imprime_error_and_die($mensaje){
// 	$respuesta['resultado'] = 'error';
// 	$respuesta['mensaje'] = $mensaje;
// 	print_r(json_encode($respuesta));
// 	die();
// }

// function valida_parametro_and_die($parametro, $mensaje_error){ 
// 	$parametro = "" . $parametro; 
// 	if ($parametro == "") { 
// 		$respuesta["resultado"] = "error\n"; 
// 		$respuesta["mensaje"] = $mensaje_error; 
// 		print_r(json_encode($respuesta)); 
// 		die(); 
// 	} 
// } 

// function valida_error_medoo_and_die(){ 
// 	global $database, $mailerror; 
// 	if ($database->error()[2]) { 
// 		$respuesta["resultado"]="error"; 
// 		$respuesta["mensaje"]="Error al ejecutar script: " . $database->error()[2]; 
// 		print_r(json_encode($respuesta)); 
// 		$mailerror->send("SG_AUDITORIA_GRUPOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
// 		die(); 
// 	} 
// } 

// $respuesta=array(); 
// $respuesta["resultado"]="error"; 
// $respuesta["mensaje"]="No está permitido actualizar un registro de grupo auditor"; 
// print_r(json_encode($respuesta)); 
// die();

// $json = file_get_contents("php://input"); 
// $objeto = json_decode($json); 

// $ID = $objeto->ID; 
// $ID_SG_AUDITORIA = $objeto->ID_SG_AUDITORIA; 
// $ID_PERSONAL_TECNICO_CALIF = $objeto->ID_PERSONAL_TECNICO_CALIF; 
// $FECHA_INICIO = $objeto->FECHA_INICIO; 
// $FECHA_FIN = $objeto->FECHA_FIN; 


// $id = $database->update("SG_AUDITORIA_GRUPOS", [ 
// 	"ID_SG_AUDITORIA" => $ID_SG_AUDITORIA, 
// 	"ID_PERSONAL_TECNICO_CALIF" => $ID_PERSONAL_TECNICO_CALIF, 
// 	"FECHA_INICIO" => $FECHA_INICIO, 
// 	"FECHA_FIN" => $FECHA_FIN, 
// 	], ["ID"=>$ID]); 
// valida_error_medoo_and_die(); 
// $respuesta["resultado"]="ok"; 
// print_r(json_encode($respuesta)); 
?> 
