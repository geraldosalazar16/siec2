<?php  
include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

function valida_parametro_and_die($parametro, $mensaje_error){ 
	$parametro = "" . $parametro; 
	if ($parametro == "" or is_null($parametro)) { 
		$respuesta["resultado"] = "error\n"; 
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
		$mailerror->send("SERVICIO_CLIENTE_ETAPA", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json); 

$ID = $objeto->ID;
valida_parametro_and_die($ID, "Falta ID de servicio_cliente_etapa");; 

$ID_CLIENTE = $objeto->ID_CLIENTE; 
valida_parametro_and_die($ID_CLIENTE, "Es necesario seleccionar un cliente");

$ID_SERVICIO = $objeto->ID_SERVICIO; 
valida_parametro_and_die($ID_SERVICIO, "Es necesario seleccionar un servicio");

$ID_TIPO_SERVICIO	= $objeto->ID_TIPO_SERVICIO; 
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un tipo de servicio");

$ID_ETAPA_PROCESO = $objeto->ID_ETAPA_PROCESO;
valida_parametro_and_die($ID_ETAPA_PROCESO, "Es neceario seleccionar un trámite");

//$SG_INTEGRAL = $objeto->SG_INTEGRAL; // opcional

$REFERENCIA = $objeto->REFERENCIA;
valida_parametro_and_die($REFERENCIA, "Es necesario capturar la referencia");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");
$CANTIDAD = "";
$NORMAS= "";
$CAMBIO= "";
if($ID_SERVICIO==3)
{
    $NORMAS = $objeto->NORMAS;
    valida_parametro_and_die($NORMAS, "Es neceario seleccionar un curso");
    $CANTIDAD = $objeto->CANTIDAD;
    valida_parametro_and_die($CANTIDAD, "Es neceario seleccionar la cantidad de participantes");
}else{
    $NORMAS= $objeto->NORMAS;
    if(count($NORMAS) == 0){
        $respuesta['resultado']="error";
        $respuesta['mensaje']="Es necesario seleccionar una norma";
        print_r(json_encode($respuesta));
        die();
    }


    $CAMBIO= $objeto->CAMBIO;
}

//$ID_REFERENCIA_SEG= $objeto->ID_REFERENCIA_SEG;
//$OBSERVACION_CAMBIO= $objeto->OBSERVACION_CAMBIO;

$FECHA_MODIFICACION = date("Ymd");
$HORA_MODIFICACION = date("His");
/****************************************************/
$ID_ETAPA_ANTERIOR	=	$database->get("SERVICIO_CLIENTE_ETAPA","ID_ETAPA_PROCESO",["ID"=>$ID]);
$consulta = "SELECT SCE.ID,SCE.REFERENCIA,SCE.ID_SERVICIO,
            (SELECT C.NOMBRE FROM CLIENTES C WHERE C.ID=SCE.ID_CLIENTE) AS CLIENTE,
            (SELECT S.NOMBRE  FROM SERVICIOS S WHERE S.ID=SCE.ID_SERVICIO) AS SERVICIO,
            (SELECT TS.NOMBRE FROM TIPOS_SERVICIO TS WHERE TS.ID=SCE.ID_TIPO_SERVICIO) AS TIPO_SERVICIO,
            (SELECT CU.NOMBRE FROM SCE_CURSOS SCEC INNER JOIN CURSOS CU ON SCEC.ID_CURSO=CU.ID_CURSO WHERE SCEC.ID_SCE=SCE.ID) AS CURSO,
            (SELECT GROUP_CONCAT(N.ID SEPARATOR ', ')   FROM SCE_NORMAS SCEN INNER JOIN NORMAS N ON SCEN.ID_NORMA=N.ID WHERE SCEN.ID_SCE=SCE.ID GROUP BY SCEN.ID_SCE) AS NORMA,
            (SELECT EP.ETAPA FROM ETAPAS_PROCESO EP WHERE EP.ID_ETAPA=SCE.ID_ETAPA_PROCESO) AS ETAPA FROM SERVICIO_CLIENTE_ETAPA SCE WHERE SCE.ID =".$ID;
$sce_anterior = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
/****************************************************/


    $id_sce = $database->update("SERVICIO_CLIENTE_ETAPA", [
        "ID_CLIENTE" => $ID_CLIENTE,
        "ID_SERVICIO" => $ID_SERVICIO,
        "ID_TIPO_SERVICIO"=>	$ID_TIPO_SERVICIO,
        "ID_ETAPA_PROCESO" => $ID_ETAPA_PROCESO,
        //"SG_INTEGRAL" => $SG_INTEGRAL,
        "REFERENCIA" => $REFERENCIA,
        "FECHA_MODIFICACION" => $FECHA_MODIFICACION,
        "HORA_MODIFICACION" => $HORA_MODIFICACION,
        "ID_USUARIO_MODIFICACION" => $ID_USUARIO_MODIFICACION,
        "CAMBIO"=>$CAMBIO,
        "CANTIDAD_PARTICIPANTES"=>$CANTIDAD
        //"ID_REFERENCIA_SEG"=>$ID_REFERENCIA_SEG,
        //"OBSERVACION_CAMBIO"=>$OBSERVACION_CAMBIO
    ],
        ["ID"=>$ID]
    );

valida_error_medoo_and_die();
if($ID_SERVICIO==3)  //para cifa
{
    if($id_sce != 0)
    {
        $id1 = $database->update("SCE_CURSOS", ["ID_CURSO"=>$NORMAS,"CANTIDAD_PARTICIPANTES"=>$CANTIDAD], ["ID_SCE"=>$ID]);

        $consulta = "SELECT SCE.ID,SCE.REFERENCIA,SCE.ID_SERVICIO,
                  (SELECT C.NOMBRE FROM CLIENTES C WHERE C.ID=SCE.ID_CLIENTE) AS CLIENTE,
                  (SELECT S.NOMBRE  FROM SERVICIOS S WHERE S.ID=SCE.ID_SERVICIO) AS SERVICIO,
                  (SELECT TS.NOMBRE FROM TIPOS_SERVICIO TS WHERE TS.ID=SCE.ID_TIPO_SERVICIO) AS TIPO_SERVICIO,
                  (SELECT CU.NOMBRE FROM SCE_CURSOS SCEC INNER JOIN CURSOS CU ON SCEC.ID_CURSO=CU.ID_CURSO WHERE SCEC.ID_SCE=SCE.ID) AS CURSO,
                  (SELECT GROUP_CONCAT(N.ID SEPARATOR ', ')   FROM SCE_NORMAS SCEN INNER JOIN NORMAS N ON SCEN.ID_NORMA=N.ID WHERE SCEN.ID_SCE=SCE.ID GROUP BY SCEN.ID_SCE) AS NORMA,
                  (SELECT EP.ETAPA FROM ETAPAS_PROCESO EP WHERE EP.ID_ETAPA=SCE.ID_ETAPA_PROCESO) AS ETAPA 
                  FROM SERVICIO_CLIENTE_ETAPA SCE WHERE SCE.ID =".$ID;
        $sce_actual = $database->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
        $estado_actual = "ID: ".$sce_actual[0]["ID"].", Referencia: ".$sce_actual[0]["REFERENCIA"].", Cliente: ".$sce_actual[0]["CLIENTE"].", Servicio: ".$sce_actual[0]["SERVICIO"].", ".($sce_actual[0]["ID_SERVICIO"] == 3 ? "Módulo: ":"Tipo de Servicio: ").$sce_actual[0]["TIPO_SERVICIO"].", ".($sce_actual[0]["ID_SERVICIO"] == 3 ? ("Curso: ".$sce_actual[0]["CURSO"]):("Normas: ".$sce_actual[0]["NORMA"])).", Etapa: ".$sce_actual[0]["ETAPA"];
        $estado_anterior = "ID: ".$sce_anterior[0]["ID"].", Referencia: ".$sce_anterior[0]["REFERENCIA"].", Cliente: ".$sce_anterior[0]["CLIENTE"].", Servicio: ".$sce_anterior[0]["SERVICIO"].", ".($sce_anterior[0]["ID_SERVICIO"] == 3 ? "Módulo: ":"Tipo de Servicio: ").$sce_anterior[0]["TIPO_SERVICIO"].", ".($sce_anterior[0]["ID_SERVICIO"] == 3 ? ("Curso: ".$sce_anterior[0]["CURSO"]):("Normas: ".$sce_anterior[0]["NORMA"])).", Etapa: ".$sce_anterior[0]["ETAPA"];
        $id2=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
            "ID_SERVICIO_CONTRATADO" => $ID,
            "MODIFICACION" => "MODIFICANDO CIFA",
            "ESTADO_ANTERIOR"=>	$estado_anterior,
            "ESTADO_ACTUAL"=>	$estado_actual,
            "USUARIO" => $ID_USUARIO_MODIFICACION,
            "FECHA_USUARIO" => $FECHA_MODIFICACION,
            "FECHA_MODIFICACION" => date("Ymd")]);
    }
} else {
    //Elimino las normas cargadas
    $database->delete("SCE_NORMAS",[
        "ID_SCE" => $ID
    ]);

    //Inserto las normas capturadas
    for ($i=0; $i < count($NORMAS); $i++) {
        $id_norma = $NORMAS[$i]->ID_NORMA;
        //Validar si ya está insertada la norma
        $cant = $database->count("SCE_NORMAS", [
            "AND"=>[ 
                "ID_SCE" => $ID,
                "ID_NORMA" => $id_norma
            ]
        ]);
        if($cant == 0 ){
            $id_sce_normas = $database->insert("SCE_NORMAS", [
                "ID_SCE" => $ID,
                "ID_NORMA" => $id_norma
            ]);
        }            
        valida_error_medoo_and_die();
    }

/*******************************************************/

    /*CODIGO PARA OBTENER EL CICLO DE ESTE SERVICIO*/
    $C1=explode("-",$REFERENCIA);
    $C2=explode("C",$C1[0]);
    $CICLO=$C2[1];


    $CHK	=	explode(";",$objeto->CHK);
    $DESCRIPCION	=	explode(";",$objeto->DESCRIPCION);
    $respuesta["resultado"]	=	"ok";
    $respuesta["id"]	=	$ID_ETAPA_ANTERIOR;

    /*Este codigo es para agregar los cambios que se realicen*/
    if($CAMBIO=="S"){

        for($i=0;$i<count($CHK)-1;$i++){

            if($database->count("I_SERVICIOS_CONTRATADOS_CAMBIOS",["AND"=>[
                        "ID_SERVICIO_CONTRATADO"=>$ID,
                        "ID_TIPO_CAMBIO"=>$CHK[$i],
                        "ID_ETAPA"=>$ID_ETAPA_PROCESO,
                        "CICLO"=>$CICLO,]
                    ]
                )== 1){
                $camb_desc	=	$database->get("I_SERVICIOS_CONTRATADOS_CAMBIOS","DESCRIPCION",["AND"=>[
                    "ID_SERVICIO_CONTRATADO"=>$ID,
                    "ID_TIPO_CAMBIO"=>$CHK[$i],
                    "ID_ETAPA"=>$ID_ETAPA_PROCESO,
                    "CICLO"=>$CICLO,]
                ]);
                $id1	=	$database->update("I_SERVICIOS_CONTRATADOS_CAMBIOS",["DESCRIPCION"=>$DESCRIPCION[$i],"FECHA"=>date("Ymd")],["AND"=>[
                    "ID_SERVICIO_CONTRATADO"=>$ID,
                    "ID_TIPO_CAMBIO"=>$CHK[$i],
                    "ID_ETAPA"=>$ID_ETAPA_PROCESO,
                    "CICLO"=>$CICLO,]
                ]);
                if($camb_desc	!=	$DESCRIPCION[$i]){
                    $idc1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
                        "ID_SERVICIO_CONTRATADO" => $ID,
                        "MODIFICACION" => "MODIFICACION CAMBIO",
                        "ESTADO_ANTERIOR"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$camb_desc,
                        "ESTADO_ACTUAL"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$DESCRIPCION[$i],
                        "USUARIO" => $ID_USUARIO_MODIFICACION,
                        "FECHA_USUARIO" => $FECHA_MODIFICACION,
                        "FECHA_MODIFICACION" => date("Ymd"),
                    ]);
                }

            }else{
                $id1	=	$database->insert("I_SERVICIOS_CONTRATADOS_CAMBIOS",[
                    "ID_SERVICIO_CONTRATADO"=>$ID,
                    "ID_TIPO_CAMBIO"=>$CHK[$i],
                    "ID_ETAPA"=>$ID_ETAPA_PROCESO,
                    "CICLO"=>$CICLO,
                    "DESCRIPCION"=>$DESCRIPCION[$i],
                    "FECHA"=>date("Ymd")
                ]);
                $idc1=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
                    "ID_SERVICIO_CONTRATADO" => $ID,
                    "MODIFICACION" => "INSERCION CAMBIO",
                    "ESTADO_ANTERIOR"=>	"",
                    "ESTADO_ACTUAL"=>	$CHK[$i]."@#$".$ID_ETAPA_PROCESO."@#$".$CICLO."@#$".$DESCRIPCION[$i],
                    "USUARIO" => $ID_USUARIO_MODIFICACION,
                    "FECHA_USUARIO" => $FECHA_MODIFICACION,
                    "FECHA_MODIFICACION" => date("Ymd"),
                ]);
            }


        }
    }
    /*******************************************************/
    if($ID_ETAPA_ANTERIOR!=$ID_ETAPA_PROCESO){
        $idet=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
            "ID_SERVICIO_CONTRATADO" => $ID,
            "MODIFICACION" => "MODIFICACION DE ETAPA",
            "ESTADO_ANTERIOR"=>	$ID_ETAPA_ANTERIOR,
            "ESTADO_ACTUAL"=>	$ID_ETAPA_PROCESO,
            "USUARIO" => $ID_USUARIO_MODIFICACION,
            "FECHA_USUARIO" => $FECHA_MODIFICACION,
            "FECHA_MODIFICACION" => date("Ymd"),

        ]);

    }

}


$respuesta["resultado"]="ok";

print_r(json_encode($respuesta)); 
?> 