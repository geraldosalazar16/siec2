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
/*//////////////////////////////////////////////////////////////////////////*/
/*					CAPTURA E INICIALIZACION DE VARIABLES					*/
/*//////////////////////////////////////////////////////////////////////////*/
function valida_isset($variable, $mensaje){
	if (!isset($variable)) {
		print_r($mensaje);
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
$ruta = $global_apiserver.'/cotizaciones/getById/?id='.$id_cotizacion;
$cotizacion = file_get_contents($ruta);
$cotizacion = json_decode($cotizacion);

if($cotizacion[0]->BANDERA == 0){
	$id_cliente = $database->get("PROSPECTO", "ID_CLIENTE", ["ID"=>$cotizacion[0]->ID_PROSPECTO]);
	$cliente = $database->get("CLIENTES", "*", ["ID"=>$id_cliente]);
	$cotizacion[0]->CLIENTE = $cliente;
}


/////////////////////////////////////////////////////////////////////////////////////////////////
//CONTACTO
/////////////////////////////////////////////////////////////////////////////////////////////////
if($cotizacion[0]->BANDERA == 0){
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
if($cotizacion[0]->BANDERA == 1){
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
$area = "de Certificación de Igualdad Laboral y No Discriminación";//$cotizacion[0]['SERVICIO']["NOMBRE"];
$fecha = mes_esp(date('d/n/Y'));
$referencia_comercial = $cotizacion[0]->FOLIO;//$cotizacion[0]["REFERENCIA"];//"001082017-01";$referencia_comercial = $datos->folio;//
$referencia = $cotizacion[0]->REFERENCIA;
if($cotizacion[0]->BANDERA == 0){
	$direccion_contacto = "Calle ".$domicilio_cotizacion["CALLE"]." Exterior ".$domicilio_cotizacion["NUMERO_EXTERIOR"]." Interior ".$domicilio_cotizacion["NUMERO_INTERIOR"]." Colonia ".$domicilio_cotizacion["COLONIA"]." Delegacion ".$domicilio_cotizacion["MUNICIPIO"].",CP ".$domicilio_cotizacion["CODIGO_POSTAL"].", ".$domicilio_cotizacion["ESTADO"].", ".$domicilio_cotizacion["PAIS"];//$direccion_contacto="";//
	
	$name_contacto = $contacto_cotizacion["NOMBRE"];//$name_contacto="";//
	$cargo_contacto = $contacto_cotizacion["PUESTO"];//$cargo_contacto="";//
	$telefono_contacto = $contacto_cotizacion["TELEFONO"];//$telefono_contacto="";//
	$email = $contacto_cotizacion["CORREO"];//$email="";//
}
if($cotizacion[0]->BANDERA == 1){
	$direccion_contacto = "Calle ".$dom_cliente["CALLE"]." Exterior ".$dom_cliente["NUMERO_EXTERIOR"]." Interior ".$dom_cliente["NUMERO_INTERIOR"]." Colonia ".$dom_cliente["COLONIA_BARRIO"]." Delegacion ".$dom_cliente["DELEGACION_MUNICIPIO"].",CP ".$dom_cliente["CP"].", ".$dom_cliente["ENTIDAD_FEDERATIVA"].", ".$dom_cliente["PAIS"];//$direccion_contacto="";//
	
	$name_contacto = $cont_cliente["NOMBRE_CONTACTO"];//$name_contacto="";//
	$cargo_contacto = $cont_cliente["CARGO"];//$cargo_contacto="";//
	$telefono_contacto = $cont_cliente["TELEFONO_FIJO"];//$telefono_contacto="";//
	$email = $cont_cliente["EMAIL"];//$email="";//
}

/*=======================================================================================*/
//		AQUI VOY A BUSCAR LA CANTIDAD DE SITIOS Y DE EMPLEADOS TOTAL EN ESOS SITIOS
/*=======================================================================================*/
$tramites=$cotizacion[0]->COTIZACION_TRAMITES;
foreach ($tramites as $key => $tramite_item) {
		$ruta = $global_apiserver.'/cotizaciones_tramites_cil/getSitios/?id='.$tramite_item->ID.'&cotizacion='.$id_cotizacion;
		$obj_cotizacion_tramite[$key] = file_get_contents($ruta);
		$obj_cotizacion_tramite[$key] = json_decode($obj_cotizacion_tramite[$key]);
}	

/*=======================================================================================*/
$norma2 = $cotizacion[0]->NORMAS[0]->ID_NORMA;//$nombrearea[0]["PNOMBRE"];//$norma=$datos->norma;;//
$NoEmpleados = $suma_emple;//$NoEmpleados = $cotizacion[0]["NO_EMPLEADOS"];//"3";
$NoSitios ="3";//count($respuesta);//$NoSitios = $cotizacion[0]["NO_SITIOS"];//
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
		$this->Cell(0, 10, $this->certificacion, 0, false, 'L', 0, '', 0, false, 'M', 'M');
		// Logo
		$image_file = $this->selector . 'barra.jpg';
		$this->Image($image_file, 30, 10, 140, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$image_file1 = $this->selector . 'logob.jpg';
		$this->Image($image_file1, 175, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
		$this->SetFont('Helvetica', '', 9);

		// Lugar, fecha y claves (alineado a la derecha)
$html = <<<EOD
<table cellpadding="3" cellspacing="0" border="0">
	
	<tr>
		<hr>
		<td style="font-size: small; text-align:center;" width="400">
			Manuel Ma. Contreras 133 6º piso Col. Cuauhtémoc, Del. Cuauhtémoc C. P. 06500 CDMX<br>
			Lada sin costo: 01 800 201 0145 Teléfono: 5546 4546<br>
			Web <a>www.imnc.org.mx</a>
		</td>
		
		<td style="font-size: small; text-align:left;" width="115"> 
			Clave: FPPr41 <br>
			Fecha de aplicación: 2018-04-16 <br>
			Versión: 12 <br>
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

$No1="Cotización ".$area;
$No2="Empresa: ".$name_prospecto;
$No3=$fecha;
$No4 = $referencia_comercial;
$No5= "Cotización ".$area;
$No6 = $name_prospecto;

//////////////////////////
// create new PDF document
//$pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$str_direccion="Daniel Hernandez Barroso";
//$global_diffname="E:/xampp/htdocs/imnc/imnc/generar/pdf/cotizacion/";
//$global_diffname="";//$global_diffname="E:/xampp/htdocs/pruebasiec2/siec2/imnc/generar/pdf/cotizacion_propuesta_cil/";
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

// ---------------------------------------------------------

// set font
$pdf1->SetFont('helvetica', '', 25);
$pdf1->SetTextColor(54,95,145);
// add a page (ESTA ES LA PAGINA DE PORTADA)
$pdf1->SetPrintHeader(false);
$pdf1->AddPage();
$pdf1->SetPrintFooter(false);
$pdf1->Image($global_direccion_pdf.'logob.jpg', 160, 10, 45, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

$pdf1->SetXY(0,0);
// Titulo de documento (centrado)
$html = '<br><br><br><br><div style="text-align:center;"><h3>Propuesta Económica </h3></div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibril','',16);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 style="text-align:center;"> '.$No1.' </h3><br>';
//$html .= '</div>';
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
		<td style="font-size: medium; text-align:left" width="175"> $name_prospecto</td>
		<td style="font-size: medium; text-align:right" width="75">No. Solicitud SCPrIL-</td>
		<td style="font-size: medium; text-align:left" width="100"> $referencia_comercial</td>
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
	
</table>

EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
for($i=0;$i<count($datos);$i++){
	if($i!=0){
		$pdf1->AddPage();
	}
		$Titulo_Tabla="DATOS DEL SERVICIO";
		$ETAPA = $obj_cotizacion_tramite[$i]->ETAPA;
		$SITIOS_A_VISITAR=$obj_cotizacion_tramite[$i]->COUNT_SITIOS->SITIOS_A_VISITAR;
		$TOTAL_SITIOS = $obj_cotizacion_tramite[$i]->COUNT_SITIOS->TOTAL_SITIOS;
		$dias_auditor_E1 = $datos[$i]->DIAS_AUDITORIA;
		$costo_E1	=	$datos[$i]->TRAMITE_COSTO;
		$viaticos_E1 = $datos[$i]->VIATICOS;
		$total_emp_tramite = $cotizacion[0]->COTIZACION_TRAMITES[$i]->TOTAL_EMPLEADOS_TRAMITE;
		$personas_encuesta = $cotizacion[0]->COTIZACION_TRAMITES[$i]->PERSONAS_ENCUESTA;
		$dias_base_multisitio = $cotizacion[0]->COTIZACION_TRAMITES[$i]->DIAS_BASE + $cotizacion[0]->COTIZACION_TRAMITES[$i]->DIAS_MULTISITIO;
		$dias_encuesta	=	$cotizacion[0]->COTIZACION_TRAMITES[$i]->DIAS_ENCUESTA;
		$tarifa_dia_auditor = $cotizacion[0]->COTIZACION_TRAMITES[$i]->TARIFA_DES;
		$costo_dias_encuesta = $dias_encuesta*2000;
		$costo_dias_auditor = $dias_base_multisitio*$tarifa_dia_auditor;
		$tarifa_adicional = $cotizacion[0]->COTIZACION_TRAMITES[$i]->TARIFA_ADICIONAL;
		$viaticos = $cotizacion[0]->COTIZACION_TRAMITES[$i]->VIATICOS;
		$subtotal = $cotizacion[0]->COTIZACION_TRAMITES[$i]->TRAMITE_COSTO_TOTAL;
		$IVA = 0.16*$subtotal;//Aqui es necesario asegurarse que sea IVA 16%
		$Total=$subtotal+$IVA;
		$monto = $database->update("COTIZACIONES_TRAMITES_CIL", [
			"MONTO" => $Total
		], ["ID"=>$datos[$i]->ID]);
		//Dando formato a los datos
		$costo_dias_encuesta=number_format($costo_dias_encuesta,2);
		$tarifa_dia_auditor=number_format($tarifa_dia_auditor,2);
		$costo_dias_auditor=number_format($costo_dias_auditor,2);
		$tarifa_adicional=number_format($tarifa_adicional,2);
		$viaticos=number_format($viaticos,2);
		$subtotal=number_format($subtotal,2);
		$IVA=number_format($IVA,2);
		$Total=number_format($Total,2);

$html = <<<EOT
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1"><strong>DATOS DEL SERVICIO</strong></th>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Norma de Referencia:</td>
		<td style="font-size: medium;  text-align:left" width="350"> $norma2</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Alcance:</td>
		<td style="font-size: medium;  text-align:left" width="350"> </td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Servicio:</td>
		<td style="font-size: medium;  text-align:left" width="350">$ETAPA </td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Núm. Sitios:</td>
		<td style="font-size: medium;  text-align:left" width="150">$TOTAL_SITIOS </td>
		<td style="font-size: medium; text-align:right" width="50">Núm. de Sitios a muestrear:</td>
		<td style="font-size: medium;  text-align:left" width="150"> $SITIOS_A_VISITAR</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Ubicación del sitio donde se prestará el servicio (varios sitios ver tabla 1):</td>
		<td style="font-size: medium;  text-align:left" width="350"> $direccion_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">No. Empleados:</td>
		<td style="font-size: medium;  text-align:left" width="150">$total_emp_tramite </td>
		<td style="font-size: medium; text-align:right" width="125">Tamaño de la muestra para la aplicacion del Instrumento:</td>
		<td style="font-size: medium;  text-align:left" width="75">$personas_encuesta </td>
	</tr>
</table>
<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1"><strong>Costos de las etapas del proceso</strong></th>
	</tr>
	<tr style="background-color: #1F487B;">
		<th style="font-size: medium; color:white;" colspan="1" width="150"><strong>Descripción de las etapas del proceso</strong></th>
		<th style="font-size: medium; color:white;" colspan="1" width="100"><strong>Días Auditor Requeridos (a)</strong></th>
		<th style="font-size: medium; color:white;" colspan="1" width="100"><strong>Costo por día auditor (b)</strong></th>
		<th style="font-size: medium; color:white;" colspan="1" width="100"><strong>Precio de la etapa (c=a*b)</strong></th>
	</tr>	
	<tr>
		<td style="font-size: medium; text-align:left" width="150">Aplicación del Instrumento de medición de percepciones</td>
		<td style="font-size: medium;  text-align:center" width="100">$dias_encuesta</td>
		<td style="font-size: medium;  text-align:center" width="100"> 2,000.00 $ </td>
		<td style="font-size: medium;  text-align:center" width="100">$ $costo_dias_encuesta</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="150">Evaluación en Sitio</td>
		<td style="font-size: medium;  text-align:center" width="100">$dias_base_multisitio</td>
		<td style="font-size: medium;  text-align:center" width="100"> $tarifa_dia_auditor $ </td>
		<td style="font-size: medium;  text-align:center" width="100">$ $costo_dias_auditor</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="350">Emisión de Certificado</td>
		<td style="font-size: medium;  text-align:center" width="100">$ $tarifa_adicional</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="250">Copias de Certificado sin acrílico</td>
		<td style="font-size: medium;  text-align:center" width="100"> MXP $ </td>
		<td style="font-size: medium;  text-align:center" width="100"> </td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="250">Copias de Certificado con acrílico</td>
		<td style="font-size: medium;  text-align:center" width="100"> MXP $ </td>
		<td style="font-size: medium;  text-align:center" width="100"> </td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="350">Viáticos</td>
		<td style="font-size: medium;  text-align:center" width="100">$ $viaticos</td>
	</tr>
	<tr>
		<td style="font-size: medium;  text-align:right" width="350"> Subtotal </td>
		<td style="font-size: medium;  text-align:center" width="100">$ $subtotal</td>
	</tr>
	<tr>
		<td style="font-size: medium;  text-align:rigt" width="350"> IVA </td>
		<td style="font-size: medium;  text-align:center" width="100">$ $IVA </td>
	</tr>
	<tr>
		<td style="font-size: medium;  text-align:right" width="350"> Total </td>
		<td style="font-size: medium;  text-align:center" width="100">$ $Total </td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');	
	
	if($TOTAL_SITIOS>1){
$html = <<<EOT
<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1"><strong>SITIOS COMPRENDIDOS DENTRO DEL ALCANCE (TABLA 1)</strong></th>
	</tr>	
	<tr style="background-color: #1F487B;">
		<th style="font-size: large; color:white;" colspan="1" width="100"><strong>Sitio</strong></th>
		<th style="font-size: large; color:white;" colspan="1" width="350"><strong>Ubicación</strong></th>
	</tr>

EOT;
	for($m = 1; $m < $TOTAL_SITIOS; $m++){
		//
		if($cotizacion[0]->BANDERA == 0){
			
		/////////////////////////////////////////////////////////////////////////////////////////////////
		//DOMICILIO
		/////////////////////////////////////////////////////////////////////////////////////////////////
			$domicilio_sitio_m =  $database->get("PROSPECTO_DOMICILIO" , 
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
				, ["PROSPECTO_DOMICILIO.ID"=>$obj_cotizacion_tramite[$i]->COUNT_SITIOS->SITIOS_ID[$m]->ID_DOMICILIO_SITIO]);
			valida_error_medoo_and_die();	
			$direccion_sitio_m = "Calle ".$domicilio_sitio_m["CALLE"]." Exterior ".$domicilio_sitio_m["NUMERO_EXTERIOR"]." Interior ".$domicilio_sitio_m["NUMERO_INTERIOR"]." Colonia ".$domicilio_sitio_m["COLONIA"]." Delegacion ".$domicilio_sitio_m["MUNICIPIO"].",CP ".$domicilio_sitio_m["CODIGO_POSTAL"].", ".$domicilio_sitio_m["ESTADO"].", ".$domicilio_sitio_m["PAIS"];
			$nombre_sitio_m=$domicilio_sitio_m["NOMBRE"];
		}
		if($cotizacion[0]->BANDERA == 1){
		//NOMBRE ClIENTES

			$dom_sitio_m = $database->get("CLIENTES_DOMICILIOS", "*", ["ID"=>$obj_cotizacion_tramite[$i]->COUNT_SITIOS->SITIOS_ID[$m]->ID_DOMICILIO_SITIO]);
			valida_error_medoo_and_die();
			
			$direccion_sitio_m = "Calle ".$dom_sitio_m["CALLE"]." Exterior ".$dom_sitio_m["NUMERO_EXTERIOR"]." Interior ".$dom_sitio_m["NUMERO_INTERIOR"]." Colonia ".$dom_sitio_m["COLONIA_BARRIO"]." Delegacion ".$dom_sitio_m["DELEGACION_MUNICIPIO"].",CP ".$dom_sitio_m["CP"].", ".$dom_sitio_m["ENTIDAD_FEDERATIVA"].", ".$dom_sitio_m["PAIS"];
			$nombre_sitio_m=$dom_sitio_m["NOMBRE"];
		}
		$html .= <<<EOT
		
	<tr>
		<td style="font-size: medium;  text-align:left" width="100"> $nombre_sitio_m </td>
		<td style="font-size: medium;  text-align:left" width="350"> $direccion_sitio_m </td>
		
	</tr>
	
EOT;
	}
$html .= <<<EOT
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');

	}	
}
// add a page (ESTA ES LA TERCERA PAGINA)
$pdf1->AddPage();
$pdf1->SetTextColor(0,0,0);
$pdf1->SetFont('Calibri', '', 9);
$html =' Los pagos de los servicios cotizados serán 50% antes de realizar el servicio y el 50% restante el finalizar el servicio y serán con cheque empresarial o depósito bancario a nombre del Instituto Mexicano de Normalización y Certificación, A. C. En el caso del Gobierno Federal no aplica ya que se consideran sus Políticas de pagos, los depósitos se realizarán a las cuentas siguientes:';
$html .= '<br><br>1.- <b>BANAMEX</b>  cuenta No. 096530-8 Suc. San Rafael No. 224. Para transferencia Inter. Bancaria: CLAVE BANCARIA ESTÁNDAR (CLABE) 002180022409653080';
$html .= ' Lo referente al I.V.A. o impuestos ya están incluidos en la presente Propuesta económica/Cotización.';
$html .= '<br><br>2.- <b>SCOTIABANK</b> cuenta No. 001 006 90669 Suc. 28 Sullivan Plaza I México, D.F. Para transferencia Inter. Bancaria: CLABE BANCARIA ESTANDAR (CLABE) 044 18000 100690669 8';
$html .= '<br><br> Si la presente cotización es aceptada y desea los servicios del IMNC, favor de firmarla en el espacio correspondiente, envíela nuevamente al IMNC (anexe copia simple de los siguientes documentos: acta constitutiva, identificación del representante legal, comprobante de domicilio y RFC). ';
$html .= '<br><br><b>Nota:</b> A excepción de la cotización aceptada, el envío de los documentos anteriores y el pago anticipado no aplica para sector público.';
$html .= '<br><br><b>Al firmar esta propuesta económica la organización manifiesta que ha leído, comprendido y acepta las Condiciones Generales para la Certificación de las Prácticas de Igualdad Laboral vigentes.</b>';
$html .= '<br><br><b>En caso de que se lleve parte del servicio de evaluación y la Organización decida que no se continuará con el resto de la evaluación se obliga a pagar la parte del servicio efectuado. </b>';
$html .= '<br><br><br>En espera de vernos favorecidos con su preferencia, quedo de usted.<br><br><br>
';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri','B',16);
$html = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Ciudad de México, a</strong><br><br>";
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri','',10);
$html = <<<EOT
<table cellpadding="15" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium;" width="219"></td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="219">Aceptamos y pagaremos a su vencimiento</td>
	</tr>
	<tr>
		<td style="font-size: medium;" width="219">
			
				____________________________________<br>IMNC<br><br>Representante Legal

  			
  		</td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="219">
			
				____________________________________<br>Empresa<br>Nombre<br>Representante Legal

  			
  		</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri','',8);
$html = "Debemos y pagaremos incondicionalmente por este Pagaré a la orden del Instituto Mexicano de Normalización y Certificación, A.C. en Ciudad de México en la fecha comprometida y por el monto que ha sido pactado en el contrato / pedido / contrato celebrado entre ambas partes. Valor recibido a entera satisfacción del Instituto Mexicano de Normalización y Certificación, A.C.<br><br>
";
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = <<<EOT
<table cellpadding="8" border="0" width="500">
	<tr>
		<td width="80">
			<b>Generalidades</b>
			
		</td>
		<td width="420">
			<b> Requisitos de participación</b>
<br> La Organización está sujeta a cumplir los requisitos de certificación y a demostrar que tiene las prácticas de igualdad implementadas. 

<br><br><b><i> No podrá iniciar proceso de certificación el centro de trabajo que no presente al IMNC la documentación oficial emitida por la Secretaría del Trabajo y Previsión Social, directamente de la Procuraduría Federal de la Defensa del Trabajo (PROFEDET) y por el Consejo Nacional para Prevenir la Discriminación con antigüedad máxima de seis meses en donde conste que no existe algún incumplimiento a la Ley Federal del Trabajo y a la Ley Federal para Prevenir y Eliminar la Discriminación (quejas procedentes).</i></b>

<br><br> Pueden participar organizaciones e instituciones públicas, privadas y sociales que generan empleo y cuentan con una plantilla de personal independiente del tipo de contrato. Para Organizaciones multisitios se deberá demostrar previamente que los procesos de capacitación, de contratación y reclutamiento de personal se realizan desde un área central.  Todos los sitios deben contar con un vínculo legal o contractual con la oficina central de la organización y estar sometidos a prácticas comunes, la oficina central tiene derecho a solicitar a los sitios la implementación de acciones correctivas cuando sea necesario en cualquier sitio. 

<br><br> Los centros de trabajo podrán realizar un diagnóstico de autoevaluación, que está disponible en el portal www.stps.gob.mx a fin de realizar un chequeo sobre los requisitos de la Norma Mexicana.

<br><br> La Organización debe enviar una carta compromiso al Consejo Interinstitucional, en hoja membretada-el formato se encuentra en la NMX-R-025-SCFI-2015. En este documento se manifestará el interés en obtener la certificación, asumiendo la responsabilidad de cumplir con los requisitos establecidos de la NMX-R-025-SCFI-2015, y deberá estar firmado por la máxima autoridad, alta dirección o representante legal.

		</td>
	</tr>
	<tr>
		<td width="80">
			<b>Confidencialidad</b>
		</td>
		<td width="420">
			El IMNC es responsable, a través de compromisos de cumplimiento legal, de la gestión de toda la información obtenida o creada durante el desempeño de las actividades de certificación. Con excepción de la información que el cliente pone a disposición del público, o cuando existe acuerdo entre el IMNC y el cliente (por ejemplo, con fines de responder a las quejas), toda otra información se considera información privada y se considera confidencial. El IMNC debe informar al cliente, con anticipación, acerca de la información que pretende poner a disposición del público.
<br> Cuando se exige al IMNC, por ley o autorización de las disposiciones contractuales, la divulgación de información confidencial, se notificará al cliente o personal implicada la información proporcionada salvo que esté prohibido por ley.
La información relativa al cliente, obtenida de fuentes distintas al cliente (por ejemplo, de una queja o de autoridades reglamentarias), será tratada como información confidencial.
		</td>
	</tr>
	<tr>
		<td width="80">
			<b>Cambio</b>
		</td>
		<td width="420">
			Cuando existan cambios en los requisitos de certificación sean nuevos o por resultados de revisiones y que afecten las prácticas de Igualdad Laboral, el IMNC se asegurará de los cambios sean comunicados oportunamente a la Organización. El IMNC verificará la implementación de los cambios por parte de la Organización mediante el proceso de evaluación correspondiente en el esquema de certificación.
<br>br> Cualquier cambio que afecte la capacidad de la Organización para cumplir con los requisitos de certificación debe ser informado al IMNC oportunamente. Los cambios pueden incluir: cambios en condiciones legales, cambios de directivos o personal que toma decisiones, cambios de dirección o datos generales, entre otros.  Cuando la Organización decide cambios en sus prácticas de igualdad  laboral que afectan a la certificación, el IMNC podrá verificar la implementación de los cambios de acuerdo al impacto,  según se requiera, se realizará una nueva evaluación y/o la emisión de documentación formal de certificación. 

<br><br>La Organización debe hacer declaraciones precisas acerca del alcance de la certificación y no utilizar la certificación de tal manera que ocasione mala reputación,  se use con fines engañosos o no autorizados por el IMNC. En medios de comunicación tales como folletos o publicidad, la Organización está obligada a notificar su uso al IMNC e implementar las acciones definidas en los Lineamientos de Uso de Marca del IMNC y en el Manual de Identidad Grafica que emite la STPS. 
<br><br>En caso de que la Organización haya sido declarada por el IMNC como suspendida, se le retire el certificado o se haya finalizado la certificación, deberá dejar de ostentar todo tipo de material publicitario que haga referencia a la Marca de Igualdad Laboral o a la Certificación y deberá emprender las acciones exigidas por el Comité de Dictaminación acerca del uso de los documentos relacionados con el certificado. 
<br><br>Cuando la Organización certificada desea suministrar copias de su certificado a terceros puede solicitar copias originales autógrafas al IMNC (las copias son controladas). Para evitar situaciones engañosas o dolosas, en caso de que la Organización lo requiera,  se deben entregar copias completas del certificado (ambas caras) a terceros.
<br><br>La Organización está obligada a guardar un registro de todas las quejas (denuncias) emitidas por su personal independientemente del tipo de contrato, tomar las acciones pertinentes y documentar las acciones realizadas. La verificación de este tema se realizará mediante el proceso de evaluación correspondiente en el esquema de certificación. 
		</td>
	</tr>
</table>
EOT;

$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = <<<EOT
<table cellpadding="8" border="0" width="500">
	<tr>
		<td width="80">
			<b>1.- Aplicación del cuestionario de percepción de clima laboral y no discriminación</b>
			
		</td>
		<td width="420">
			De común acuerdo entre el/la cliente y el IMNC programarán la fecha para la aplicación del<i> cuestionario de percepción de clima laboral y no discriminación y de la auditoría en sitio, lo anterior</i> en función del tiempo establecido en la presente propuesta técnica.
<br><br> El IMNC designará a los/las integrantes del equipo de auditor y a la persona responsable de la aplicación de los cuestionarios, dicha designación se envía a la organización mediante una notificación que incluye los nombres de los/las integrantes del equipo auditor.  

<br><br>Previo a la fecha de ejecución de la auditoría en sitio, la organización debe proporcionar al IMNC los siguientes datos de la totalidad de su plantilla laboral (todo el personal que participa en alcance sin importar el tipo de contrato): nombre, cargo y sólo para aplicación electrónica se requiere de un correo electrónico. Esta información es confidencial y sólo el personal autorizado en el proceso tendrá acceso a ella, se utiliza sólo  para determinar la muestra para la aplicación del cuestionario.

<br><br> El cuestionario se aplicará de manera individual de acuerdo con la siguiente fórmula para la muestra: 
<br>
<br>
<br>
<br>
<br>
<br><br> Para certificaciones iniciales, actualizaciones, ampliaciones y recertificaciones se utilizará un nivel de confianza del 95% mientras que el margen de error será de 5% 
<br><br> Para mantenimientos o vigilancias se utilizará un nivel de confianza del 90% mientras que el margen de error será del 10%
<br><br> Cabe precisar que la conformación de la muestra, como la aplicación del instrumento, es responsabilidad del IMNC.
<br><br> Existen diversas formas para aplicar el cuestionario: 
<br> - Por medio de cuestionarios que se envía directamente a la persona seleccionada a través de correo electrónico
<br> - Por medio de cuestionarios impresos, en tal caso, la organización debe proveer un espacio adecuado para el propósito.
<br> - Una combinación de las anteriores, en caso necesario.
<br><br> En cualquiera de los medios elegidos, la organización debe garantizar que el personal esta familiarizado con el tema o los conceptos relacionados. El cuestionario deberá llenarse completamente, ser contestado de manera individual y asegurar el anonimato del personal. 
<br><br> El IMNC se encargará de capturar los resultados de la aplicación del cuestionario en la herramienta informática que para los fines ha creado.
<br><br> Se le entregará al cliente(a) vía electrónica un informe con los resultados de la aplicación del cuestionario. Este informe deberá tenerse como elemento de entrada para la auditoria en sitio. 
		</td>
	</tr>
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->Image($global_direccion_pdf.'Formula1.jpg', 50, 95, 100, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
$pdf1->AddPage();
$html = <<<EOT
<table cellpadding="8" border="0" width="500">
	<tr>
		<td width="80">
			<b>2.-Evaluación  en sitio</b>
			
		</td>
		<td width="420">
			El/la líder del equipo auditor enviará a la organización el plan de auditoría, previó a la evaluación en sitio.  El cliente revisará el plan de auditoría con el objeto de expresar oportunamente cualquier comentario al líder del equipo auditor.
<br><br> En las fechas acordadas se presentará el equipo auditor a las instalaciones de la organización para verificar si las prácticas de igualdad laboral demuestran conformidad con la NMX-R-025-SCFI-2015.  

<br><br> La evaluación inicia con una reunión de apertura en la cual el/la líder explica los detalles y procedimientos bajo los cuales se llevará a cabo la auditoría. Normalmente a la reunión asisten directivos y personal encargado de la Gestión de Recursos Humanos, pero no está limitado a que otras personas sean activas del proceso, de tal forma que la organización, puede designar al personal que considere necesario para esta reunión. Personas integrantes del Consejo Interinstitucional podrán a acudir a presenciar las auditorías de certificación que a sus intereses convenga. La notificación al centro de trabajo de la asistencia de observadores/as se realizará en un plazo de 3 días anteriores a la fecha de recibir la auditoría.

<br><br> El/la cliente debe proporcionar acceso a todos los registros, manuales, procedimientos y demás información que sirva como evidencia para constatar el cumplimiento de los requisitos de la norma de referencia.

<br><br> En caso de estar presentes durante la evaluación asesores o consultores de la organización, estarán  limitados al papel de “observadores”.

<br><br> Durante la evaluación el/la auditado(a) muestra evidencia del cumplimiento de los criterios que establece la norma. Adicionalmente, se hace un recorrido a las instalaciones para evaluar los requisitos de “Accesibilidad y ergonomía” definidos en el apartado 5.3.3.6.1 de la norma. Durante este recorrido se entrevistará a personal de la organización, haciendo hincapié en mujeres en estado de lactancia, mujeres en estado de embarazo, adultos/as mayores de 60 años y personal con algún tipo de discapacidad. Se revisaran los mecanismos de difusión de las políticas, los programas o las acciones enfocadas a promover la equidad y la igualdad entre mujeres y hombres.
<br><br> La calificación mínima para obtener el certificado es de 70 puntos, dentro de los cuales necesariamente deben estar incluidos los 30 puntos que aportan los requisitos críticos. Cuando un centro de trabajo renueva su certificado es necesario que el proceso de mejora continua se refleje en un aumento del 10 % en la calificación final obtenida en la anterior auditoría. 
<br><br> Todas las evidencias presentadas para el cumplimiento de los requisitos de certificación, deberán tener una antigüedad máxima de 12 meses al momento de la aplicación de la auditoría de certificación o de vigilancia.
<br><br> En caso de no estar de acuerdo con los hallazgos o no conformidades del informe, el/la cliente podrá demostrar mediante evidencia objetiva su desacuerdo. El grupo técnico en evaluación de la conformidad revisará y valorará esta evidencia para cerrar la no conformidad y proceder al momento a modificar el informe de evaluación. 

<br><br> La evaluación en sitio termina con una reunión de cierre en la cual el/la líder explica los detalles y pormenores del ejercicio de evaluación. El/la auditor(a) líder debe entregar al cliente un ejemplar del informe de la evaluación en sitio, e integrar otro ejemplar del informe al expediente del servicio.
<br><br> Posterior a la auditoria, el IMNC podrá revisar el expediente que presenta el auditor/a, en caso de encontrarse necesario, se solicitará  mayor evidencia a la organización por lo que existe la posibilidad de emitir un informe complementario. 

		</td>
	</tr>
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = <<<EOT
<table cellpadding="8" border="0" width="500">
	<tr>
		<td width="80">
			<b>3.- Decisión sobre la dictaminación </b>
			
		</td>
		<td width="420">
			En esta etapa el Comité de Dictaminación del IMNC recibe el expediente del servicio para la revisión de su cumplimiento con base en las Condiciones de Certificación de Igualdad Laboral, así como valoración de los resultados de acuerdo con la norma de referencia.

<br><br> Como resultado de esta etapa, se emite un Dictamen donde se registra la Decisión del Comité, la cual puede ser en cualquiera de los siguientes términos:

<br><br> •	Otorgar la certificación;
<br> •	Requerir visita de seguimiento;
<br> •	No se otorga la certificación 
<br> •	Cualquier otra decisión que considere pertinente

<br><br> El resultado del dictamen se da a conocer al la/el cliente. Cuando el dictamen ha sido positivo, se envía un boceto de certificado para que el cliente de su Vo.Bo. o comentarios (aplica sólo para certificación o recertificación). Toda vez que el boceto del certificado haya sido aceptado por el cliente, el IMNC le entregará,  en un plazo no mayor a 10 días hábiles,  el <b>Certificado de cumplimiento con la norma mexicana NMX-R-025-SCFI-2015</b>.
		</td>
	</tr>
	<tr>
		<td width="80">
			<b>4. Mantenimiento de la certificación </b>
			
		</td>
		<td width="420">
			<b>La entrega del certificado y el dictamen está sujeta a pago por parte del cliente o lo que indique el contrato.</b>
			

<br><br> La certificación tiene una vigencia de cuatro años a partir de la fecha de dictaminación.

<br><br> De acuerdo con lo establecido en la norma NMX-R-025-SCFI-2015, toda organización certificada debe demostrar el mantenimiento de las condiciones que propiciaron su certificación, por lo que, se realizará evaluación de vigilancia bianual.
<br><br> Para la evaluación de vigilancia bianual se lleva a cabo nuevamente la aplicación de las etapas 1, 2 y 3 de este proceso, por lo que  debe programarse para su ejecución antes de que se cumpla un año a partir de la fecha en que se otorgó la dictaminación.
<br><br> En la auditoría de vigilancia, además de demostrar el mantenimiento de la implementación de los requisitos,  el centro de trabajo deberá demostrar el cumplimiento del 100 % de los puntos críticos y solventar como mínimo un 70 % las áreas de oportunidad detectadas en la auditoría de certificación, así como aplicar el cuestionario de clima laboral.
<br><br> El IMNC notifica a la organización de forma anticipada que deberá ejecutarse la vigilancia  60 días naturales en conformidad a la fecha del certificado. 
<br><br> Si el centro de trabajo muestra interés en la auditoria de vigilancia ésta deberá estar preparada con sus evidencias para demostrar el mantenimiento de los requisitos. 
<br><br> Si el centro de trabajo no acepta aplicar la auditoría de vigilancia,  el IMNC comunicará la suspensión de la certificación a la organización y al Consejo Interinstitucional. Si en un plazo de no más de 60 días naturales no se realiza la vigilancia, el Organismo cancelará la certificación. 
<br><br> Como resultado de la dictaminación de una evaluación de vigilancia, se puede tener cualquiera de las siguientes decisiones sobre la certificación:
<br><br> •	Mantener la certificación;
<br> •	Requerir visita de seguimiento;
<br> •	Suspender la certificación;
<br> •	Retirar la certificación.
<br><br> Este Dictamen es entregado al cliente para su conocimiento y, en su caso, atención de las acciones solicitadas por visita de seguimiento.
		</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = <<<EOT
<table cellpadding="8" border="0" width="500">
	<tr>
		<td width="80">
			<b>5. Recertificación </b>
			
		</td>
		<td width="420">
			Al menos 60 días naturales  antes de la fecha en que concluya la vigencia de los certificados, los centros de trabajo interesados podrán solicitar una recertificación.   

<br><br> Una vez trascurridos los cuatro años de vigencia del certificado, los centros de trabajo que se recertifiquen, deberán reportar al Consejo Interinstitucional la obtención del nuevo certificado en la Norma Mexicana NMX-R-025-SCFI-2015 en Igualdad Laboral y No Discriminación, enviando una copia del mismo dentro de los 30 días naturales a partir de la fecha de su expedición, a fin de tener derecho del uso de la marca y formar parte de las estadísticas oficiales, comunicaciones y eventos relacionados con este instrumento.


		</td>
	</tr>
	
</table>
EOT;

$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('helvetica','',9);
$html ="Los datos personales proporcionados por la organización, serán tratados conforme a la Ley Federal de Protección de Datos Personales en Posesión de los Particulares vigente";
$pdf1->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------
// ---------------------------------------------------------


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
