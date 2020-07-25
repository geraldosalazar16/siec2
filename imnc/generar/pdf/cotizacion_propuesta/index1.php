<?php 
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

/*/////////////////////////////////////////////////////////////////////////*/
require_once('../../../phplibs/libPDF/tcpdf.php');
require_once ('../../../phplibs/fpdf181/fpdf.php');
require_once('../../../phplibs/FPDI-2.0.2/src/autoload.php');
include  '../../../../api.imnc/imnc/common/conn-apiserver.php'; 
include  '../../../../api.imnc/imnc/common/conn-medoo.php'; 
include  '../../../../api.imnc/imnc/common/conn-sendgrid.php';
require_once('../../../diff/direccion.php');
//require_once('../../../common/apiserver.php'); //$global_apiserver
//require_once('../../../diff/selector.php'); //$global_diffname
//require_once('../../../diff/'.$global_diffname.'/strings.php'); 

/*/////////////////////////////////////////////////////////////////////////*/
function mes_esp($string){
	list($day,$month,$year) = explode('/',$string);
	switch($month){
		case 1:
			$mes = "Enero";
			break;
		case 2:
			$mes = "Febrero";
			break;
		case 3:
			$mes = "Marzo";
			break;
		case 4:
			$mes = "Abril";
			break;
		case 5:
			$mes = "Mayo";
			break;
		case 6:
			$mes = "Junio";
			break;
		case 7:
			$mes = "Julio";
			break;
		case 8:
			$mes = "Agosto";
			break;		
		case 9:
			$mes = "Septiembre";
			break;
		case 10:
			$mes = "Octubre";
			break;
		case 11:
			$mes = "Noviembre";
			break;
		case 12:
			$mes = "Diciembre";
			break;	
	}
	$str = $day."/".$mes."/".$year;
	return $str;
}

//$datos = json_decode($_REQUEST["datos"]); 

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
		$mailerror->send("COTIZACIONES", getcwd(), $database->error()[2], $database->last_query(), "polo@codeart.mx"); 
		die(); 
	} 
}


$id_prospecto = $_REQUEST["id_prospecto"]; 
valida_parametro_and_die($id_prospecto,"Es necesario seleccionar un prospecto");
$id_producto = $_REQUEST["id_producto"];
$id_contacto =  $_REQUEST["id_contacto"];
$id_domicilio =  $_REQUEST["id_domicilio"];
$id_cotizacion	=	$_REQUEST["id_cotizacion"];
$datos = json_decode($_REQUEST["tramites"]); 
$datos1 = json_decode($_REQUEST["descripcion"]); 
//$datos2 = json_decode($_REQUEST["tarifa"]); 
/*/////////////////////////////////////////////////////////////////////////*/
//Para obtener datos de la base de datos
/*/////////////////////////////////////////////////////////////////////////*/

/*===========================================================================*/
/*===========================================================================*/
/*===========================================================================*/
$cotizacion = $database->get("COTIZACIONES", "*", ["ID"=>$id_cotizacion]); 
$query = "SELECT * FROM TABLA_ENTIDADES,COTIZACIONES WHERE ID_PROSPECTO = ID_VISTA AND BANDERA_VISTA = BANDERA AND ID =".$database->quote($id_cotizacion);
$cotizacion = $database->query($query)->fetchAll(PDO::FETCH_ASSOC); 
valida_error_medoo_and_die(); 


$complejidad = $cotizacion[0]["COMPLEJIDAD"]; 
$complejidades_validas = array("alta", "media", "baja", "limitada");
if (!in_array($complejidad, $complejidades_validas)) {
	$complejidad = "media";
}
$complejidad = "_" . strtoupper($complejidad);

if($cotizacion[0]["BANDERA"] == 0){
	$id_cliente = $database->get("PROSPECTO", "ID_CLIENTE", ["ID"=>$cotizacion[0]["ID_PROSPECTO"]]);
	$cliente = $database->get("CLIENTES", "*", ["ID"=>$id_cliente]);
	$cotizacion[0]["CLIENTE"] = $cliente;
}


$servicio = $database->get("SERVICIOS", "*", ["ID"=>$cotizacion[0]["ID_SERVICIO"]]);
valida_error_medoo_and_die(); 
$tipos_servicio = $database->get("TIPOS_SERVICIO", "*", ["ID"=>$cotizacion[0]["ID_TIPO_SERVICIO"]]);
valida_error_medoo_and_die(); 
//AQUI TRABAJO LO DE LAS NORMAS POR SI TIENE CARGADAS MAS DE UNA
$normas = $database->select("COTIZACION_NORMAS", "*", ["ID_COTIZACION"=>$id_cotizacion]);
valida_error_medoo_and_die();
//aqui concateno las normas
$norma2 = $normas[0]['ID_NORMA'];//$nombrearea[0]["PNOMBRE"];//$norma=$datos->norma;;//
for($z=1;$z<count($normas);$z++){
	$norma2 .= ";".$normas[$z]['ID_NORMA'];
}
$estado = $database->get("PROSPECTO_ESTATUS_SEGUIMIENTO", "*", ["ID"=>$cotizacion[0]["ESTADO_COTIZACION"]]);
valida_error_medoo_and_die(); 
$campos_tramite = [
	"COTIZACIONES_TRAMITES.ID",
	"COTIZACIONES_TRAMITES.VIATICOS",
	"COTIZACIONES_TRAMITES.DESCUENTO",
	"COTIZACIONES_TRAMITES.ID_ETAPA_PROCESO",
	"COTIZACIONES_TRAMITES.FACTOR_INTEGRACION",
	"COTIZACIONES_TRAMITES.JUSTIFICACION",
	"COTIZACIONES_TRAMITES.CAMBIO",
	"COTIZACIONES_TRAMITES.ID_SERVICIO_CLIENTE",
	"ETAPAS_PROCESO.ETAPA"
];
$tramites =  $database->select("COTIZACIONES_TRAMITES", ["[>]ETAPAS_PROCESO" => ["ID_ETAPA_PROCESO" => "ID_ETAPA"]], $campos_tramite,
	["ID_COTIZACION"=>$cotizacion[0]["ID"]]);
valida_error_medoo_and_die(); 



