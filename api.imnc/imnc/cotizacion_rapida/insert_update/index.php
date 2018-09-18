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

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$ID_PROSPECTO = $objeto->id_prospecto;
valida_parametro_and_die($ID_PROSPECTO, "Falta ID del prospecto");

//Determinar si existe la cotizacion
$existe_cotizacion = $database->get("COTIZACION_RAPIDA","ID",["ID_PROSPECTO" => $ID_PROSPECTO]);
valida_error_medoo_and_die();

//Si existe la cotizacion
if($existe_cotizacion){
   $id = $database->update("COTIZACION_RAPIDA",[
        "FECHA_E1" => $objeto->FECHA_E1,
        "FECHA_E2" => $objeto->FECHA_E2,
        "FECHA_V1" => $objeto->FECHA_V1,
        "FECHA_V2" => $objeto->FECHA_V2,
        "FECHA_V3" => $objeto->FECHA_V3,
        "FECHA_V4" => $objeto->FECHA_V4,
        "FECHA_V5" => $objeto->FECHA_V5,
        "MONTO_E1" => $objeto->MONTO_E1,
        "MONTO_E2" => $objeto->MONTO_E2,
        "MONTO_V1" => $objeto->MONTO_V1,
        "MONTO_V2" => $objeto->MONTO_V2,
        "MONTO_V3" => $objeto->MONTO_V3,
        "MONTO_V4" => $objeto->MONTO_V4,
        "MONTO_V5" => $objeto->MONTO_V5,
        "DIAS_E1" => $objeto->DIAS_E1,
        "DIAS_E2" => $objeto->DIAS_E2,
        "DIAS_V1" => $objeto->DIAS_V1,
        "DIAS_V2" => $objeto->DIAS_V2,
        "DIAS_V3" => $objeto->DIAS_V3,
        "DIAS_V4" => $objeto->DIAS_V4,
        "DIAS_V5" => $objeto->DIAS_V5,
        "NO_EMPLEADOS" => $objeto->CANTIDAD_EMPLEADOS,
        "NO_SITIOS" => $objeto->CANTIDAD_SITIOS,
        "CERTIFICADO_ACREDITADO" => $objeto->CERTIFICADO,
        "VIATICOS_INCLUIDOS" => $objeto->VIATICOS,
        "IVA_INCLUIDO" => $objeto->IVA
       ],
       ["ID" => $existe_cotizacion["ID"]]
   ); 
   valida_error_medoo_and_die();
   $respuesta['resultado']="ok_modificacion";
}
else{ //insert
    $id = $database->insert("COTIZACION_RAPIDA",[
        "ID_PROSPECTO" => $ID_PROSPECTO,
        "FECHA_E1" => $objeto->FECHA_E1,
        "FECHA_E2" => $objeto->FECHA_E2,
        "FECHA_V1" => $objeto->FECHA_V1,
        "FECHA_V2" => $objeto->FECHA_V2,
        "FECHA_V3" => $objeto->FECHA_V3,
        "FECHA_V4" => $objeto->FECHA_V4,
        "FECHA_V5" => $objeto->FECHA_V5,
        "MONTO_E1" => $objeto->MONTO_E1,
        "MONTO_E2" => $objeto->MONTO_E2,
        "MONTO_V1" => $objeto->MONTO_V1,
        "MONTO_V2" => $objeto->MONTO_V2,
        "MONTO_V3" => $objeto->MONTO_V3,
        "MONTO_V4" => $objeto->MONTO_V4,
        "MONTO_V5" => $objeto->MONTO_V5,
        "DIAS_E1" => $objeto->DIAS_E1,
        "DIAS_E2" => $objeto->DIAS_E2,
        "DIAS_V1" => $objeto->DIAS_V1,
        "DIAS_V2" => $objeto->DIAS_V2,
        "DIAS_V3" => $objeto->DIAS_V3,
        "DIAS_V4" => $objeto->DIAS_V4,
        "DIAS_V5" => $objeto->DIAS_V5,
        "NO_EMPLEADOS" => $objeto->CANTIDAD_EMPLEADOS,
        "NO_SITIOS" => $objeto->CANTIDAD_SITIOS,
        "CERTIFICADO_ACREDITADO" => $objeto->CERTIFICADO,
        "VIATICOS_INCLUIDOS" => $objeto->VIATICOS,
        "IVA_INCLUIDO" => $objeto->IVA
       ]
   ); 
   valida_error_medoo_and_die();
   $respuesta['resultado']="ok_insercion";   
}
print_r(json_encode($respuesta)); 
?> 
