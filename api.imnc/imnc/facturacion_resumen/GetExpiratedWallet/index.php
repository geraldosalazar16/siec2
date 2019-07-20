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
 
$sql = "SELECT SUM(monto) total, diasvencida FROM (
 SELECT IDFact, fech, estAct, estatus, monto, DATEDIFF(CURDATE(),fech) hace,
 elt(INTERVAL(DATEDIFF(CURDATE(),fech), 31, 61, 91, 365*10)+1,
 'Hasta 30 días','De 31 a 60 dias','De 61 a 90 dias','Superior a 90 dias') diasvencida FROM 
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
 WHERE estAct=3 OR DATEDIFF(CURDATE(),fech)>30) cartera GROUP BY diasvencida;";

$count = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die();
		
$respuesta=$count;

print_r(json_encode($respuesta));