$cotizacion[0]["SERVICIO"] = $servicio;
$cotizacion[0]["TIPOS_SERVICIO"] = $tipos_servicio;
$cotizacion[0]["NORMA"] = $norma;
$cotizacion[0]["ESTADO"] = $estado;
$cotizacion[0]["COTIZACION_TRAMITES"] = $tramites;

$CONSECUTIVO = str_pad("".$cotizacion[0]["FOLIO_CONSECUTIVO"], 5, "0", STR_PAD_LEFT);
$FOLIO = $cotizacion[0]["FOLIO_INICIALES"].$cotizacion[0]["FOLIO_SERVICIO"].$CONSECUTIVO.$cotizacion[0]["FOLIO_MES"].$cotizacion[0]["FOLIO_YEAR"];
if( !is_null($cotizacion[0]["FOLIO_UPDATE"]) && $cotizacion[0]["FOLIO_UPDATE"] != ""){
	$FOLIO .= "-".$cotizacion[0]["FOLIO_UPDATE"];
}
$cotizacion[0]["FOLIO"] = $FOLIO;

/*===========================================================================*/
/*===========================================================================*/
/*===========================================================================*/

/////////////////////////////////////////////////////////////////////////////////////////////////
//CONTACTO
/////////////////////////////////////////////////////////////////////////////////////////////////
if($cotizacion[0]["BANDERA"] == 0 && $id_cliente == 0){
	//NOMBRE PROSPECTO

	$prospectoall = $database->select("PROSPECTO","*",["ID"=>$id_prospecto]);

	$contacto_cotizacion = $database->get("PROSPECTO_CONTACTO", 
		["[>]PROSPECTO_DOMICILIO" => ["PROSPECTO_CONTACTO.ID_PROSPECTO_DOMICILIO" => "ID"],
		"[>]USUARIOS(USUARIO_CREAR)" => ["PROSPECTO_CONTACTO.USUARIO_CREACION" => "ID"],
		"[>]USUARIOS(USUARIO_MOD)" => ["PROSPECTO_CONTACTO.USUARIO_MODIFICACION" => "ID"]
		],  
		[
	"PROSPECTO_CONTACTO.ID",
	"PROSPECTO_CONTACTO.ID_PROSPECTO_DOMICILIO",
	"PROSPECTO_CONTACTO.NOMBRE",
	"PROSPECTO_CONTACTO.CORREO",
	"PROSPECTO_CONTACTO.TELEFONO",
	"PROSPECTO_CONTACTO.CELULAR",
	"PROSPECTO_CONTACTO.PUESTO",
	"PROSPECTO_CONTACTO.ACTIVO",
	"PROSPECTO_CONTACTO.FECHA_CREACION",
	"PROSPECTO_CONTACTO.FECHA_MODIFICACION",
	"PROSPECTO_CONTACTO.USUARIO_CREACION",
	"PROSPECTO_CONTACTO.USUARIO_MODIFICACION",
	"PROSPECTO_CONTACTO.DATOS_ADICIONALES",
	"USUARIO_CREAR.NOMBRE(NOMBRE_USUARIO_CREAR)",
	"USUARIO_MOD.NOMBRE(NOMBRE_USUARIO_MOD)",
	"PROSPECTO_DOMICILIO.NOMBRE(NOMBRE_DOMICILIO)",
	"PROSPECTO_DOMICILIO.ESTADO(ESTADO)",
	"PROSPECTO_CONTACTO.CORREO2"
	], 
	["PROSPECTO_CONTACTO.ID"=>$id_contacto]); 
/////////////////////////////////////////////////////////////////////////////////////////////////
//DOMICILIO
/////////////////////////////////////////////////////////////////////////////////////////////////
$domicilio_cotizacion =  $database->get("PROSPECTO_DOMICILIO" , 
		["[>]USUARIOS(USUARIO_CREAR)" => ["PROSPECTO_DOMICILIO.USUARIO_CREACION" => "ID"],
		"[>]USUARIOS(USUARIO_MOD)" => ["PROSPECTO_DOMICILIO.USUARIO_MODIFICACION" => "ID"]
		],
		[
	"PROSPECTO_DOMICILIO.ID",
	"PROSPECTO_DOMICILIO.NOMBRE",
	"PROSPECTO_DOMICILIO.PAIS",
	"PROSPECTO_DOMICILIO.ESTADO",
	"PROSPECTO_DOMICILIO.MUNICIPIO",
	"PROSPECTO_DOMICILIO.COLONIA",
	"PROSPECTO_DOMICILIO.CODIGO_POSTAL",
	"PROSPECTO_DOMICILIO.CALLE",
	"PROSPECTO_DOMICILIO.NUMERO_INTERIOR",
	"PROSPECTO_DOMICILIO.NUMERO_EXTERIOR",
	"PROSPECTO_DOMICILIO.FISCAL",
	"PROSPECTO_DOMICILIO.CENTRAL",
	"PROSPECTO_DOMICILIO.FECHA_CREACION",
	"PROSPECTO_DOMICILIO.FECHA_MODIFICACION",
	"PROSPECTO_DOMICILIO.USUARIO_CREACION",
	"PROSPECTO_DOMICILIO.USUARIO_MODIFICACION",
	"USUARIO_CREAR.NOMBRE(NOMBRE_USUARIO_CREAR)",
	"USUARIO_MOD.NOMBRE(NOMBRE_USUARIO_MOD)"
	]	
		, ["PROSPECTO_DOMICILIO.ID"=>$id_domicilio]);
}
if($cotizacion[0]["BANDERA"] == 0 && $id_cliente != 0){
	//NOMBRE ClIENTES

	$prospectoall = $database->select("CLIENTES","*",["ID"=>$id_cliente]);
	$dom_cliente = $database->get("CLIENTES_DOMICILIOS", "*", ["ID"=>$id_domicilio]);
	$cont_cliente = $database->get("CLIENTES_CONTACTOS","*",["ID"=>$id_contacto]);
	valida_error_medoo_and_die();
}
if($cotizacion[0]["BANDERA"] == 1){
	//NOMBRE ClIENTES

	$prospectoall = $database->select("CLIENTES","*",["ID"=>$id_prospecto]);
	$dom_cliente = $database->get("CLIENTES_DOMICILIOS", "*", ["ID"=>$id_domicilio]);
	$cont_cliente = $database->get("CLIENTES_CONTACTOS","*",["ID"=>$id_contacto]);
	valida_error_medoo_and_die();
}		
/////////////////////////////////////////////////////////////////////////////////////////////////	
//			Variables que ahi que imprimir
//print_r($cotizacion);

