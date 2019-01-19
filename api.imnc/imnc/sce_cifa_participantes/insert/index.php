<?php  

include  '../../common/conn-apiserver.php';  
include  '../../common/conn-medoo.php';  
include  '../../common/conn-sendgrid.php';  

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

$ID_SCE = $objeto->ID_SERVICIO_CLIENTE_ETAPA;
valida_parametro_and_die($ID_SCE, "Es necesario introducir el ID SCE");

$RAZON_ENTIDAD = $objeto->RAZON_ENTIDAD;
valida_parametro_and_die($RAZON_ENTIDAD, "Es necesario introducir una Razon Social");

$EMAIL = $objeto->EMAIL;
valida_parametro_and_die($EMAIL, "Es necesario introducir un Correo Electrónico");

$TELEFONO	= $objeto->TELEFONO;
valida_parametro_and_die($TELEFONO, "Es necesario introducir un número de telefono");

$CURP	= $objeto->CURP;
valida_parametro_and_die($CURP, "Es introducir el CURP del participante");

$RFC	= $objeto->RFC;
valida_parametro_and_die($RFC, "Es introducir el RFC de su organización");

$ESTADO	= $objeto->ESTADO;
valida_parametro_and_die($ESTADO, "Es introducir el Estado del que nos visita");

$EJECUTIVO	= $objeto->EJECUTIVO;
valida_parametro_and_die($EJECUTIVO, "Es introducir el Nombre del ejecutivo comercial que le atendió");

$ID_USUARIO_MODIFICACION = $objeto->ID_USUARIO;
valida_parametro_and_die($ID_USUARIO_MODIFICACION,"Falta ID de USUARIO");

$ID_CURSO = $objeto->ID_CURSO;
valida_parametro_and_die($ID_CURSO,"Falta ID del Curso");

$CANTIDAD_PARTICIPANTES = $objeto->CANTIDAD_PARTICIPANTES;
valida_parametro_and_die($CANTIDAD_PARTICIPANTES,"Falta cantidad de participantes");

$count = $database->count("SCE_PARTICIPANTES","*",["AND"=>["ID_SCE" => $ID_SCE,"ID_CURSO"=>$ID_CURSO]]);

if($count < $CANTIDAD_PARTICIPANTES)
{
    $id_participante = $database->insert("PARTICIPANTES", [
        "RAZON_ENTIDAD" => $RAZON_ENTIDAD,
        "EMAIL"=>	$EMAIL,
        "TELEFONO" => $TELEFONO,
        "CURP" => $CURP,
        "RFC"=>$RFC,
        "ID_ESTADO"=>$ESTADO,
        "EJECUTIVO"=>$EJECUTIVO
    ]);
    valida_error_medoo_and_die();

    if($id_participante!=0)
    {
        $id = $database->insert("SCE_PARTICIPANTES", [
            "ID_SCE" => $ID_SCE,
            "ID_PARTICIPANTE"=>	$id_participante,
            "ID_CURSO"=>$ID_CURSO

        ]);
        valida_error_medoo_and_die();
        $estado_actual = "Razón Social: ".$RAZON_ENTIDAD.", Correo:".$EMAIL.", Teléfono: ".$TELEFONO.", CURP: ".$CURP.", RFC: ".$RFC.", Estado:".$ESTADO.", le atendió:".$EJECUTIVO;
        $id2=$database->insert("SERVICIO_CLIENTE_ETAPA_HISTORICO", [
            "ID_SERVICIO_CONTRATADO" => $ID_SCE,
            "MODIFICACION" => "NUEVO PARTICIPANTE",
            "ESTADO_ANTERIOR"=>	"",
            "ESTADO_ACTUAL"=>$estado_actual,
            "USUARIO" => $ID_USUARIO_MODIFICACION,
            "FECHA_USUARIO" => date("Ymd"),
            "FECHA_MODIFICACION" => date("Ymd")]);
    }
}else{
    valida_parametro_and_die(null,"No se puede agregar mas participantes a este curso.");
}


$respuesta["resultado"]="ok"; 

print_r(json_encode($respuesta)); 
?> 
