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
$meses = array("Enero" => "01","Febrero" => "02","Marzo" => "03","Abril" => "04","Mayo" => "05","Junio" => "06","Julio" => "07","Agosto" => "08","Septiembre" => "09","Octubre" => "10","Noviembre" => "11","Diciembre" => "12");


$periodicidad = $_REQUEST["periodicidad"];
$valor = $_REQUEST["valor"];


$where1 = '';
$where2 = '';
$where_acumulado1 = '';
$str_fechas1 = '';
$str_fechas2 = '';
$mes1 = '';
$mes2 = '';
$year = date("Y");
$mes_acumulado1 = $meses["Enero"];
$mes_acumulado2 = '';

if($periodicidad==1)
{
	$mes1 = $meses["Enero"];
	$mes2 = $meses["Diciembre"];
	$year = $valor;
	$mes_acumulado2 = $meses["Diciembre"];

}
if($periodicidad==2)
{
	$mes1 = $meses[$valor];
	$mes2 = $meses[$valor];
	$mes_acumulado2 = $meses[$valor];

}

$fechas_inicio = $year.'-' . $mes1 . '-01';
$fechas_fin =  $year.'-' . $mes2 . '-31';
$fechas_inicio_acumulado = $year.'-' . $mes_acumulado1 . '-01';
$fechas_fin_acumulado =  $year.'-' . $mes_acumulado2 . '-31';


$str_fechas1 .= ' (';
$str_fechas2 .= ' ';
foreach ($columnas as $index=>$s)
{
	if($index==4)
	{
		$str_fechas2 .= "CSF.".$s." BETWEEN '".$fechas_inicio."' AND '".$fechas_fin."' ";
	}

		$str_fechas1 .= "CSF.".$s." BETWEEN '".$fechas_inicio."' AND '".$fechas_fin."' OR ";



}
$str_fechas1 = substr($str_fechas1, 0, -4);
$str_fechas1 .= ') ';

	$where1 .= ' '.$str_fechas1;
	$where2 .= ' '.$str_fechas2;


$sql = "SELECT TIPO,SUM(TOTALG) AS TOTALG,SUM(TOTALE)  AS TOTALE, SUM(ACTIVAS) AS ACTIVAS  FROM(
SELECT 
CASE 
	WHEN C.BANDERA = 0 THEN
		'CONTRATOS NUEVOS'
	ELSE
		CASE 
	WHEN C.BANDERA = 1 && P.ID_CLIENTE > 0 && P.ID_TIPO_CONTRATO = 3 THEN
		'RECERTIFICACIONES'
	ELSE
		''
