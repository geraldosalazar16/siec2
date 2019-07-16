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
$columnas = array(
   "1"=>"FECHA_SOLICITUD_COTIZACION",
   "2"=>"FECHA_ENVIO_COTIZACION",
   "3"=>"FECHA_NEGOCIACION",
   "4"=>"FECHA_FIRMADO",
   "5"=>"FECHA_PEDIDO",
   "6"=>"FECHA_CANCELADO",
   "7"=>"FECHA_EJECUTADO",
   "8"=>"FECHA_ENVIO_CUESTIONARIO",
   "9"=>"FECHA_RECEPCION_CUESTIONARIO",
);

$status = json_decode($_REQUEST["status"]);
$fechas = json_decode($_REQUEST["fechas"]);
$servicio = $_REQUEST["servicio"];
$tipo = $_REQUEST["tipo"];

$where = '';
$str_fechas = '';
if($fechas[0] && $fechas[1]) {
	$f = explode('/', $fechas[0]);
	$fechas_inicio = $f[2] . '-' . $f[1] . '-' . $f[0];
	$f = explode('/', $fechas[1]);
	$fechas_fin = $f[2] . '-' . $f[1] . '-' . $f[0];
}

if(count($status))
{
	$where .= ' PES.ID IN(';
	$str_fechas .= ' (';
	foreach ($status as $s)
	{
		$where .= $s.",";
		$str_fechas .= "CSF.".$columnas[$s]." BETWEEN '".$fechas_inicio."' AND '".$fechas_fin."' OR ";
	}
	$where = substr($where, 0, -1);
	$where .=') ';
	$str_fechas = substr($str_fechas, 0, -4);
	$str_fechas .= ') ';
}
else{
	$str_fechas .= ' (';
	foreach ($columnas as $s)
	{
		$str_fechas .= "CSF.".$s." BETWEEN '".$fechas_inicio."' AND '".$fechas_fin."' OR ";
	}
	$str_fechas = substr($str_fechas, 0, -4);
	$str_fechas .= ') ';
}
if($fechas[0] && $fechas[1])
{
	$where .= ($where?' AND ':'').$str_fechas;
}
if($servicio)
{
	$where .= ($where?' AND ':'')." C.ID_SERVICIO = ".(int)$servicio;
}
if($tipo)
{
	$where .= ($where?' AND ':'')." C.ID_TIPO_SERVICIO = ".(int)$tipo;
}


$sql = "SELECT DISTINCT P.ID,P.NOMBRE,C.ID AS ID_COTIZACION,CONCAT('$',FORMAT(C.MONTO,2)) AS MONTO,CONCAT('$',FORMAT((SELECT SUM(CS.MONTO) FROM COTIZACIONES CS LEFT JOIN COTIZACIONES_STATUS_FECHA CSFS ON CS.ID = CSFS.ID_COTIZACION WHERE C.ESTADO_COTIZACION = CS.ESTADO_COTIZACION GROUP BY CS.ESTADO_COTIZACION ORDER BY CS.ESTADO_COTIZACION),2)) AS TOTAL,PES.ID  AS ID_STATUS,PES.ESTATUS_SEGUIMIENTO FROM PROSPECTO P LEFT JOIN COTIZACIONES C ON P.ID = C.ID_PROSPECTO LEFT JOIN COTIZACIONES_STATUS_FECHA CSF ON C.ID = CSF.ID_COTIZACION INNER JOIN PROSPECTO_ESTATUS_SEGUIMIENTO PES ON C.ESTADO_COTIZACION = PES.ID".($where?' WHERE '.$where:'')." ORDER BY PES.ID ";

$consulta = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();


print_r(json_encode($consulta));

?>