$name_prospecto = $prospectoall[0]["NOMBRE"];//$name_prospecto = $datos->name_prospecto;
$area = $cotizacion[0]['SERVICIO']["NOMBRE"];//$nombrearea[0]["NOMBRE"];//$area = $datos->servicio;//
$fecha = mes_esp(date('d/n/Y'));
$referencia_comercial = $cotizacion[0]["FOLIO"];//$cotizacion[0]["REFERENCIA"];//"001082017-01";$referencia_comercial = $datos->folio;//
if($cotizacion[0]["BANDERA"] == 0 && $id_cliente == 0){
	$direccion_contacto = "Calle ".$domicilio_cotizacion["CALLE"]." Exterior ".$domicilio_cotizacion["NUMERO_EXTERIOR"]." Interior ".$domicilio_cotizacion["NUMERO_INTERIOR"]." Colonia ".$domicilio_cotizacion["COLONIA"]." Delegacion ".$domicilio_cotizacion["MUNICIPIO"].",CP ".$domicilio_cotizacion["CODIGO_POSTAL"].", ".$domicilio_cotizacion["ESTADO"].", ".$domicilio_cotizacion["PAIS"];//$direccion_contacto="";//
	
	$name_contacto = $contacto_cotizacion["NOMBRE"];//$name_contacto="";//
	$cargo_contacto = $contacto_cotizacion["PUESTO"];//$cargo_contacto="";//
	$telefono_contacto = $contacto_cotizacion["TELEFONO"];//$telefono_contacto="";//
	$email = $contacto_cotizacion["CORREO"];//$email="";//
}
if($cotizacion[0]["BANDERA"] == 0 && $id_cliente != 0){
	$direccion_contacto = "Calle ".$dom_cliente["CALLE"]." Exterior ".$dom_cliente["NUMERO_EXTERIOR"]." Interior ".$dom_cliente["NUMERO_INTERIOR"]." Colonia ".$dom_cliente["COLONIA_BARRIO"]." Delegacion ".$dom_cliente["DELEGACION_MUNICIPIO"].",CP ".$dom_cliente["CP"].", ".$dom_cliente["ENTIDAD_FEDERATIVA"].", ".$dom_cliente["PAIS"];//$direccion_contacto="";//
	
	$name_contacto = $cont_cliente["NOMBRE_CONTACTO"];//$name_contacto="";//
	$cargo_contacto = $cont_cliente["CARGO"];//$cargo_contacto="";//
	$telefono_contacto = $cont_cliente["TELEFONO_FIJO"];//$telefono_contacto="";//
	$email = $cont_cliente["EMAIL"];//$email="";//
}
if($cotizacion[0]["BANDERA"] == 1){
	$direccion_contacto = "Calle ".$dom_cliente["CALLE"]." Exterior ".$dom_cliente["NUMERO_EXTERIOR"]." Interior ".$dom_cliente["NUMERO_INTERIOR"]." Colonia ".$dom_cliente["COLONIA_BARRIO"]." Delegacion ".$dom_cliente["DELEGACION_MUNICIPIO"].",CP ".$dom_cliente["CP"].", ".$dom_cliente["ENTIDAD_FEDERATIVA"].", ".$dom_cliente["PAIS"];//$direccion_contacto="";//
	
	$name_contacto = $cont_cliente["NOMBRE_CONTACTO"];//$name_contacto="";//
	$cargo_contacto = $cont_cliente["CARGO"];//$cargo_contacto="";//
	$telefono_contacto = $cont_cliente["TELEFONO_FIJO"];//$telefono_contacto="";//
	$email = $cont_cliente["EMAIL"];//$email="";//
}

