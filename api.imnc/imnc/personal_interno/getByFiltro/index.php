<?php 
// error_reporting(E_ALL);
// ini_set("display_errors",1);

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
		$mailerror->send("certificando", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx");
		die();
	}
}

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

$NO = $objeto->NO;

$EDAD = $objeto->EDAD;
$EDAD_CONTAINS = $objeto->EDAD_CONTAINS; // contains = 1, starts with = 0

$CURP= $objeto->CURP;
$CURP_CONTAINS = $objeto->CURP_CONTAINS; // contains = 1, starts with = 0

$NOMBRE = $objeto->NOMBRE;
$NOMBRE_CONTAINS = $objeto->NOMBRE_CONTAINS; // contains = 1, starts with = 0

$APELLIDO_PATERNO = $objeto->APELLIDO_PATERNO;
$APELLIDO_PATERNO_CONTAINS = $objeto->APELLIDO_PATERNO_CONTAINS; // contains = 1, starts with = 0

$SEXO = $objeto->SEXO;

$SEGURO = $objeto->SEGURO;
$SEGURO_CONTAINS = $objeto->SEGURO_CONTAINS; // contains = 1, starts with = 0

$ESTADO_CIVIL = $objeto->ESTADO_CIVIL;

$ALTA_BAJA = $objeto->ALTA_BAJA;


$WHERE = "";
$C = "";

if($NO != "")
{
    $WHERE .= ($WHERE!=""?" AND ":"")." NO_EMPLEADO = '".$NO."'";
}

if($EDAD != "")
{
    $C = "";

        if($EDAD_CONTAINS == 'EXACTO')//menores
        {
            $C = " = ";
        }
        if($EDAD_CONTAINS == '0')//menores
        {
            $C = " < ";
        }

        if($EDAD_CONTAINS == '1')//mayores
        {
            $C = " > ";
        }



    $WHERE .= ($WHERE!=""?" AND ":""). " TIMESTAMPDIFF(YEAR,FECHA_NACIMIENTO,CURDATE())".$C.$EDAD;
}

if ($CURP != "") {
	$C = "";
	if ($CURP_CONTAINS) {
        $C = "%";
	}
    $WHERE .= ($WHERE!=""?" AND ":"")." CURP LIKE " . $database->quote($C.$CURP."%");
}


if ($NOMBRE != "") {
    $C = "";
    if ($NOMBRE_CONTAINS) {
        $C = "%";
    }
    $WHERE .= ($WHERE!=""?" AND ":"")." NOMBRE LIKE " . $database->quote($C.$NOMBRE."%");
}

if ($APELLIDO_PATERNO != "") {
    $C = "";
    if ($APELLIDO_PATERNO_CONTAINS) {
        $C = "%";
    }
    $WHERE .= ($WHERE!=""?" AND ":"")." APELLIDO_PATERNO LIKE " . $database->quote($C.$APELLIDO_PATERNO."%");
}

if($SEXO != "" && $SEXO!="TODOS")
{
    $WHERE .= ($WHERE!=""?" AND ":"")." SEXO = '".$SEXO."'";
}

if ($SEGURO != "") {
    $C = "";
    if ($SEGURO_CONTAINS) {
        $C = "%";
    }
    $WHERE .= ($WHERE!=""?" AND ":"")." NO_SEGURO_SOCIAL LIKE " . $database->quote($C.$SEGURO."%");
}

if($ESTADO_CIVIL != "" && $ESTADO_CIVIL!="TODOS")
{
    $WHERE .= ($WHERE!=""?" AND ":"")." ESTADO_CIVIL = '".$ESTADO_CIVIL."'";
}

if($ALTA_BAJA != "" && $ALTA_BAJA!="TODOS")
{
    $WHERE .= ($WHERE!=""?" AND ":"")." ISACTIVO = '".$ALTA_BAJA."'";
}


$query = "SELECT * FROM PERSONAL_INTERNO ";
if($WHERE!="")
{
    $query .= " WHERE ".$WHERE;
}

$personal_interno = $database->query($query)->fetchAll();
valida_error_medoo_and_die();

//print_r($strQuery);
print_r(json_encode($personal_interno));


//-------- FIN --------------
?>