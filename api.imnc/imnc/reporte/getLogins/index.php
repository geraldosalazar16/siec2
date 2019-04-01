<?php
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';

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
	if ($database->error()[2]) { //Aqui está el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		die();
	}
}

$respuesta =  array();
	
	if(isset($_REQUEST["usuario"])){
		$id_usuario = $_REQUEST["usuario"];
		$start_year  = date("Y-m-d H:i:s",mktime(0, 0, 0, 12  , 31, date("Y")-1));
		$logins  = $database->select("USUARIOS_LOGIN",["FECHA_LOGIN"],["AND"=>["ID_USUARIO"=>$id_usuario,"FECHA_LOGIN[>]"=>$start_year]]);


		$respuesta = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
		foreach ($logins as $item)
		{
			$fecha =  substr($item["FECHA_LOGIN"],5,2);
			if((int) $fecha > 0) {
				$respuesta [((int)$fecha) - 1] += 1;
			}

		}
		valida_error_medoo_and_die();

	}
	
	

	print_r(json_encode($respuesta));
?> 
