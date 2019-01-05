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
	//$norma = $database->get("NORMAS", "*", ["ID"=>$tipos_servicio["ID_NORMA"]]);
	$norma = $database->select("COTIZACION_NORMAS", "*", ["ID_COTIZACION"=>$cotizaciones[$i]["ID"]]);
	valida_error_medoo_and_die(); 
	//Info de cursos
    $desc_curso = array();
    if($servicio["ID"] == 3){
        //Buscar el id del producto con el ID de la cotización
        $ID_PRODUCTO = $database->get("PROSPECTO_PRODUCTO", "ID", ["ID_COTIZACION"=>$cotizaciones[$i]["ID"]]);
        $PROSPECTO_PROD_CURSO = $database->get("PROSPECTO_PRODUCTO_CURSO", "*", ["ID_PRODUCTO"=>$ID_PRODUCTO]);
        $modalidad = $PROSPECTO_PROD_CURSO["MODALIDAD"];
        $desc_curso["MODALIDAD"] = "";
        if($modalidad == 'programado'){
            $desc_curso["MODALIDAD"] = "programado";
            $query = "SELECT C.ID_CURSO,C.NOMBRE FROM CURSOS C INNER JOIN CURSOS_PROGRAMADOS CP ON C.ID_CURSO = CP.ID_CURSO WHERE CP.ID =" . $PROSPECTO_PROD_CURSO["ID_CURSO_PROGRAMADO"];
            $NOMBRE_CURSO = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
            $desc_curso["NOMBRE_CURSO"] = $NOMBRE_CURSO[0]["NOMBRE"];
			$desc_curso["ID_CURSO_PROGRAMADO"] = $PROSPECTO_PROD_CURSO["ID_CURSO_PROGRAMADO"];
			$desc_curso["ID_CURSO"] = $NOMBRE_CURSO[0]["ID_CURSO"];
        } else if($modalidad == 'insitu'){
            $desc_curso["MODALIDAD"] = "insitu";
			$data = $database->get("CURSOS", ["ID_CURSO","NOMBRE"], ["ID_CURSO"=>$PROSPECTO_PROD_CURSO["ID_CURSO"]]);
			$desc_curso["NOMBRE_CURSO"] = $data["NOMBRE"];
			$desc_curso["ID_CURSO"] = $data["ID_CURSO"];
        }
    }     
	$desc_tarifa = $database->get("TARIFA_COTIZACION", "*", ["ID"=>$cotizaciones[$i]["TARIFA"]]);
	valida_error_medoo_and_die();
	$estado = $database->get("PROSPECTO_ESTATUS_SEGUIMIENTO", "*", ["ID"=>$cotizaciones[$i]["ESTADO_COTIZACION"]]);
	valida_error_medoo_and_die(); 
	$cotizaciones[$i]["SERVICIO"] = $servicio;
	$cotizaciones[$i]["TIPOS_SERVICIO"] = $tipos_servicio;
	$cotizaciones[$i]["NORMA"] = $norma;
	$cotizaciones[$i]["ESTADO"] = $estado;
	$cotizaciones[$i]["VALOR_TARIFA"] = $desc_tarifa['TARIFA'];
	
	//Si es CIFA y está firmado buscar el url para la carga de participantes
	if($servicio["ID"] == 3 && $estado["ESTATUS_SEGUIMIENTO"] == "Firmado"){
		$url = $database->get("COTIZACION_DETALLES", "VALOR", [
			"AND" => [
				"ID_COTIZACION"=>$cotizaciones[$i]["ID"],
				"DETALLE" => "URL_PARTICIPANTES"
			]			
		]);
		$desc_curso["URL_PARTICIPANTES"] = $url;
	}
	$cotizaciones[$i]["CURSO"] = $desc_curso;

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