END 
END AS TIPO
,
0 AS TOTALG,  
CASE  
	WHEN C.ID_TIPO_SERVICIO = 1 || C.ID_TIPO_SERVICIO = 2 || C.ID_TIPO_SERVICIO = 12 || C.ID_TIPO_SERVICIO = 20 THEN
		(SELECT SUM(CT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES CT WHERE C.ID = CT.ID_COTIZACION GROUP BY CT.ID_COTIZACION ORDER BY CT.ID_COTIZACION)  
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 16 THEN
		(SELECT SUM(CTC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CIL CTC WHERE C.ID = CTC.ID_COTIZACION GROUP BY CTC.ID_COTIZACION ORDER BY CTC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 17 THEN
		(SELECT SUM(CTT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_TUR CTT WHERE C.ID = CTT.ID_COTIZACION GROUP BY CTT.ID_COTIZACION ORDER BY CTT.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 18 THEN
		(SELECT SUM(CTIC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_INF_COM CTIC WHERE C.ID = CTIC.ID_COTIZACION GROUP BY CTIC.ID_COTIZACION ORDER BY CTIC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 19 THEN
		(SELECT SUM(CTCP.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CPER CTCP WHERE C.ID = CTCP.ID_COTIZACION GROUP BY CTCP.ID_COTIZACION ORDER BY CTCP.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 14 THEN
		(SELECT SUM(CTDH.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_DH CTDH WHERE C.ID = CTDH.ID_COTIZACION GROUP BY CTDH.ID_COTIZACION ORDER BY CTDH.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 15 THEN
		(SELECT SUM(CTHM.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_HM CTHM WHERE C.ID = CTHM.ID_COTIZACION GROUP BY CTHM.ID_COTIZACION ORDER BY CTHM.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 13 THEN
		(SELECT SUM(CTPIND.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_PIND CTPIND WHERE C.ID = CTPIND.ID_COTIZACION GROUP BY CTPIND.ID_COTIZACION ORDER BY CTPIND.ID_COTIZACION)
	ELSE
		0
  END 
  END 
  END 
  END 
  END 
  END 
  END 
  END  AS TOTALE,0 AS ACTIVAS
  FROM PROSPECTO P INNER JOIN COTIZACIONES C ON P.ID = C.ID_PROSPECTO INNER JOIN COTIZACIONES_STATUS_FECHA CSF ON C.ID = CSF.ID_COTIZACION WHERE C.ESTADO_COTIZACION = 2 AND  ".$where1."
	UNION ALL
	SELECT 
CASE 
	WHEN C.BANDERA = 0 THEN
		'CONTRATOS NUEVOS'
	ELSE
		CASE 
	WHEN C.BANDERA = 1 && P.ID_CLIENTE > 0 && P.ID_TIPO_CONTRATO = 3 THEN
		'RECERTIFICACIONES'
	ELSE
		''
END 
END AS TIPO
,  
CASE  
	WHEN C.ID_TIPO_SERVICIO = 1 || C.ID_TIPO_SERVICIO = 2 || C.ID_TIPO_SERVICIO = 12 || C.ID_TIPO_SERVICIO = 20 THEN
		(SELECT SUM(CT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES CT WHERE C.ID = CT.ID_COTIZACION GROUP BY CT.ID_COTIZACION ORDER BY CT.ID_COTIZACION)  
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 16 THEN
		(SELECT SUM(CTC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CIL CTC WHERE C.ID = CTC.ID_COTIZACION GROUP BY CTC.ID_COTIZACION ORDER BY CTC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 17 THEN
		(SELECT SUM(CTT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_TUR CTT WHERE C.ID = CTT.ID_COTIZACION GROUP BY CTT.ID_COTIZACION ORDER BY CTT.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 18 THEN
		(SELECT SUM(CTIC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_INF_COM CTIC WHERE C.ID = CTIC.ID_COTIZACION GROUP BY CTIC.ID_COTIZACION ORDER BY CTIC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 19 THEN
		(SELECT SUM(CTCP.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CPER CTCP WHERE C.ID = CTCP.ID_COTIZACION GROUP BY CTCP.ID_COTIZACION ORDER BY CTCP.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 14 THEN
		(SELECT SUM(CTDH.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_DH CTDH WHERE C.ID = CTDH.ID_COTIZACION GROUP BY CTDH.ID_COTIZACION ORDER BY CTDH.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 15 THEN
		(SELECT SUM(CTHM.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_HM CTHM WHERE C.ID = CTHM.ID_COTIZACION GROUP BY CTHM.ID_COTIZACION ORDER BY CTHM.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 13 THEN
		(SELECT SUM(CTPIND.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_PIND CTPIND WHERE C.ID = CTPIND.ID_COTIZACION GROUP BY CTPIND.ID_COTIZACION ORDER BY CTPIND.ID_COTIZACION)
	ELSE
		0
  END 
  END 
  END 
  END 
  END 
  END 
  END 
  END  AS TOTALG,0 AS TOTALE,0 AS ACTIVAS
   FROM PROSPECTO P INNER JOIN COTIZACIONES C ON P.ID = C.ID_PROSPECTO LEFT JOIN COTIZACIONES_STATUS_FECHA CSF ON C.ID = CSF.ID_COTIZACION WHERE C.ESTADO_COTIZACION = 4   AND  ".$where2." 
	
	 UNION ALL
SELECT 
CASE 
	WHEN C.BANDERA = 0 THEN
		'CONTRATOS NUEVOS'
	ELSE
		CASE 
	WHEN C.BANDERA = 1 && P.ID_CLIENTE > 0 && P.ID_TIPO_CONTRATO = 3 THEN
		'RECERTIFICACIONES'
	ELSE
		''
END 
END AS TIPO,
0 AS TOTALG,0 AS TOTALE,
CASE  
	WHEN C.ID_TIPO_SERVICIO = 1 || C.ID_TIPO_SERVICIO = 2 || C.ID_TIPO_SERVICIO = 12 || C.ID_TIPO_SERVICIO = 20 THEN
		(SELECT SUM(CT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES CT WHERE C.ID = CT.ID_COTIZACION GROUP BY CT.ID_COTIZACION ORDER BY CT.ID_COTIZACION)  
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 16 THEN
		(SELECT SUM(CTC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CIL CTC WHERE C.ID = CTC.ID_COTIZACION GROUP BY CTC.ID_COTIZACION ORDER BY CTC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 17 THEN
		(SELECT SUM(CTT.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_TUR CTT WHERE C.ID = CTT.ID_COTIZACION GROUP BY CTT.ID_COTIZACION ORDER BY CTT.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 18 THEN
		(SELECT SUM(CTIC.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_INF_COM CTIC WHERE C.ID = CTIC.ID_COTIZACION GROUP BY CTIC.ID_COTIZACION ORDER BY CTIC.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 19 THEN
		(SELECT SUM(CTCP.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_CPER CTCP WHERE C.ID = CTCP.ID_COTIZACION GROUP BY CTCP.ID_COTIZACION ORDER BY CTCP.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 14 THEN
		(SELECT SUM(CTDH.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_DH CTDH WHERE C.ID = CTDH.ID_COTIZACION GROUP BY CTDH.ID_COTIZACION ORDER BY CTDH.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 15 THEN
		(SELECT SUM(CTHM.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_HM CTHM WHERE C.ID = CTHM.ID_COTIZACION GROUP BY CTHM.ID_COTIZACION ORDER BY CTHM.ID_COTIZACION)
	ELSE
		CASE 
	WHEN C.ID_TIPO_SERVICIO = 13 THEN
		(SELECT SUM(CTPIND.MONTO) AS TOTAL FROM COTIZACIONES_TRAMITES_PIND CTPIND WHERE C.ID = CTPIND.ID_COTIZACION GROUP BY CTPIND.ID_COTIZACION ORDER BY CTPIND.ID_COTIZACION)
	ELSE
		0
  END 
  END 
  END 
  END 
  END 
  END 
  END 
  END  AS ACTIVAS
  FROM PROSPECTO P INNER JOIN COTIZACIONES C ON P.ID = C.ID_PROSPECTO INNER JOIN COTIZACIONES_STATUS_FECHA CSF ON C.ID = CSF.ID_COTIZACION WHERE C.ESTADO_COTIZACION = 1 OR C.ESTADO_COTIZACION = 2 OR C.ESTADO_COTIZACION = 3 OR C.ESTADO_COTIZACION = 8 OR C.ESTADO_COTIZACION = 9
	 ) tt
GROUP BY TIPO 
ORDER BY TIPO 
";
$consulta = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

print_r(json_encode($consulta,JSON_PRETTY_PRINT));

?>
