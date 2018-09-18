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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}

$id_prospecto = $_REQUEST["id_prospecto"]; 
valida_parametro_and_die($id_prospecto,"Es necesario seleccionar un prospecto");
$id_producto = $_REQUEST["id_producto"]; 
$where = "";
if($id_producto == 0 or is_null($id_producto))
{
    $where = "WHERE ID_PROSPECTO = ".$id_prospecto;
}
else
{
    $where = "WHERE ID_PROSPECTO = ".$id_prospecto." AND ID_PRODUCTO = ".$id_producto;
}
$cant_cotizaciones = $database->query("SELECT COUNT(*) as cantidad FROM COTIZACION_RAPIDA ".$where)->fetchAll();
$cotizacion = 0;
if($cant_cotizaciones[0]["cantidad"] > 0)
    $cotizacion = $database->query("SELECT * FROM COTIZACION_RAPIDA ".$where)->fetchAll(); 

/*
for($i=0;$i<count($cotizacion);$i++)
{
    if(!$cotizacion[$i])
    {
        $cotizacion[$i]["FECHA_E1"] = 0;
        $cotizacion[$i]["FECHA_E2"] = 0;
        $cotizacion[$i]["FECHA_V1"] = 0;
        $cotizacion[$i]["FECHA_V2"] = 0;
        $cotizacion[$i]["FECHA_V3"] = 0;
        $cotizacion[$i]["FECHA_V4"] = 0;
        $cotizacion[$i]["FECHA_V5"] = 0;
    
        $cotizacion[$i]["MONTO_E1"] = 0;
        $cotizacion[$i]["MONTO_E2"] = 0;
        $cotizacion[$i]["MONTO_V1"] = 0;
        $cotizacion[$i]["MONTO_V2"] = 0;
        $cotizacion[$i]["MONTO_V3"] = 0;
        $cotizacion[$i]["MONTO_V4"] = 0;
        $cotizacion[$i]["MONTO_V5"] = 0;
        
        $cotizacion[$i]["DIAS_E1"] = 0;
        $cotizacion[$i]["DIAS_E2"] = 0;
        $cotizacion[$i]["DIAS_V1"] = 0;
        $cotizacion[$i]["DIAS_V2"] = 0;
        $cotizacion[$i]["DIAS_V3"] = 0;
        $cotizacion[$i]["DIAS_V4"] = 0;
        $cotizacion[$i]["DIAS_V5"] = 0;
        
        $cotizacion[$i]["CANTIDAD_EMPLEADOS"] = 0;
        $cotizacion[$i]["CANTIDAD_SITIOS"] = 0;
        $cotizacion[$i]["CERTIFICADO"] = "elige";
        $cotizacion[$i]["VIATICOS"] = "elige";
        $cotizacion[$i]["IVA"] = "elige";
        $cotizacion[$i]["NOMBRE"] = "SIN ASIGNAR";
    }
}
*/
//echo $where;
print_r(json_encode($cotizacion)); 
?> 
