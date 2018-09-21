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

$NOMBRE = $objeto->NOMBRE; //CLIENTES
$NOMBRE_CONTAINS = $objeto->NOMBRE_CONTAINS; // contains = 1, starts with = 0

$RFC = $objeto->RFC; //CLIENTES
$RFC_CONTAINS = $objeto->RFC_CONTAINS; // contains = 1, starts with = 0

$ENTIDAD_FEDERATIVA = $objeto->ENTIDAD_FEDERATIVA; //CLIENTES_DOMICILIOS
$ENTIDAD_FEDERATIVA_CONTAINS = $objeto->ENTIDAD_FEDERATIVA_CONTAINS; // contains = 1, starts with = 0

$MUNICIPIO = $objeto->MUNICIPIO; //CLIENTES_DOMICILIOS
$MUNICIPIO_CONTAINS = $objeto->MUNICIPIO_CONTAINS; // contains = 1, starts with = 0

$CP = $objeto->CP; //CLIENTES_DOMICILIOS
$CP_CONTAINS = $objeto->CP_CONTAINS; // contains = 1, starts with = 0

$NOMBRE_CONTACTO = $objeto->NOMBRE_CONTACTO; //CLIENTES_CONTACTOS
$NOMBRE_CONTACTO_CONTAINS = $objeto->NOMBRE_CONTACTO_CONTAINS; // contains = 1, starts with = 0


$fromCLIENTES_DOMICILIOS = "";
$fromCLIENTES_CONTACTOS = "";

$whereCLIENTES_DOMICILIOS = "";
$whereCLIENTES_CONTACTOS = "";

$whereNOMBRE = "";
if ($NOMBRE != "") {
	$likeNOMBRE = "";
	if ($NOMBRE_CONTAINS) {
		$likeNOMBRE = "%";
	}
	$whereNOMBRE = " AND CLIENTES.NOMBRE LIKE " . $database->quote($likeNOMBRE . $NOMBRE."%");
}

$whereRFC = "";
if ($RFC != "") {
	$likeRFC = "";
	if ($RFC_CONTAINS) {
		$likeRFC = "%";
	}
	$whereRFC = " AND CLIENTES.RFC LIKE " . $database->quote($likeRFC . $RFC."%");
}


$whereENTIDAD_FEDERATIVA = "";
if ($ENTIDAD_FEDERATIVA != "") {
	$likeENTIDAD_FEDERATIVA = "";
	if ($ENTIDAD_FEDERATIVA_CONTAINS) {
		$likeENTIDAD_FEDERATIVA = "%";
	}
	$whereENTIDAD_FEDERATIVA = " AND CLIENTES_DOMICILIOS.ENTIDAD_FEDERATIVA LIKE " . $database->quote($likeENTIDAD_FEDERATIVA . $ENTIDAD_FEDERATIVA."%");

	$fromCLIENTES_DOMICILIOS = ", CLIENTES_DOMICILIOS ";
	$whereCLIENTES_DOMICILIOS = " AND CLIENTES_DOMICILIOS.ID_CLIENTE = CLIENTES.ID ";
}

$whereMUNICIPIO = "";
if ($MUNICIPIO != "") {
	$likeMUNICIPIO = "";
	if ($MUNICIPIO_CONTAINS) {
		$likeMUNICIPIO = "%";
	}
	$whereMUNICIPIO = " AND CLIENTES_DOMICILIOS.DELEGACION_MUNICIPIO LIKE " . $database->quote($likeMUNICIPIO . $MUNICIPIO."%");
	
	$fromCLIENTES_DOMICILIOS = ", CLIENTES_DOMICILIOS ";
	$whereCLIENTES_DOMICILIOS = " AND CLIENTES_DOMICILIOS.ID_CLIENTE = CLIENTES.ID ";
}

