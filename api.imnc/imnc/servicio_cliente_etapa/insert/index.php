<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  
include '../../ex_common/archivos.php';
include 'funciones.php';
include  '../../common/jwt.php'; 

use \Firebase\JWT\JWT;

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
$CANTIDAD = null;
$NORMAS= '';
if($ID_SERVICIO == 3)
{
     $NORMAS = $objeto->NORMAS;
     valida_parametro_and_die($NORMAS, "Es neceario seleccionar un curso");
     $CANTIDAD = $objeto->CANTIDAD;
     valida_parametro_and_die($CANTIDAD, "Es neceario seleccionar la cantidad de participantes");
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
	//GENERAR TOKEN PARA EL CLIENTE

			//payload
			$data = [
				'ID_CLIENTE' => $ID_CLIENTE,
				'MODALIDAD' => 'insitu',
				'ID_CURSO' => $NORMAS,
				'ID_PROGRAMACION' => $id_sce
			];
			/*
			iss = issuer, servidor que genera el token
			data = payload del JWT
			*/
			$token = array(
				'iss' => $global_apiserver,
				'aud' => $global_apiserver,
				'exp' => time() + $duration,
				'data' => $data
			);

			//Codifica la información usando el $key definido en jwt.php
			$jwt = JWT::encode($token, $key);

			//GUARDAR EL URL SCE_CURSOS
			$url = $insertar_participantes . "?token=" . $jwt;

    $id_sce_normas = $database->insert("SCE_CURSOS", [
        "ID_SCE" => $id_sce,
		"ID_CURSO" => $NORMAS,
		"CANTIDAD_PARTICIPANTES"=>$CANTIDAD,
		"URL_PARTICIPANTES" => $url
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
	$consulta = "SELECT SCE.ID,SCE.REFERENCIA,SCE.ID_SERVICIO,(SELECT C.NOMBRE FROM CLIENTES C WHERE C.ID=SCE.ID_CLIENTE) AS CLIENTE,(SELECT S.NOMBRE  FROM SERVICIOS S WHERE S.ID=SCE.ID_SERVICIO) AS SERVICIO, (SELECT TS.NOMBRE FROM TIPOS_SERVICIO TS WHERE TS.ID=SCE.ID_TIPO_SERVICIO) AS TIPO_SERVICIO,(SELECT CU.NOMBRE FROM SCE_CURSOS SCEC INNER JOIN CURSOS CU ON SCEC.ID_CURSO=CU.ID_CURSO WHERE SCEC.ID_SCE=SCE.ID) AS CURSO,(SELECT GROUP_CONCAT(N.ID SEPARATOR ', ')   FROM SCE_NORMAS SCEN INNER JOIN NORMAS N ON SCEN.ID_NORMA=N.ID WHERE SCEN.ID_SCE=SCE.ID GROUP BY SCEN.ID_SCE) AS NORMA, (SELECT EP.ETAPA 
FROM ETAPAS_PROCESO EP WHERE EP.ID_ETAPA=SCE.ID_ETAPA_PROCESO) AS ETAPA FROM SERVICIO_CLIENTE_ETAPA SCE WHERE SCE.ID =".$id_sce;
    $sce = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
    $estado_actual = "ID: ".$sce[0]["ID"].", Referencia: ".$sce[0]["REFERENCIA"].", Cliente: ".$sce[0]["CLIENTE"].", Servicio: ".$sce[0]["SERVICIO"].", ".($sce[0]["ID_SERVICIO"] == 3 ? "Módulo: ":"Tipo de Servicio: ").$sce[0]["TIPO_SERVICIO"].", ".($sce[0]["ID_SERVICIO"] == 3 ? ("Curso: ".$sce[0]["CURSO"]):("Normas: ".$sce[0]["NORMA"])).", Etapa: ".$sce[0]["ETAPA"];
	$id1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
			"ID_SERVICIO_CONTRATADO" => $id_sce, 
			"MODIFICACION" => "NUEVO SERVICIO", 
			"ESTADO_ANTERIOR"=>	"",
			"ESTADO_ACTUAL"=>	$estado_actual,
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
