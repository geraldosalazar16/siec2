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
		$mailerror->send("USUARIOS", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$email = $_REQUEST["email"]; 
valida_parametro_and_die($email, "Es necesario escribir tu usuario o email");
$password = $_REQUEST["password"]; 
valida_parametro_and_die($password, "Es necesario escribir tu contraseña");

$usuario = $database->get("USUARIOS", "*", ["EMAIL"=>$email]); 
valida_error_medoo_and_die(); 
if (!$usuario) {
	$usuario = $database->get("USUARIOS", "*", ["USUARIO"=>$email]); 
	valida_error_medoo_and_die(); 
}

if ($usuario) {
	if ($password == $usuario["PASSWORD"]) {
		$perfil = $database->get("TIPOS_PERFILES", "*", ["ID"=>$usuario["ID_PERFIL"]]); 
		valida_error_medoo_and_die();
		$usuario["PERFIL"] = $perfil;
		
		$respuesta["resultado"] = "ok"; 
		$respuesta["mensaje"] = "Bienvenido"; 
		$respuesta["usuario"] = $usuario; 
		print_r(json_encode($respuesta)); 
		die();
	}
	else{
		imprime_error_and_die("Password incorrecto");	
	}
}
else{
	imprime_error_and_die("Usuario no registrado");
}


?> 