$whereCP = "";
if ($CP != "") {
	$likeCP = "";
	if ($CP_CONTAINS) {
		$likeCP = "%";
	}
	$whereCP = " AND CLIENTES_DOMICILIOS.CP LIKE " . $database->quote($likeCP . $CP."%");
	
	$fromCLIENTES_DOMICILIOS = ", CLIENTES_DOMICILIOS ";
	$whereCLIENTES_DOMICILIOS = " AND CLIENTES_DOMICILIOS.ID_CLIENTE = CLIENTES.ID ";
}

$whereNOMBRE_CONTACTO = "";
if ($NOMBRE_CONTACTO != "") {
	$likeNOMBRE_CONTACTO = "";
	if ($NOMBRE_CONTACTO_CONTAINS) {
		$likeNOMBRE_CONTACTO = "%";
	}
	$whereNOMBRE_CONTACTO = " AND CLIENTES_CONTACTOS.NOMBRE_CONTACTO LIKE " . $database->quote($likeNOMBRE_CONTACTO.$NOMBRE_CONTACTO."%");

	$fromCLIENTES_DOMICILIOS = ", CLIENTES_DOMICILIOS ";
	$whereCLIENTES_DOMICILIOS = " AND CLIENTES_DOMICILIOS.ID_CLIENTE = CLIENTES.ID ";

	$fromCLIENTES_CONTACTOS = ", CLIENTES_CONTACTOS ";
	$whereCLIENTES_CONTACTOS = " AND CLIENTES_CONTACTOS.ID_CLIENTE_DOMICILIO = CLIENTES_DOMICILIOS.ID ";

}


$strQuery = <<<EOT
SELECT DISTINCT CLIENTES.ID, CLIENTES.ID_TIPO_PERSONA, CLIENTES.ID_TIPO_ENTIDAD, 
	CLIENTES.ID_CLIENTE_FACTURARIO, CLIENTES.NOMBRE, CLIENTES.RFC, CLIENTES.ES_FACTURARIO, CLIENTES.TIENE_FACTURARIO, 
	CLIENTES.IMAGEN_BASE64, CLIENTES.CLIENTE_FACTURARIO, CLIENTES.RFC_FACTURARIO 
FROM CLIENTES $fromCLIENTES_DOMICILIOS $fromCLIENTES_CONTACTOS
WHERE 1 
	$whereCLIENTES_DOMICILIOS  
	$whereCLIENTES_CONTACTOS 
	$whereNOMBRE 
	$whereRFC 
	$whereENTIDAD_FEDERATIVA 
	$whereMUNICIPIO 
	$whereCP 
	$whereNOMBRE_CONTACTO 
ORDER BY CLIENTES.NOMBRE
EOT;
//print_r($strQuery);

$arreglo_clientes = $database->query($strQuery);
valida_error_medoo_and_die();
$arreglo_clientes = $arreglo_clientes->fetchAll();

for ($i=0; $i < count($arreglo_clientes) ; $i++) { 
	if (!is_null($arreglo_clientes[$i]["ID_CLIENTE_FACTURARIO"])) {
		$nombre_facturatario = $database->get("CLIENTES", "NOMBRE", ["ID"=>$arreglo_clientes[$i]["ID_CLIENTE_FACTURARIO"]]);
		valida_error_medoo_and_die();

		$arreglo_clientes[$i]["NOMBRE_FACTURATARIO"] = $nombre_facturatario;
	}
	$tipo_persona = $database->get("TIPOS_PERSONA", "TIPO", ["ID"=>$arreglo_clientes[$i]["ID_TIPO_PERSONA"]]);
	valida_error_medoo_and_die();
	$arreglo_clientes[$i]["TIPO_PERSONA"] = $tipo_persona;

	$tipo_entidad = $database->get("TIPOS_ENTIDAD", "TIPO", ["ID"=>$arreglo_clientes[$i]["ID_TIPO_ENTIDAD"]]);
	valida_error_medoo_and_die();
	$arreglo_clientes[$i]["TIPO_ENTIDAD"] = $tipo_entidad;
}



print_r(json_encode($arreglo_clientes));


?>