/*=======================================================================================*/
//		AQUI VOY A BUSCAR LA CANTIDAD DE SITIOS Y DE EMPLEADOS TOTAL EN ESOS SITIOS
/*=======================================================================================*/
$where_sitios="";
$or="";/*
for($i=0;$i<count($datos);$i++){
	if($i==0){
		$or=" ";
	}
	else{
		$or=" OR ";
	}
	$where_sitios .= $or.'`ID_COTIZACION`='.$datos[$i]->ID; 
}*/
//$query = 'SELECT DISTINCT `ID_DOMICILIO_SITIO`,`TOTAL_EMPLEADOS` FROM `COTIZACION_SITIOS` WHERE'.$where_sitios;
$query = 'SELECT `ID_DOMICILIO_SITIO`,`TOTAL_EMPLEADOS` FROM `COTIZACION_SITIOS` WHERE ID_COTIZACION = '.$id_cotizacion;
$respuesta = $database->query($query)->fetchAll(PDO::FETCH_ASSOC);
$suma_emple=0;
for($i=0;$i<count($respuesta);$i++){
	$suma_emple += $respuesta[$i]['TOTAL_EMPLEADOS'];
	// AQUI CARGO LOS DATOS DE LOS DOMICILIOS ADICIONALES (NOMBRE Y DIRECCION)
	if($cotizacion[0]["BANDERA"] == 0 && $id_cliente == 0){
		$domicilio_cotizacion1 =  $database->get("PROSPECTO_DOMICILIO" , 
				["[>]USUARIOS(USUARIO_CREAR)" => ["PROSPECTO_DOMICILIO.USUARIO_CREACION" => "ID"],
				"[>]USUARIOS(USUARIO_MOD)" => ["PROSPECTO_DOMICILIO.USUARIO_MODIFICACION" => "ID"]
				],
				[
					"PROSPECTO_DOMICILIO.ID",
					"PROSPECTO_DOMICILIO.NOMBRE",
					"PROSPECTO_DOMICILIO.PAIS",
					"PROSPECTO_DOMICILIO.ESTADO",
					"PROSPECTO_DOMICILIO.MUNICIPIO",
					"PROSPECTO_DOMICILIO.COLONIA",
					"PROSPECTO_DOMICILIO.CODIGO_POSTAL",
					"PROSPECTO_DOMICILIO.CALLE",
					"PROSPECTO_DOMICILIO.NUMERO_INTERIOR",
					"PROSPECTO_DOMICILIO.NUMERO_EXTERIOR",
					"PROSPECTO_DOMICILIO.FISCAL",
					"PROSPECTO_DOMICILIO.CENTRAL",
					"PROSPECTO_DOMICILIO.FECHA_CREACION",
					"PROSPECTO_DOMICILIO.FECHA_MODIFICACION",
					"PROSPECTO_DOMICILIO.USUARIO_CREACION",
					"PROSPECTO_DOMICILIO.USUARIO_MODIFICACION",
					"USUARIO_CREAR.NOMBRE(NOMBRE_USUARIO_CREAR)",
					"USUARIO_MOD.NOMBRE(NOMBRE_USUARIO_MOD)"
			]	
				, ["PROSPECTO_DOMICILIO.ID"=>$respuesta[$i]['ID_DOMICILIO_SITIO']]);
			
			$anexo1[$i]['DOMICILIO'] = "Calle ".$domicilio_cotizacion1["CALLE"]." Exterior ".$domicilio_cotizacion1["NUMERO_EXTERIOR"]." Interior ".$domicilio_cotizacion1["NUMERO_INTERIOR"]." Colonia ".$domicilio_cotizacion1["COLONIA"]." Delegacion ".$domicilio_cotizacion1["MUNICIPIO"].",CP ".$domicilio_cotizacion1["CODIGO_POSTAL"].", ".$domicilio_cotizacion1["ESTADO"].", ".$domicilio_cotizacion1["PAIS"];
			$anexo1[$i]['NOMBRE'] = $domicilio_cotizacion1["NOMBRE"];	
	}
	if($cotizacion[0]["BANDERA"] == 0 && $id_cliente != 0){
		$dom_cliente1 = $database->get("CLIENTES_DOMICILIOS", "*", ["ID"=>$respuesta[$i]['ID_DOMICILIO_SITIO']]);
		$anexo1[$i]['DOMICILIO'] = "Calle ".$dom_cliente1["CALLE"]." Exterior ".$dom_cliente1["NUMERO_EXTERIOR"]." Interior ".$dom_cliente1["NUMERO_INTERIOR"]." Colonia ".$dom_cliente1["COLONIA_BARRIO"]." Delegacion ".$dom_cliente1["DELEGACION_MUNICIPIO"].",CP ".$dom_cliente1["CP"].", ".$dom_cliente1["ENTIDAD_FEDERATIVA"].", ".$dom_cliente1["PAIS"];
		$anexo1[$i]['NOMBRE'] = $dom_cliente1["NOMBRE_DOMICILIO"];	
	}
	if($cotizacion[0]["BANDERA"] == 1){
		$dom_cliente1 = $database->get("CLIENTES_DOMICILIOS", "*", ["ID"=>$respuesta[$i]['ID_DOMICILIO_SITIO']]);
		$anexo1[$i]['DOMICILIO'] = "Calle ".$dom_cliente1["CALLE"]." Exterior ".$dom_cliente1["NUMERO_EXTERIOR"]." Interior ".$dom_cliente1["NUMERO_INTERIOR"]." Colonia ".$dom_cliente1["COLONIA_BARRIO"]." Delegacion ".$dom_cliente1["DELEGACION_MUNICIPIO"].",CP ".$dom_cliente1["CP"].", ".$dom_cliente1["ENTIDAD_FEDERATIVA"].", ".$dom_cliente1["PAIS"];
		$anexo1[$i]['NOMBRE'] = $dom_cliente1["NOMBRE_DOMICILIO"];	
	}
}
/*=======================================================================================*/

//$norma = $cotizacion[0]["NORMA"]['ID'];//$nombrearea[0]["PNOMBRE"];//$norma=$datos->norma;;//
$NoEmpleados = $suma_emple;//$NoEmpleados = $cotizacion[0]["NO_EMPLEADOS"];//"3";
$NoSitios =count($respuesta);//$NoSitios = $cotizacion[0]["NO_SITIOS"];//"3";
$Importe_Certificado = "";//$Importe_Certificado = $cotizacion[0]["IMPORTE_CERTIFICADO"];//"3";




//////////////////////////////////////////////////////////////////////////////////////////////
//Prueba
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	private $selector;
	private $direccion;
	private $certificacion;

	function __construct($CERTIFICACION, $DIRECCION, $SELECTOR, $PDF_PAGE_ORIENTATION, $PDF_UNIT, $PDF_PAGE_FORMAT, $B1, $coding, $B2) {
       parent::__construct($PDF_PAGE_ORIENTATION, $PDF_UNIT, $PDF_PAGE_FORMAT, $B1, $coding, $B2);
       $this->selector = $SELECTOR;
       $this->direccion = $DIRECCION;
       $this->certificacion = $CERTIFICACION;//strtoupper($CERTIFICACION);
   }

	//Page header
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		$img_file = '../img/fondo.jpg';
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
		$NumPage = $this->getPage();
		if($NumPage !== 1){
			// Set font
			$this->SetFont('Helvetica', 'B', 18);
			// Title
			$this->setCellMargins(15,15,0,0);
			$this->Cell(0, 10, 'Propuesta Económica', 0, false, 'L', 0, '', 0, false, 'M', 'M');
			
			// Set font
			$this->SetFont('Calibri', 'B', 14);
			// Title
			$this->SetY(3);
			$this->setCellMargins(15,25,0,0);
			$this->Cell(0, 10, "Cotización de Certificación PASSE", 0, false, 'L', 0, '', 0, false, 'M', 'M');
			
		}
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-20);
		// Set font
		// Page number
		//$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$NumPage =  $this->getAliasNumPage();
		$TotPage =	$this->getAliasNbPages();
		$a=123;
		$this->SetFont('Helvetica', '', 11);
		$this->SetTextColor(255,255,255);

		// Lugar, fecha y claves (alineado a la derecha)
