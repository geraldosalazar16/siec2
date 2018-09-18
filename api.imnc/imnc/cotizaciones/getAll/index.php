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
		$respuesta["mensaje"]="Error al ejecutar script:" . $database->error()[2]; 
		print_r(json_encode($respuesta)); 
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "leovardo.quintero@dhttecno.com"); 
		die(); 
	} 
} 

$respuesta=array(); 
$query = "SELECT * FROM TABLA_ENTIDADES,COTIZACIONES WHERE ID_PROSPECTO = ID_VISTA AND BANDERA_VISTA = BANDERA";
$cotizaciones = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 

valida_error_medoo_and_die(); 

for ($i=0; $i < count($cotizaciones); $i++) { 
	$servicio = $database->get("SERVICIOS", "*", ["ID"=>$cotizaciones[$i]["ID_SERVICIO"]]);
	valida_error_medoo_and_die(); 
	$tipos_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$cotizaciones[$i]["ID_TIPO_SERVICIO"]]);
	valida_error_medoo_and_die(); 
	$norma = $database->get("NORMAS", "*", ["ID"=>$tipos_servicio["ID_NORMA"]]);
	valida_error_medoo_and_die(); 
	$estado = $database->get("PROSPECTO_ESTATUS_SEGUIMIENTO", "*", ["ID"=>$cotizaciones[$i]["ESTADO_COTIZACION"]]);
	valida_error_medoo_and_die(); 
	$cotizaciones[$i]["SERVICIO"] = $servicio;
	$cotizaciones[$i]["TIPOS_SERVICIO"] = $tipos_servicio;
	$cotizaciones[$i]["NORMA"] = $norma;
	$cotizaciones[$i]["ESTADO"] = $estado;

	$CONSECUTIVO = str_pad("".$cotizaciones[$i]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
	$FOLIO = $cotizaciones[$i]["FOLIO_INICIALES"].$cotizaciones[$i]["FOLIO_SERVICIO"].$CONSECUTIVO
	.$cotizaciones[$i]["FOLIO_MES"].$cotizaciones[$i]["FOLIO_YEAR"];
	if( !is_null($cotizaciones[$i]["FOLIO_UPDATE"]) && $cotizaciones[$i]["FOLIO_UPDATE"] != ""){
		$FOLIO .= "-".$cotizaciones[$i]["FOLIO_UPDATE"];
	}
	$cotizaciones[$i]["FOLIO"] = $FOLIO;
}

print_r(json_encode($cotizaciones)); 

?> 
