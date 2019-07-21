<?php
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';

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
// devolver en rangos, ordenados por edad, los montos de las facturas vencidas: hasta 30 días, 31 a 60, 61 a 90, más de 90
//obtenidos a partir de subconsultas: las fact vencidas junto a las emitidas con más de 30 días  
//los estados serían 2:emitida y 3:vencida
$sql = "SELECT SUM(monto) total, diasvencida FROM (
 SELECT IDFact, fech, estAct, estatus, monto, DATEDIFF(CURDATE(),fech) diasTransc,
 elt(INTERVAL(DATEDIFF(CURDATE(),fech), 31, 61, 91, 365*10)+1,
 'Hasta 30 días','De 31 a 60 días','De 61 a 90 días','Superior a 90 días') diasvencida FROM 
(SELECT ID_SOLICITUD IDFact, adddate(STR_TO_DATE(fecha,'%Y%m%d'),30) fech,id_estatus_actual estAct, monto
 FROM facturacion_solicitud_historico fsh
 INNER JOIN facturacion_solicitudes fs ON fsh.ID_SOLICITUD=fs.ID
 WHERE id_estatus_actual=2 AND DATEDIFF(CURDATE(),STR_TO_DATE(fecha,'%Y%m%d'))>30
 AND fs.id NOT IN (SELECT ID_SOLICITUD FROM facturacion_solicitud_historico WHERE id_estatus_actual=3)
UNION
 SELECT ID_SOLICITUD IDFact, STR_TO_DATE(fecha,'%Y%m%d') fech,id_estatus_actual estAct, monto
 FROM facturacion_solicitud_historico fsh 
 INNER JOIN facturacion_solicitudes fs ON fsh.ID_SOLICITUD=fs.ID
 WHERE id_estatus_actual=3) resumen INNER JOIN facturacion_solicitud_estatus fse ON fse.ID=resumen.estAct
 WHERE estAct=3 OR DATEDIFF(CURDATE(),fech)>0) cartera GROUP BY diasvencida ORDER BY diasTransc;";

$count = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die();
		
$respuesta=$count;

print_r(json_encode($respuesta));