$html = <<<EOD
<table cellpadding="3" cellspacing="0" border="0">
	
	<tr>
		<td style="font-size: small; text-align:center;" width="400">
			
		</td>
		
		<td style="font-size: small; text-align:rigth;" width="115"> 
			Página $NumPage de $TotPage
		</td>
	</tr>
</table>
EOD;

		$this->writeHTML($html, true, false, true, false, '');
		
	}
}




//////////////////////////////////////////////////////////////////////////////////////////////
//require_once('../../../phplibs/libPDF/examples/tcpdf_include.php');
//////////////////////////

$No1="Certificación PASSE";//"Cotización ".$area;
$No2="Empresa: ".$name_prospecto;
$No3=$fecha;
$No4 = $referencia_comercial;
$No5= "Cotización de ".$area;
$No6 = $name_prospecto;
$No7="";//$No7 = $direccion_contacto;
$No8="";//$No8 = $name_contacto;
$No9="";//$No9 = $cargo_contacto;
$No10 = "";//$No10 = $telefono_contacto;
$No11="";//$No11 = $email;
$No12 = $norma;
$No13="";//$No13 = $NoEmpleados;
$No14="";//$No14 = $NoSitios;


//////////////////////////
// create new PDF document
//$pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$str_direccion="Daniel Hernandez Barroso";
//$global_diffname="E:/xampp/htdocs/imnc/imnc/generar/pdf/cotizacion/";
$global_diffname="http://apinube.com/imnc/siec2.0/imnc/generar/pdf/cotizacion/";
// create new PDF document
$pdf1 = new MYPDF($No5, $str_direccion, $global_direccion_pdf, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$fontname = TCPDF_FONTS::addTTFfont('E:/xampp/htdocs/imnc/imnc/phplibs/libPDF/fonts/Calibri Bold Italic.ttf','TrueTypeUnicode','',96);
$pdf1->AddFont('Calibri','','calibri.php');
$pdf1->AddFont('Calibri','B','calibrib.php');
$pdf1->AddFont('Calibri','I','calibrii.php');
$pdf1->AddFont('Calibri','BI','calibribi.php');
$pdf1->AddFont('Calibril','','calibril.php');

// set default header data
$pdf1->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 021', PDF_HEADER_STRING);


// set header and footer fonts
$pdf1->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf1->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf1->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf1->SetMargins(PDF_MARGIN_LEFT, 40 , PDF_MARGIN_RIGHT);//PDF_MARGIN_TOP
$pdf1->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf1->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf1->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor/
//$pdf1->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf1->setLanguageArray($l);
}

