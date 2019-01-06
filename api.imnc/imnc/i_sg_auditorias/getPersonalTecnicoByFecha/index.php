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
		die(); 
	} 
} 

$respuesta=array(); 
$json = file_get_contents("php://input"); 
$objeto = json_decode($json);  

$TIPO_AUDITORIA = $objeto->TIPO_AUDITORIA;
valida_parametro_and_die($TIPO_AUDITORIA, "Falta el TIPO_AUDITORIA");

$CICLO = $objeto->CICLO; 
valida_parametro_and_die($CICLO, "Falta el CICLO");

$ID_SCE = $objeto->ID_SCE;
valida_parametro_and_die($ID_SCE, "Falta el ID_SCE");

$FECHA = $objeto->FECHA; 
valida_parametro_and_die($FECHA, "Falta la FECHA");


$query = "SELECT 
PT.NOMBRE,
PT.APELLIDO_PATERNO
FROM 
I_SG_AUDITORIA_GRUPOS AG
INNER JOIN I_SG_AUDITORIA_GRUPO_FECHAS AGF
ON AG.ID_SERVICIO_CLIENTE_ETAPA = AGF.ID_SERVICIO_CLIENTE_ETAPA
AND AG.TIPO_AUDITORIA = AGF.TIPO_AUDITORIA
AND AG.CICLO = AGF.CICLO
INNER JOIN PERSONAL_TECNICO_CALIFICACIONES PTC
ON AG.ID_PERSONAL_TECNICO_CALIF = PTC.ID
INNER JOIN PERSONAL_TECNICO PT 
ON PT.ID = PTC.ID_PERSONAL_TECNICO
WHERE AG.ID_SERVICIO_CLIENTE_ETAPA = " . $ID_SCE .
" AND AG.TIPO_AUDITORIA = " . $TIPO_AUDITORIA . 
" AND AG.CICLO = " . $CICLO . 
" AND AGF.FECHA = '" . $FECHA . "'";

$NOMBRES = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die(); 

$respuesta["resultado"]="ok";
$respuesta["nombres"] = $NOMBRES;
print_r(json_encode($respuesta)); 
?> 
