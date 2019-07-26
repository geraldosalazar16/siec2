<?php 
include  '../../common/conn-apiserver.php';
include  '../../common/conn-medoo.php'; 
include  '../../common/conn-sendgrid.php'; 

function imprime_error_and_die($mensaje){
	$respuesta['resultado'] = 'error';
	$respuesta['mensaje'] = $mensaje;
	print_r(json_encode($respuesta));
	die();
}


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

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$FLAG = $objeto->FLAG;

$ID_PERSONAL_TECNICO = $objeto->ID_PERSONAL_TECNICO;
valida_parametro_and_die($ID_PERSONAL_TECNICO, "Falta ID_PERSONAL_TECNICO");


$ID_TIPO_SERVICIO = $objeto->ID_TIPO_SERVICIO;
valida_parametro_and_die($ID_TIPO_SERVICIO, "Es necesario seleccionar un tipo de servicio");
$NORMA ="";
if($FLAG=="NORMAL")
{
    $NORMA = $objeto->ID_NORMA;
    if(sizeof($NORMA) == 0){
        imprime_error_and_die("Es necesario seleccionar una norma");
}

}

$ID_ROL = $objeto->ID_ROL;
valida_parametro_and_die($ID_ROL, "Es necesario seleccionar un rol");
$REGISTRO = $objeto->REGISTRO;
valida_parametro_and_die($REGISTRO, "Es necesario capturar un registro");

$FECHA_INICIO = $objeto->FECHA_INICIO;
valida_parametro_and_die($FECHA_INICIO, "Es necesario capturar una fecha de inicio");
if (strlen($FECHA_INICIO) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de inicio");
}
$anhio = intval(substr($FECHA_INICIO,0,4));
$mes = intval(substr($FECHA_INICIO,4,2));
$dia = intval(substr($FECHA_INICIO,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de inicio no es válida");
}

$FECHA_FIN = $objeto->FECHA_FIN;
valida_parametro_and_die($FECHA_FIN, "Es necesario capturar una fecha de fin");
if (strlen($FECHA_FIN) != 8) {
	imprime_error_and_die("Verifica el formato de la fecha de fin");
}
$anhio = intval(substr($FECHA_FIN,0,4));
$mes = intval(substr($FECHA_FIN,4,2));
$dia = intval(substr($FECHA_FIN,6,2));
if (!checkdate($mes , $dia, $anhio)){
	imprime_error_and_die("La fecha de fin no es válida");
}
if ($FECHA_INICIO > $FECHA_FIN) {
	imprime_error_and_die("La fecha de inicio no puede ser después de la fecha final");
}

$ID_USUARIO_CREACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_CREACION,"Falta ID de USUARIO");

$FECHA_CREACION = date("Ymd");
$HORA_CREACION = date("His");

//Validación de que en la misma norma no se interlace la fechas

$personal_tecnico_fecha_fin=array();
$personal_tecnico_fecha_fin= $database->query("SELECT MAX(FECHA_FIN) AS MAXIMO FROM PERSONAL_TECNICO_CALIFICACIONES
WHERE ID_PERSONAL_TECNICO=".$database->quote($ID_PERSONAL_TECNICO )."
AND ID_ROL = ".$database->quote($ID_ROL)."
AND ID_TIPO_SERVICIO = ".$database->quote($ID_TIPO_SERVICIO).";")->fetchAll(PDO::FETCH_ASSOC);

if($database->count("PERSONAL_TECNICO_CALIFICACIONES","*",["AND"=>["ID_PERSONAL_TECNICO"=>$ID_PERSONAL_TECNICO,"ID_ROL"=>$ID_ROL,"ID_TIPO_SERVICIO"=>$ID_TIPO_SERVICIO]])>0){

	
	imprime_error_and_die("Esta calificacion ya existe. Para modificar debe darle editar calificacion.");
				
	
}else{

	$IdPTC = $database->insert("PERSONAL_TECNICO_CALIFICACIONES", [
		"ID_PERSONAL_TECNICO" => $ID_PERSONAL_TECNICO,
		"ID_ROL" => $ID_ROL,
		"ID_TIPO_SERVICIO" => $ID_TIPO_SERVICIO,
		"REGISTRO" => $REGISTRO,
		"FECHA_INICIO" => $FECHA_INICIO,
		"FECHA_FIN" => $FECHA_FIN,
		"FECHA_CREACION" => $FECHA_CREACION,
		"HORA_CREACION" => $HORA_CREACION,
		"ID_USUARIO_CREACION" => $ID_USUARIO_CREACION
		]);
		valida_error_medoo_and_die();



///////////////////////////////////////////////////////////////////////////////
//				PARA INSERTAR LAS RELACIONES NORMAS CALIFICACIONES

        $id2 = $database->delete("CALIFICACIONES_NORMAS", ["ID_CALIFICACION" => $IdPTC]);
    if($FLAG=="NORMAL") {
        for ($i = 0; $i < count($NORMA); $i++) {
            $ID_NORMA = $NORMA[$i]->ID_NORMA;

            $id1 = $database->insert("CALIFICACIONES_NORMAS", [

                "ID_NORMA" => $ID_NORMA,
                "ID_CALIFICACION" => $IdPTC,
                "FECHA_CREACION" => $FECHA_CREACION,
                "ID_USUARIO_CREACION" => $ID_USUARIO_CREACION

            ]);
            valida_error_medoo_and_die();

        }
    }

$respuesta['resultado']="ok";
$respuesta['id']=$IdPTC;
print_r(json_encode($respuesta));
}

//-------- FIN --------------
?>
