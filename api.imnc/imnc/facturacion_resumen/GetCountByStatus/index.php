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
// $json = file_get_contents("php://input");
// $objeto = json_decode($json);

$sql = "SELECT ID_estatus,estatus,COUNT(*) cantidad FROM facturacion_solicitudes AS fs, facturacion_solicitud_estatus AS fse WHERE fs.id_estatus=fse.id GROUP BY ID_estatus;";

$count = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);

valida_error_medoo_and_die();
		
$respuesta=$count;
//$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
