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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();

$ID_USUARIO = $_REQUEST["id"];
//Determinar el perfil del usuario
$QUERY = "SELECT PERFILES.ID FROM PERFIL_MODULO_USUARIO
INNER JOIN USUARIOS ON USUARIOS.ID = PERFIL_MODULO_USUARIO.ID_USUARIO
INNER JOIN MODULOS ON MODULOS.ID = PERFIL_MODULO_USUARIO.ID_MODULO
INNER JOIN PERFILES ON PERFILES.ID = PERFIL_MODULO_USUARIO.ID_PERFIL
WHERE MODULOS.ID = 1
AND USUARIOS.ID = ".$ID_USUARIO;
$perfil = $database->query($QUERY)->fetchAll();
$valor_perfil = $perfil[0]['ID'];

$where = " WHERE P.ID_USUARIO_PRINCIPAL = ".$ID_USUARIO." OR P.ID_USUARIO_SECUNDARIO = ".$ID_USUARIO;
if($valor_perfil == 1 || $valor_perfil == 3 || $valor_perfil == 9)
{
    $where = ";";
}
$consulta = "SELECT
    P.ID AS ID,
    ID_CLIENTE,
    P.RFC AS RFC,
    P.NOMBRE AS NOMBRE,
    P.ORIGEN AS ORIGEN,
    P.ID_ESTATUS_SEGUIMIENTO,
    p_estatus_seguimiento.ESTATUS_SEGUIMIENTO AS NOMBRE_ESTATUS_SEGUIMIENTO,
    P.ID_TIPO_CONTRATO,
    p_tipo_contrato.TIPO_CONTRATO AS NOMBRE_TIPO_CONTRATO,
    GIRO,
    P.FECHA_CREACION,
    P.FECHA_MODIFICACION,
    ACTIVO,
    p_porcentaje.PORCENTAJE AS PORCENTAJE
FROM
    PROSPECTO P
LEFT JOIN PROSPECTO_ESTATUS_SEGUIMIENTO p_estatus_seguimiento ON
    P.ID_ESTATUS_SEGUIMIENTO = p_estatus_seguimiento.ID
LEFT JOIN PROSPECTO_TIPO_CONTRATO p_tipo_contrato ON
    P.ID_TIPO_CONTRATO = p_tipo_contrato.ID
LEFT JOIN PROSPECTO_PORCENTAJE p_porcentaje ON
    P.ID_PORCENTAJE = p_porcentaje.ID"
	.$where; 

$tareas = $database->query($consulta)->fetchAll();
valida_error_medoo_and_die();

print_r(json_encode($tareas));


//-------- FIN --------------
?>