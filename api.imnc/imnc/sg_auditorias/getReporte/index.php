<?php 
 	include  '../../ex_common/query.php'; 
	$correo = "jesus.popocatl@dhttecno.com";
	
	header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=clientes.csv");

$respuesta=array();
$json = file_get_contents('php://input'); //Obtiene lo que se envía vía POST
$objeto = json_decode($json); // Lo transforma de JSON a un objeto de PHP

if(isset($objeto->SISTEMA_GESTION)){
	$SISTEMA_GESTION = $objeto->SISTEMA_GESTION;
}else{
	$SISTEMA_GESTION = "";
}
if(isset($objeto->TRAMITE)){
	$TRAMITE = $objeto->TRAMITE;
}else{
	$TRAMITE = "";
}

if(isset($objeto->SECTOR)){
	$SECTOR = $objeto->SECTOR;
}else{
	$SECTOR = "";
}

if(isset($objeto->NOMBRE)){
	$NOMBRE = $objeto->NOMBRE;
}else{
	$NOMBRE = "";
}
if(isset($objeto->FECHA_INICIO)){
	$FECHA_INICIO = $objeto->FECHA_INICIO;
}else{
	$FECHA_INICIO = "";
}
if(isset($objeto->FECHA_FIN)){
	$FECHA_FIN = $objeto->FECHA_FIN;
}else{
	$FECHA_FIN = "";
}

$lista_where = [];

$lista_join = [
	"[>]SG_TIPOS_SERVICIO" => ["SG_AUDITORIAS.ID_SG_TIPO_SERVICIO" => "ID"],
	"[>]SERVICIO_CLIENTE_ETAPA" => ["SG_TIPOS_SERVICIO.ID_SERVICIO_CLIENTE_ETAPA" => "ID"],
	"[>]CLIENTES" => ["SERVICIO_CLIENTE_ETAPA.ID_CLIENTE" => "ID"],
	"[>]ETAPAS_PROCESO" => ["SERVICIO_CLIENTE_ETAPA.ID_ETAPA_PROCESO" => "ID_ETAPA"],
	"[>]SERVICIOS" => ["SERVICIO_CLIENTE_ETAPA.ID_SERVICIO" => "ID"],
	"[>]TIPOS_SERVICIO" => ["SG_TIPOS_SERVICIO.ID_TIPO_SERVICIO" => "ID"],
	"[>]SG_SECTORES" => ["SG_TIPOS_SERVICIO.ID" => "ID_SG_TIPO_SERVICIO"],
	"[>]SECTORES" => ["SG_SECTORES.ID_SECTOR" => "ID_SECTOR"],

];

$lista_campos = [
	"SG_AUDITORIAS.ID",
	"SERVICIOS.NOMBRE(NOMBRE_SERVICIO)",
	"TIPOS_SERVICIO.NOMBRE(NOMBRE_TIPOS_SERVICIO)",
	"CLIENTES.NOMBRE(NOMBRE_CLIENTE)",
	"ETAPAS_PROCESO.ETAPA(TRAMITE)",
	"SERVICIO_CLIENTE_ETAPA.SG_INTEGRAL",
	"SG_AUDITORIAS.DURACION_DIAS",
	"SECTORES.NOMBRE(NOMBRE_SECTOR)",
];

if(!empty($FECHA_INICIO)){
	$lista_where["SG_TIPOS_SERVICIO.FECHA_CREACION[>=]"] = $FECHA_INICIO;
}
if(!empty($FECHA_FIN)){
	$lista_where["SG_TIPOS_SERVICIO.FECHA_CREACION[<]"] = $FECHA_FIN;
}
if(!empty($SISTEMA_GESTION)){
	$lista_where["TIPOS_SERVICIO.NOMBRE[~]"] = $SISTEMA_GESTION;
}
if(!empty($TRAMITE)){
	$lista_where["ETAPAS_PROCESO.NOMBRE[~]"] = $TRAMITE;
}
if(!empty($SECTOR)){
	$lista_where["SECTORES.NOMBRE"] = $SECTOR;
}
if(!empty($NOMBRE)){
	$lista_where["CLIENTES.NOMBRE"] = $NOMBRE;
}
if(count($lista_where) > 1){
	$lista_where = ["AND" => $lista_where];
}
$respuesta = $database->select("SG_AUDITORIAS", $lista_join, $lista_campos, $lista_where); 
valida_error_medoo_and_die("SG_AUDITORIAS" ,$correo );

for ($i=0; $i < count($respuesta); $i++) { 
	$ex_tec = $database->count("SG_AUDITORIA_GRUPOS", ["AND" => ["ID_SG_AUDITORIA" => $respuesta[$i]["ID"], "ID_ROL" => "ExTec" ] ]);
	if($ex_tec >= 1){
		$respuesta[$i]["EX_TEC"] = "SI";
	} 
	else{
		$respuesta[$i]["EX_TEC"] = "NO";
	}
	$tecl_join = [
		"[>]PERSONAL_TECNICO_CALIFICACIONES" => ["SG_AUDITORIA_GRUPOS.ID_PERSONAL_TECNICO_CALIF" => "ID"],
		"[>]PERSONAL_TECNICO" => ["PERSONAL_TECNICO_CALIFICACIONES.ID_PERSONAL_TECNICO" => "ID"], 	
	];
	$tecl = $database->get("SG_AUDITORIA_GRUPOS", $tecl_join , "INICIALES", 
		["AND" => ["ID_SG_AUDITORIA" => $respuesta[$i]["ID"], "SG_AUDITORIA_GRUPOS.ID_ROL" => "TECL" ] ]);
	$respuesta[$i]["TECL"] = $tecl;
}

$csv = "";
for($i = 0 ; $i < sizeof($respuesta); $i++){
	foreach ($respuesta[$i] as $key => $item) {
		$csv .= $item.",";
	}
	$csv .= "\r\n";
}

print_r($csv);
//print_r(json_encode($respuesta)); 

?>