// set font
$pdf1->SetFont('helvetica', '', 25);
$pdf1->SetTextColor(54,95,145);
// add a page (ESTA ES LA PAGINA DE PORTADA)
$pdf1->SetPrintHeader(true);
$pdf1->AddPage();
$pdf1->SetPrintFooter(false);
$pdf1->Image('../img/logob.png', 160, 10, 45, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
$pdf1->Image('../img/logoc.png', 20, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

$pdf1->SetXY(0,0);
// Titulo de documento (centrado)
$html = '<br><br><br><br><div style="text-align:center;"><h3>Propuesta Económica </h3></div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('helvetica','B',18);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 style="text-align:center;"> '.$No1.' </h3><br>';
//$html .= '</div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibril', '', 16);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 style="text-align:center;"> Buenas Prácticas para un Ambiente Sano y Seguro </h3><br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('helvetica', 'B', 18);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 style="text-align:center;"> '.$No2.' </h3><br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibril','',18);
$pdf1->SetTextColor(128,128,128);
$html = '<h3 style="text-align:center;"> '.$No3.' </h3><br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('helvetica', 'B', 18);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 style="text-align:center;"> '.$No4.' </h3><br>';
$pdf1->writeHTML($html, true, false, true, false, '');

// add a page (ESTA ES LA SEGUNDA PAGINA)
$pdf1->SetPrintHeader(true);
$pdf1->AddPage();
$pdf1->SetPrintFooter(true);
$pdf1->SetFont('Calibri', '', 9);
$html = <<<EOT
<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1"><strong>DATOS GENERALES</strong></th>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Organización:</td>
		<td style="font-size: medium; text-align:left" width="350"> $name_prospecto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Dirección:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $direccion_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Contacto:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $name_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Cargo:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $cargo_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Teléfono:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $telefono_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Email:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $email</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Norma:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $norma2</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">No. Empleados:</td>
		<td style="font-size: medium;  text-align:left" width="150"> $NoEmpleados</td>
		<td style="font-size: medium; text-align:right" width="50">No. Sitios:</td>
		<td style="font-size: medium;  text-align:left" width="150"> $NoSitios</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');

for($i=0;$i<count($datos);$i++){
	if($i==3 || $i == 7 || $i==11){
		$pdf1->AddPage();
	}
	if($datos[$i]->ID_ETAPA_PROCESO == 2 && $datos[$i+1]->ID_ETAPA_PROCESO == 3){
		$Titulo_Tabla="SERVICIO DE AUDITORÍA DE CERTIFICACIÓN INICIAL";
		$tarifa_E1 =0;
		$tarifa_E2 =0;
		$dias_auditor_E1 = $datos[$i]->DIAS_AUDITORIA;
		$costo_E1	=	$datos[$i]->TRAMITE_COSTO_DES;
		$viaticos_E1 = $datos[$i]->VIATICOS;
		$dias_auditor_E2 = $datos[$i+1]->DIAS_AUDITORIA;
		$costo_E2	=	$datos[$i+1]->TRAMITE_COSTO_DES;
		$viaticos_E2 = $datos[$i+1]->VIATICOS;
		$subtotal= $costo_E1+$costo_E2+$viaticos_E1+$viaticos_E2;
		$IVA16=0.16*$subtotal;
		$total=$subtotal+$IVA16;

		//Dando formato a los datos
		$costo_E1_f=number_format($costo_E1,2);
		$costo_E2_f=number_format($costo_E2,2);
		$html = <<<EOT
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
			<tr style="background-color: #1F487B;">
				<th style="font-size: medium; color:white;" colspan="1"><strong>$Titulo_Tabla</strong></th>
			</tr>
			<tr>
				<th style="font-size: medium;" width="225"><strong>Descripción del servicio</strong></th>
				<th style="font-size: medium; background-color: #D8E4F0;" width="100"><strong>Dias auditos requeridos</strong></th>
				<th style="font-size: medium;" width="125"><strong>Costo</strong></th>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="225">1. Auditoria Etapa 1</td>
				<td style="font-size: medium; background-color: #D8E4F0;" width="100">$dias_auditor_E1</td>
				<td style="font-size: medium; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$costo_E1_f</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="225">2. Auditoria Etapa 2</td>
				<td style="font-size: medium; background-color: #D8E4F0;" width="100">$dias_auditor_E2</td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$costo_E2_f</td>
			</tr>
EOT;
			$tarifa=0;
			$suma_tarifa=0;
			if(count($datos1[$i])>0||count($datos1[$i+1])>0){
				for($j=0;$j<count($datos1[$i]);$j++){
				$descripcion = $datos1[$i][$j]->DESCRIPCION;
				$tarifa	=	$datos1[$i][$j]->COSTO_TOTAL;
				$suma_tarifa += $tarifa;
				//Dando formato a los datos
				$tarifa_f=number_format($tarifa,2);
				$tarifa_E1=$tarifa;
				$html .= <<<EOT
						<tr>
							<td style="font-size: medium; text-align:right; color:#5779A3" width="225">$descripcion</td>
							<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
							<td style="font-size: medium; color:#5779A3" width="25">$</td>
							<td style="font-size: medium;" width="100">$tarifa_f</td>
						</tr>
EOT;
				}
			if(count($datos1[$i+1])>0){
				for($j=0;$j<count($datos1[$i+1]);$j++){
				$descripcion = $datos1[$i+1][$j]->DESCRIPCION;
				$tarifa	=	$datos1[$i+1][$j]->COSTO_TOTAL;
				$suma_tarifa += $tarifa;
				//Dando formato a los datos
				$tarifa_f=number_format($tarifa,2);
				$tarifa_E2=$tarifa;
				$html .= <<<EOT
						<tr>
							<td style="font-size: medium; text-align:right; color:#5779A3" width="225">$descripcion</td>
							<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
							<td style="font-size: medium; color:#5779A3" width="25">$</td>
							<td style="font-size: medium;" width="100">$tarifa_f</td>
						</tr>
EOT;
			}}
			}		
			$subtotal=$costo_E1+$costo_E2+$suma_tarifa+$viaticos_E1+$viaticos_E2;
			$viaticos = $viaticos_E1+$viaticos_E2;
			$IVA16=0.16*$subtotal;
			$total=$subtotal+$IVA16;
			$monto = $database->update("COTIZACIONES_TRAMITES", [
				"MONTO" => $total
			], ["ID"=>$datos[$i]->ID]);
			$subtotal_f=number_format($subtotal,2);
			$IVA16_f=number_format($IVA16,2);
			$total_f=number_format($total,2);
			$html .= <<<EOT
						
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Viaticos</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$viaticos</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Subtotal</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$subtotal_f</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>I.V.A. 16%</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$IVA16_f</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Total</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$total_f</td>
			</tr>
		</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$i=$i+1;

		// ---------------------------------------------------------
		
//	$id = $database->update("COTIZACIONES_TRAMITES", [
//		"MONTO" => $costo_E1+$viaticos_E1+$tarifa_E1
//	], ["AND"=>["ID"=>$id_cotizacion,"ID_ETAPA_PROCESO"=>2]]);
//	$id = $database->update("COTIZACIONES_TRAMITES", [
//		"MONTO" => $costo_E2+$viaticos_E2+$tarifa_E2
//	], ["AND"=>["ID"=>$id_cotizacion,"ID_ETAPA_PROCESO"=>3]]);
	}
	else{
		$Titulo_Tabla="SERVICIO DE ".strtoupper($datos[$i]->TIPO);
		$Descripcion_servicio=$datos[$i]->TIPO;
		$dias_auditor = $datos[$i]->DIAS_AUDITORIA;
		$costo	=	$datos[$i]->TRAMITE_COSTO_DES;
		$viaticos = $datos[$i]->VIATICOS;
		//Dando formato a los datos
		$costo_f=number_format($costo,2);
		$html = <<<EOT
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
			<tr style="background-color: #1F487B;">
				<th style="font-size: medium; color:white;" colspan="1"><strong>$Titulo_Tabla</strong></th>
			</tr>
			<tr>
				<th style="font-size: medium;" width="225"><strong>Descripción del servicio</strong></th>
				<th style="font-size: medium; background-color: #D8E4F0;" width="100"><strong>Dias auditos requeridos</strong></th>
				<th style="font-size: medium;" width="125"><strong>Costo</strong></th>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="225">$Descripcion_servicio</td>
				<td style="font-size: medium; background-color: #D8E4F0;" width="100">$dias_auditor</td>
				<td style="font-size: medium; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$costo_f</td>
			</tr>
EOT;
			$tarifa=0;
			$suma_tarifa=0;
			if(count($datos1[$i])>0){
				for($j=0;$j<count($datos1[$i]);$j++){
				$descripcion = $datos1[$i][$j]->DESCRIPCION;
				$tarifa	=	$datos1[$i][$j]->COSTO_TOTAL;
				$suma_tarifa += $tarifa;
				//Dando formato a los datos
				$tarifa_f=number_format($tarifa,2);
				$html .= <<<EOT
						<tr>
							<td style="font-size: medium; text-align:right; color:#5779A3" width="225">$descripcion</td>
							<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
							<td style="font-size: medium; color:#5779A3" width="25">$</td>
							<td style="font-size: medium;" width="100">$tarifa_f</td>
						</tr>
EOT;
			}}		
			$subtotal=$costo+$suma_tarifa+$viaticos;
			$IVA16=0.16*$subtotal;
			$total=$subtotal+$IVA16;
			$monto = $database->update("COTIZACIONES_TRAMITES", [
				"MONTO" => $total
			], ["ID"=>$datos[$i]->ID]);
			$subtotal_f=number_format($subtotal,2);
			$IVA16_f=number_format($IVA16,2);
			$total_f=number_format($total,2);
        $html .= <<<EOT
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Viaticos</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$viaticos</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Subtotal</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$subtotal_f</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>I.V.A. 16%</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$IVA16_f</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Total</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$total_f</td>
			</tr>
		</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
//$id = $database->update("COTIZACIONES_TRAMITES", [
//		"MONTO" => $subtotal
//	], ["AND"=>["ID_COTIZACION"=>$id_cotizacion,"ID_ETAPA_PROCESO"=>$datos[$i]->ID_ETAPA_PROCESO]]);
	}

	
	
	

}
// add a page (ESTA ES LA TERCERA PAGINA)
$pdf1->AddPage();
$pdf1->SetFont('Calibri', 'B', 16);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 > Propuesta económica/Cotización del servicio de certificación PASSE
de gestión. </h3>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html = '<br><b> 1.	Precios e Impuestos. </b><br>';
$html .= ' Esta Propuesta económica/Cotización se expresa en moneda nacional MXN (Pesos mexicanos).<br>';
$html .= ' Lo referente al I.V.A. o impuestos ya están incluidos en la presente Propuesta económica/Cotización.<br>';
$html .= '<br><b> 2. Gastos de Servicio. </b><br>';
$html .= ' La presente Propuesta económica/Cotización no incluye lo relacionado a gastos de servicio (Transportación, Alimentación y Hospedaje) y tendrán que ser cubiertos por la empresa.<br>
En caso de que la empresa requiera hacerse cargo de los gastos de servicio para economizar recursos, tendrá que notificar al organismo de certificación al momento de aceptación (firma) de la propuesta.<br>
Cuando el cliente se haga cargo de los gastos, los itinerarios de vuelos deberán ser aprobados por el organismo de certificación antes de ser comprados. Es necesario que como máximo 5 días hábiles antes de la fecha de ejecución del servicio se envíen al organismo de certificación los vuelos, hospedajes y transportación local.<br>';
$html .= '<br><b> 3. Vigencia de la Propuesta económica/Cotización. </b><br>';
$html .= ' La presente Propuesta económica/Cotización tiene una vigencia de 30 días naturales y contaran a partir de la fecha de emisión, pasado este periodo la propuesta se actualizará conforme la política de precios vigente.<br>';
$html .= '<br><b>4. Especificaciones Técnicas.</b><br>';
$html .= ' El número de días auditor de esta Propuesta económica/Cotización, tiene como base las directrices del esquema de certificación Buenas Prácticas para un Ambiente Sano y Seguro. La presente propuesta económica ha sido emitida con base a la información que el cliente ha hecho del conocimiento al organismo, mediante la “Solicitud de servicio de certificación” correspondiente.<br>';
$html .= '<br><b>4.1. Etapa documental y etapa en sitio.</b><br>';
$html .= ' La Auditoría documental se realizará vía remota por lo que el cliente debe garantizar tener suficiente internet con el objetivo de asegurar la conexión, el organismo proveerá la sesión en plataforma para poder llevarla a cabo, durante esta sesión se revisara documentalmente que lo declarado en la solicitud sea real para validar que es correcta la estimación de tiempo asignado para la evaluación completa. El organismo tiene la facultad de analizar si requerirá generar una reducción, o un incremento del tiempo de auditoría cotizado inicialmente en la presente oferta por alguna declaración errónea del cliente.<br> ';
$html .= '<br><b>4.2 Auditoría de Vigilancia.</b><br>';
$html .= ' Los servicios de Auditoría de Vigilancia se especifican en la presente Propuesta económica/Cotización sobre la base que deben realizarse al menos dos al año, excepto las auditorías de renovación y considerando que las auditorías serán programadas de forma cuatrimestral y una de ellas será no anunciada; el tiempo destinado para vigilancia será dividido en documental y sitio.<br>';
$html .= '<br><b>4.3. Auditorías especiales. </b><br>';
$html .= ' El organismo tiene la facultad de tener que realizar auditorías a sus clientes certificados, bajo la forma de visita especial, con el fin de investigar quejas, o respuestas a cambios, o como seguimientos de clientes cuya certificación haya sido suspendida. Este tipo de auditorías, se cotizarán de acuerdo a la necesidad.<br>';
$html .= '<br><b>4.4 Certificado.</b>';
$html .= ' La Vigencia del Certificado emitido es por 1 (un) año.<br>';
$html .= '<br><b>4.5 Modificaciones al certificado.</b><br>';
$html .= ' Si durante la vigencia de este contrato, surge la necesidad de hacer modificaciones al Certificado (por ejemplo: nombre o razón social de la empresa, dirección, norma, alcance y número de sitios), será emitida en su momento la cotización correspondiente.<br>';

$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = '<br><b>4.6 Cambios en la organización del cliente.</b><br>';
$html .= ' El Cliente se obliga a informar al organismo de certificación dentro de los primeros 15 días posteriores a estos, que puedan afectar la capacidad de su sistema para continuar cumpliendo los requisitos del esquema utilizado para la certificación (por ejemplo: cambios en la condición legal, comercial o de propiedad); la organización y la gestión (por ejemplo: personal clave como directivos, personal que toma decisiones o personal técnico), cambio de domicilio y lugar de contacto; el alcance de las operaciones, procesos y productos cubiertos por el sistema certificado. <br>
 En tales casos el organismo tiene la facultad de determinar si es necesario conducir una auditoría especial, y en su caso emitirá una nueva Propuesta económica/Cotización del servicio para el visto bueno del cliente. <br>';
$html .= '<br><b>4.7 Renovación</b><br>';
$html .= ' Para renovar el certificado, se tendrá que realizar una auditoría de Renovación, y tendrá que ejecutarse preferentemente con una antelación de 3 meses al vencimiento del certificado.<br>';
$html .= '<br><b>4.8 Testificación.</b><br>';
$html .= ' El organismo de certificación puede asignar a personal adicional al auditor o equipo auditor con fines de entrenamiento para el personal o testificación, personal que únicamente participa con fines de observación sobre como realiza sus actividades el auditor o equipo auditor, sin tener injerencia sobre las decisiones de los mismos hacia la empresa.<br>';
$html .= '<br> <b>5. Suspensión.</b>';
$html .= ' En el caso de que algún elemento de los párrafos anteriores sea incumplido por parte del cliente, el organismo de certificación podrá poner en proceso de Suspensión la certificación; en caso de no atenderse los plazos y actividades marcadas en el plazo de suspensión, se procederá a la Cancelación del Certificado del Cliente.<br>';
$html .= '<br><b>6. Cancelación.</b><br>';
$html .= '  La cancelación de esta Propuesta económica/Cotización, podrá ser en cualquier momento por cualquiera de las partes, cuando se presenten los siguientes supuestos:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a.	A solicitud del cliente, mediante un comunicado por escrito y con una antelación de 3 meses en relación a la fecha de inicio de cualquiera de los servicios programados.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b.	Por no pagar las contraprestaciones correspondientes a los anticipos y/o a los servicios ejecutados por el organismo.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c.	Por incumplimiento de las obligaciones definidas a cargo de cada una de las partes en el presente documento.<br><br>
En caso de cancelación después del tiempo establecido en el inciso a, el cliente tendrá que pagar un monto del 70% del servicio a realizar, con la finalidad de cubrir gastos administrativos inherentes al cierre de la certificación.<br>';
$html .= '<b>7. Programación y Logística.</b><br>';
$html .= ' Para la programación y ejecución de las auditorías, es requisito indispensable que el cliente ingrese al organismo de certificación la Propuesta económica/Cotización firmada.<br><br>
Las fechas para realizar las auditorías, serán programadas de común acuerdo con al menos 15 días naturales de antelación.<br><br>
En reprogramaciones por parte del cliente dentro de los 5 días naturales al servicio donde se requiera realizar modificaciones al equipo auditor para cubrir el ejercicio de auditoría, el cliente absorberá los gastos correspondientes. <br><br>';
$html .= '<br><b>8. Aceptación de la Propuesta económica/Cotización.</b><br>';
$html .= ' Al firmar esta Propuesta económica/Cotización, está generando la aceptación de la Contratación de los servicios y su organización manifiesta que ha leído, comprendido y acepta las Condiciones Generales para la Certificación PASSE.<br>';
$html .= ' Aceptada la presente Propuesta económica/Cotización se deberá anexar copia simple de los siguientes documentos: acta constitutiva, identificación del representante legal (en caso de que al representante legal no le sean conferidos poderes para firmar contratos en el acta constitutiva, se requiere copia del testimonio notarial correspondiente), inscripción en el R.F.C., comprobante de domicilio y especificaciones de facturación (forma de pago, método de pago, uso de cfdi).<br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = '<br><b>9. Usos y políticas de logotipos.</b><br>';
$html .= ' El cliente se deberá apegar al Reglamento para el uso de marcas, el cual será proporcionado a la entrega del certificado.<br>';
$html .= '<br><b>10. Confidencialidad.</b><br>';
$html .= ' Toda la información obtenida durante la ejecución de las actividades de la evaluación de la conformidad y certificación del cliente, será manejada estrictamente de manera reservada y confidencial por parte del organismo de certificación y no podrá ser revelada a un tercero o persona particular, sin el previo consentimiento por escrito del cliente.<br><br>';
$html .= 'En espera de vernos favorecidos con su preferencia, quedamos a sus órdenes.<br><br><br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri','',10);
$html = <<<EOT
<table cellpadding="15" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium;" width="200"></td>
		<td style="font-size: medium;" width="76"></td>
		<td style="font-size: medium;" width="200">Aceptamos y pagaremos a su vencimiento</td>
	</tr>
	<tr>
		<td style="font-size: medium;" width="200">
			
				__________________________________<br>Organismo de certificación<br><br>Representante Legal

  			
  		</td>
		<td style="font-size: medium;" width="76" style="text-align:rigth;"><br><br>Empresa:<br>Nombre:</td>
		<td style="font-size: medium;" width="200">
			
				__________________________________<br><br><br>Representante Legal

  			
  		</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri','',9);
$html = "Debemos y pagaremos incondicionalmente por este Pagaré a la orden del organismo de certificación, en Ciudad de México en la fecha comprometida y por el monto que ha sido pactado en el contrato celebrado entre ambas partes.";
$pdf1->writeHTML($html, true, false, true, false, '');
// ANHADO la pagina del Anexo de ser necesaria
if($NoSitios > 1){
	$pdf1->AddPage();
	$pdf1->SetFont('Calibri', 'B', 14);
	$pdf1->SetTextColor(0,0,0);
	$html = '<div style="text-align:center;"><h3>Anexo 1 </h3></div>';
	$pdf1->writeHTML($html, true, false, true, false, '');
	$pdf1->SetFont('Calibri', '', 9);
$html = <<<EOT
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1"><strong>En caso de sitios adicionales debe describirlos para cada sitio lo siguiente;nombre </strong> (cuando aplique, en caso contrario indicar solamente direccion del sitio)<strong> y domicilio completo</strong></th>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:center" >Sitios</td>
		
	</tr>
	
EOT;
	for($i=0;$i<$NoSitios;$i++){
		$nnnn = $anexo1[$i]['NOMBRE'];
		$dddd = $anexo1[$i]['DOMICILIO'];
		$html .= <<<EOT

	<tr>
		
		<td style="font-size: medium;  text-align:left" >
			Nombre del sitio:  $nnnn<br>
			Domicilio: $dddd
		</td>
	</tr>
	

EOT;
	}
	$html .= <<<EOT

	
	
</table>
EOT;
	$pdf1->writeHTML($html, true, false, true, false, '');
	
}


//Close and output PDF document
$pdf1->Output();

// ---------------------------------------------------------
//$pdf->addPage();
//$pdf->Output();

//$pdf->addPage();
//$pdf->SetFont('Calibri','',9);
// Titulo de documento (centrado)
//$tipoNotificacionPDF = "Daniel Hernandez Barroso";
//$html = '<div style="text-align:center;"><h3>'.$tipoNotificacionPDF.' </h3></div>';
//$pdf1->WriteHTML($html, true, false, true, false, '');
//////////////////////////////////////////////////////////////////////////////////////////////

//$pdf->Output();


?>
