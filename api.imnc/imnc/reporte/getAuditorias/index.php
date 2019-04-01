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
		$sql = "SELECT I_SG_AUDITORIAS.FECHA_CREACION AS FECHA_CREACION,SERVICIO_CLIENTE_ETAPA.ID_SERVICIO AS ID_SERVICIO, 'CSG' AS TIPO FROM I_SG_AUDITORIAS INNER JOIN SERVICIO_CLIENTE_ETAPA ON I_SG_AUDITORIAS.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID WHERE I_SG_AUDITORIAS.ID_USUARIO_CREACION = ".$id_usuario." AND I_SG_AUDITORIAS.FECHA_CREACION > '".$start_year."'
                UNION ALL
                SELECT I_EC_AUDITORIAS.FECHA_CREACION AS FECHA_CREACION,SERVICIO_CLIENTE_ETAPA.ID_SERVICIO AS ID_SERVICIO, 'EC' AS TIPO FROM I_EC_AUDITORIAS INNER JOIN SERVICIO_CLIENTE_ETAPA ON I_EC_AUDITORIAS.ID_SERVICIO_CLIENTE_ETAPA = SERVICIO_CLIENTE_ETAPA.ID WHERE I_EC_AUDITORIAS.ID_USUARIO_CREACION = ".$id_usuario." AND I_EC_AUDITORIAS.FECHA_CREACION > '".$start_year."'";
		$auditorias  = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		valida_error_medoo_and_die();
		$respuesta[1] = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
		$respuesta[2] = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
		foreach ($auditorias as $item)
		{

			$fecha =  substr($item["FECHA_CREACION"],5,2);

			    if((int) $fecha > 0)
				{
					$respuesta[$item["ID_SERVICIO"]] [((int) $fecha)-1] += 1;
				}




		}


	}
	
	

	print_r(json_encode($respuesta));
?> 
