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
//obtenidos a partir de subconsultas 
//los estados serían 1:pendiente, 2:emitida y 3:vencida
$sql = "SELECT fech, estAct,  SUM(monto) total, DATEDIFF(CURDATE(),fech) diasTransc,
elt(INTERVAL(DATEDIFF(CURDATE(),fech), 31, 61, 91, 365*10)+1,
'Hasta 30 días','De 31 a 60 días','De 61 a 90 días','Más de 90 días') diasvencida
FROM
(SELECT MONTO, adddate(STR_TO_DATE(fecha,'%Y%m%d'),30) fech,
ID_ESTATUS estAct
FROM FACTURACION_SOLICITUDES fs
INNER JOIN FACTURACION_SOLICITUD_HISTORICO fsh ON fs.ID=fsh.ID_SOLICITUD
WHERE 
fsh.ID_ESTATUS_ANTERIOR=1 AND 
fsh.ID_ESTATUS_ACTUAL=2 AND
ID_ESTATUS=3) RESUMEN
GROUP BY diasvencida ORDER BY diasTransc;";

$count = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die();
		
$respuesta=$count;

print_r(json_encode($respuesta));