<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 15/01/2019
 * Time: 12:13
 */

include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php';
include  '../../common/conn-sendgrid.php';
include  '../../common/jwt.php';
use \Firebase\JWT\JWT;

use \DateTime;

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

$NOMBRE = $objeto->NOMBRE;
valida_parametro_and_die($NOMBRE, "Es necesario el nombre del reporte");

$ID_USUARIO= $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO, "Es necesario  el usuario");

$COMPARTIDO= $objeto->COMPARTIDO;
valida_parametro_and_die($COMPARTIDO, "Es necesario saber si es publico o no");

$ID_AREA= $objeto->ID_AREA;
valida_parametro_and_die($ID_AREA, "Es necesario el área del reporte");

$COLUMN= $objeto->COLUMN;
valida_parametro_and_die($COLUMN, "Es necesario las columnas del reporte");

$COLUMNS = implode("," ,$COLUMN);
$search = array("|int","|string","|date","|boolean","|float");
$COLUMNS = str_replace($search,"",$COLUMN);


$sql = "SELECT COUNT(R.ID_REPORTE)as total  FROM REPORTES R WHERE R.NOMBRE = '".$NOMBRE."' AND R.ID_USUARIO = ".$ID_USUARIO;
//$sql = "SELECT COUNT(R.ID_REPORTE)as total  FROM REPORTES R WHERE (R.NOMBRE = '".$NOMBRE."' AND R.ID_USUARIO = ".$ID_USUARIO.") OR (R.ID_USUARIO = ".$ID_USUARIO." AND ".count($COLUMNS)." = (SELECT COUNT(RC.ID_REPORTE_COLUMNAS) FROM REPORTE_COLUMNAS RC  WHERE RC.ID_REPORTE = R.ID_REPORTE AND RC.NOMBRE_COLUMNA IN ('".implode("','" ,$COLUMNS)."'))) LIMIT 1";

$count = $database->query($sql)->fetch(PDO::FETCH_ASSOC);

    if($count["total"] == 0)
    {
        $FECHA_CREACION = date("Y-m-d H:i:s");

        $id = $database->insert("REPORTES", [
            "NOMBRE" => $NOMBRE,
            "ID_USUARIO"=>	$ID_USUARIO,
            "COMPARTIDO"=>	$COMPARTIDO,
            "ID_AREA" => $ID_AREA,
            "FECHA_CREACION" =>$FECHA_CREACION
        ]);
        valida_error_medoo_and_die();

            foreach ($COLUMN as $COL)
            {
                $datos = explode("|",$COL);
                $idc = $database->insert("REPORTE_COLUMNAS", [
                    "ID_REPORTE" => $id,
                    "NOMBRE_COLUMNA"=>	$datos[0],
                    "TIPO_DATO"=>	$datos[1]
                ]);
                valida_error_medoo_and_die();
            }



    }
    else
    {
        //valida_parametro_and_die(null, "Ya ese reporte existe, revise el nombre o las columnas");
        valida_parametro_and_die(null, "Ya ese reporte existe, revise el nombre");
    }




$respuesta["resultado"]="ok";

print_r(json_encode($respuesta));
