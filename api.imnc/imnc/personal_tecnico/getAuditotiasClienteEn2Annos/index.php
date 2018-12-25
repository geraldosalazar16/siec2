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
$FLAG = "si";
$razon = "";
$respuesta=array();
$json = file_get_contents("php://input");
$objeto = json_decode($json);

if($objeto!=null){
    $id_cliente = $objeto->ID_CLIENTE;
    valida_parametro_and_die($id_cliente, "Es necesario un id cliente");
    $id_auditor = $objeto->ID_AUDITOR;
    valida_parametro_and_die($id_auditor, "Es necesario un id auditor");
    $fecha = $objeto->FECHA; ///format dd/mm/yyyy
    valida_parametro_and_die($fecha, "Es necesario una fecha");

}else{
    $id_cliente = $_REQUEST["ID_CLIENTE"];
    valida_parametro_and_die($id_cliente, "Es necesario un id cliente");
    $id_auditor = $_REQUEST["ID_AUDITOR"];
    valida_parametro_and_die($id_auditor, "Es necesario un id auditor");
    $fecha = $_REQUEST["FECHA"]; ///format dd/mm/yyyy
    valida_parametro_and_die($fecha, "Es necesario una fecha");
}







$array = explode("/",$fecha);
$fecha_fin = date("Ymd", strtotime($array[2].$array[1].$array[0]));

$years_old = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")-2);
$years_old = date('Ymd',$years_old);


$query = "SELECT AGF.FECHA FROM PERSONAL_TECNICO_CALIFICACIONES PTC INNER JOIN I_SG_AUDITORIA_GRUPO_FECHAS AGF ON PTC.ID = AGF.ID_PERSONAL_TECNICO_CALIF INNER JOIN SERVICIO_CLIENTE_ETAPA SCE ON AGF.ID_SERVICIO_CLIENTE_ETAPA = SCE.ID WHERE PTC.ID_PERSONAL_TECNICO = ".$id_auditor." AND SCE.ID_CLIENTE = ".$id_cliente;

$fechas = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
valida_error_medoo_and_die();

for ($i=0; $i < count($fechas) ; $i++) {
    $FECHA = date("Ymd", strtotime($fechas[$i]["FECHA"]));

    if($FECHA>=$years_old && $FECHA<=$fecha_fin)
    {
        $FLAG = "no";
        $razon = "Ese auditor no puede impartile cursos a ese cliente, última auditoría al cliente: ".substr($FECHA,6,8)."-".substr($FECHA,-4,2)."-".substr($FECHA,0,4);
        goto handle_errors;
        break;


    }
}


handle_errors:
if($FLAG=="si")
{
    $respuesta["disponible"]=$FLAG;
}
else{
    $respuesta["disponible"]=$FLAG;
    $respuesta["razon"]=$razon;
}



print_r(json_encode($respuesta));



//-------- FIN --------------
?>