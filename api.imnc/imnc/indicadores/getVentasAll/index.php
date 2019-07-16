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

$sql = 'SELECT DISTINCT P.ID,P.NOMBRE,C.ID AS ID_COTIZACION,CONCAT(\'$\',FORMAT(C.MONTO,2)) AS MONTO,CONCAT(\'$\',FORMAT((SELECT SUM(CS.MONTO) FROM COTIZACIONES CS LEFT JOIN COTIZACIONES_STATUS_FECHA CSFS ON CS.ID = CSFS.ID_COTIZACION WHERE C.ESTADO_COTIZACION = CS.ESTADO_COTIZACION GROUP BY CS.ESTADO_COTIZACION ORDER BY CS.ESTADO_COTIZACION),2)) AS TOTAL,PES.ID  AS ID_STATUS,PES.ESTATUS_SEGUIMIENTO FROM PROSPECTO P LEFT JOIN COTIZACIONES C ON P.ID = C.ID_PROSPECTO LEFT JOIN COTIZACIONES_STATUS_FECHA CSF ON C.ID = CSF.ID_COTIZACION INNER JOIN PROSPECTO_ESTATUS_SEGUIMIENTO PES ON C.ESTADO_COTIZACION = PES.ID  ORDER BY PES.ID';

$consulta = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

print_r(json_encode($consulta));
?>
