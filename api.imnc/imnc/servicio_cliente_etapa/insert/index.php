<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  
include '../../ex_common/archivos.php';
include 'funciones.php';
function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
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

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID_CLIENTE = $objeto->ID_CLIENTE; 
valida_parametro_and_die($ID_CLIENTE, "Es necesario seleccionar un cliente");

$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");

$ID_TIPO_SERVICIO	= $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un tipo de servicio");

$NORMAS= '';
if($ID_SERVICIO == 3)
{
     $NORMAS = $objeto->NORMAS;
}
else{
        $NORMAS= $objeto->NORMAS;
        if(count($NORMAS) == 0){
        $respuesta['resultado']="error";
        $respuesta['mensaje']="Es necesario seleccionar una norma";
        print_r(json_encode($respuesta));
        die();
    }
}


$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO; 
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");

//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");
$CAMBIO= $objeto->CAMBIO;
//$ID_REFERENCIA_SEG= $objeto->ID_REFERENCIA_SEG;
//$OBSERVACION_CAMBIO= $objeto->OBSERVACION_CAMBIO;


$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

$id_sce = $database->insert("SERVICIO_CLIENTE_ETAPA", [ 
	"ID_CLIENTE" => $ID_CLIENTE, 
	"ID_SERVICIO" => $ID_SERVICIO, 
	"ID_TIPO_SERVICIO"=>	$ID_TIPO_SERVICIO,
	"ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO, 
//	"SG_INTEGRAL" => $SG_INTEGRAL, 
	"REFERENCIA" => $REFERENCIA,
	"FECHA_CREACION" => $FECHA_CREACION,
	"HORA_CREACION" => $HORA_CREACION,
	"CAMBIO"=>$CAMBIO,
//	"ID_REFERENCIA_SEG"=>$ID_REFERENCIA_SEG,
    //"OBSERVACION_CAMBIO"=>$OBSERVACION_CAMBIO
]); 
valida_error_medoo_and_die(); 
//Agregar las normas
if($ID_SERVICIO == 3)
{
    $id_sce_normas = $database->insert("SCE_CURSOS", [
        "ID_SCE" => $id_sce,
        "ID_CURSO" => $NORMAS
    ]);
}else{
    for ($i=0; $i < count($NORMAS); $i++) {
        $id_norma = $NORMAS[$i]->ID_NORMA;
        $id_sce_normas = $database->insert("SCE_NORMAS", [
            "ID_SCE" => $id_sce,
            "ID_NORMA" => $id_norma
        ]);
        valida_error_medoo_and_die();
    }
}


if($id_sce	!=	0){
	$id1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [ 
			"ID_SERVICIO_CONTRATADO" => $id_sce, 
			"MODIFICACION" => "NUEVO SERVICIO", 
			"ESTADO_ANTERIOR"=>	"",
			"ESTADO_ACTUAL"=>	"",
			"USUARIO" => $ID_USUARIO_CREACION, 
			"FECHA_USUARIO" => $FECHA_CREACION,
			"FECHA_MODIFICACION" => date("Ymd"),
	
]); 
$respuesta["resultado"]="ok"; 
}


$respuesta["id"]=$id_sce; 

creacion_expediente_registro($id_sce,5,$rutaExpediente, $database);
crea_instancia_expedientes_registro($id_sce,5,$database);


print_r(json_encode($respuesta)); 
?> 
