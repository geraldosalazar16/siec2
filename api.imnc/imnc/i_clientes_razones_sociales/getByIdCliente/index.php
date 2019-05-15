<?php 
include  '../../common/conn-apiserver.php'; 
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 
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
$id_cliente = $_REQUEST["id"];
// Obtener la primera razon social que es el propio nombre del cliente
$data1 = $database->get('CLIENTES',['ID','NOMBRE','RFC'],['ID'=>$id_cliente]);
valida_error_medoo_and_die();
$n=0;
$respuesta[$n] = $data1; 
$n=1;
//Obtener la 2da razon social de la tabla clientes si existe
if($database->count('CLIENTES',['AND'=>['ID'=>$id_cliente,'ES_FACTURARIO'=>'N']])>0){
	$data2 = $database->get('CLIENTES',['ID','CLIENTE_FACTURARIO(NOMBRE)','RFC_FACTURARIO(RFC)','ES_FACTURARIO'],['ID'=>$id_cliente]);
	Valida_error_medoo_and_die();
	$respuesta[$n]=$data2;
	$n=2;
}
$valores = $database->select('I_CLIENTES_RAZONES_SOCIALES',
							
							[
								'I_CLIENTES_RAZONES_SOCIALES.ID',
								'I_CLIENTES_RAZONES_SOCIALES.NOMBRE',
								'I_CLIENTES_RAZONES_SOCIALES.RFC'
								
							],['ID_CLIENTE'=>$id_cliente]);
valida_error_medoo_and_die();

for($i=0;$i<count($valores);$i++){
	$respuesta[$n] = $valores[$i];
	$n++;
}


print_r(json_encode($respuesta)); 
?> 
