<?php 
 // error_reporting(E_ALL);
 // ini_set("display_errors",1);

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
	if ($database->error()[2]) { //Aqui est� el error
		$respuesta['resultado']="error";
		$respuesta['mensaje']="Error al ejecutar script: " . $database->error()[2];
		print_r(json_encode($respuesta));
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se env�a v�a POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$QUERY = $objeto->QUERY; //CLIENTES



$strQuery = "SELECT SERVICIO_CLIENTE_ETAPA.ID,SERVICIO_CLIENTE_ETAPA.ID_CLIENTE,SERVICIO_CLIENTE_ETAPA.ID_SERVICIO,SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO,SERVICIO_CLIENTE_ETAPA.REFERENCIA,SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO,SERVICIO_CLIENTE_ETAPA.SG_INTEGRAL,SERVICIO_CLIENTE_ETAPA.FECHA_CREACION,SERVICIO_CLIENTE_ETAPA.HORA_CREACION,SERVICIO_CLIENTE_ETAPA.FECHA_MODIFICACION,SERVICIO_CLIENTE_ETAPA.HORA_MODIFICACION,SERVICIO_CLIENTE_ETAPA.ID_USUARIO_CREACION,SERVICIO_CLIENTE_ETAPA.ID_USUARIO_MODIFICACION,SERVICIO_CLIENTE_ETAPA.CAMBIO,SERVICIO_CLIENTE_ETAPA.ID_REFERENCIA_SEG,SERVICIO_CLIENTE_ETAPA.OBSERVACION_CAMBIO,SERVICIO_CLIENTE_ETAPA.ID_NORMA,SERVICIOS.NOMBRE AS NOMBRE_SERVICIO,TIPOS_SERVICIO.NOMBRE AS NOMBRE_TIPO_SERVICIO,CLIENTES.NOMBRE AS NOMBRE_CLIENTE,ETAPAS_PROCESO.ETAPA AS NOMBRE_ETAPA FROM SERVICIO_CLIENTE_ETAPA INNER JOIN SERVICIOS ON SERVICIO_CLIENTE_ETAPA.ID_SERVICIO = SERVICIOS.ID INNER JOIN TIPOS_SERVICIO ON SERVICIO_CLIENTE_ETAPA.ID_TIPO_SERVICIO = TIPOS_SERVICIO.ID INNER JOIN CLIENTES ON SERVICIO_CLIENTE_ETAPA.ID_CLIENTE = CLIENTES.ID INNER JOIN ETAPAS_PROCESO ON SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO = ETAPAS_PROCESO.ID_ETAPA
".($QUERY?$QUERY:"");

$servicio_cliente_etapa = $database->query($strQuery)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();


for ($i=0; $i < count($servicio_cliente_etapa) ; $i++) {	
	
	if($servicio_cliente_etapa[$i]["ID_SERVICIO"]==3)
	{
        $curso = $database->query("SELECT C.ID_CURSO,C.NOMBRE as ID_NORMA ,SC.CANTIDAD_PARTICIPANTES FROM SCE_CURSOS SC INNER JOIN CURSOS C ON SC.ID_CURSO = C.ID_CURSO WHERE SC.ID_SCE =".$servicio_cliente_etapa[$i]["ID"]." LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
        $servicio_cliente_etapa[$i]["NORMAS"] = $curso;
	}
	else{
		$normas = $database->query("SELECT DISTINCT `ID_NORMA` FROM `SCE_NORMAS` WHERE `ID_SCE`= ".$servicio_cliente_etapa[$i]["ID"])->fetchAll(PDO::FETCH_ASSOC);
		$servicio_cliente_etapa[$i]["NORMAS"] = $normas;
    }

}



print_r(json_encode($servicio_cliente_etapa));



?>
