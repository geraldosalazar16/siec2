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
/*/////////////////////////////////////////////////////////////////////////*/
//Para obtener datos de la base de datos
/*/////////////////////////////////////////////////////////////////////////*/
//NOMBRE PROSPECTO
$prospectoall = $database->select("PROSPECTO","*",["ID"=>$id_prospecto]);


$where = "";
if($id_producto == 0 or is_null($id_producto))
{
	$nombrearea[0]["NOMBRE"] = "ID PRODUCTO 0";
    $where = "WHERE ID_PROSPECTO = ".$id_prospecto;
}
else
{
	// NOMBRE AREA
	$nombrearea = $database->select("PROSPECTO_PRODUCTO",["[><]AREAS"=>["PROSPECTO_PRODUCTO.ID_AREA"=>"ID"],"[><]PRODUCTOS"=>["PROSPECTO_PRODUCTO.ID_PRODUCTO"=>"ID"]],["AREAS.NOMBRE","PRODUCTOS.NOMBRE(PNOMBRE)"],["PROSPECTO_PRODUCTO.ID"=>$id_producto]);
    $where = "WHERE ID_PROSPECTO = ".$id_prospecto." AND ID_PRODUCTO = ".$id_producto;
}
$cant_cotizaciones = $database->query("SELECT COUNT(*) as cantidad FROM COTIZACION_RAPIDA ".$where)->fetchAll();
$cotizacion = 0;
if($cant_cotizaciones[0]["cantidad"] > 0)
    $cotizacion = $database->query("SELECT * FROM COTIZACION_RAPIDA ".$where)->fetchAll(); 
/////////////////////////////////////////////////////////////////////////////////////////////////
//CONTACTO
/////////////////////////////////////////////////////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////////////////////////	
//			Variables que ahi que imprimir
//print_r($cotizacion);
$name_prospecto = $prospectoall[0]["NOMBRE"];
$area = $nombrearea[0]["NOMBRE"];
$fecha = mes_esp(date('d/n/Y'));
$referencia_comercial = $cotizacion[0]["REFERENCIA"];//"001082017-01";
$direccion_contacto = "Calle ".$domicilio_cotizacion["CALLE"]." Exterior ".$domicilio_cotizacion["NUMERO_EXTERIOR"]." Interior ".$domicilio_cotizacion["NUMERO_INTERIOR"]." Colonia ".$domicilio_cotizacion["COLONIA"]." Delegacion ".$domicilio_cotizacion["MUNICIPIO"].",CP ".$domicilio_cotizacion["CODIGO_POSTAL"].", ".$domicilio_cotizacion["ESTADO"].", ".$domicilio_cotizacion["PAIS"];
$name_contacto = $contacto_cotizacion["NOMBRE"];
$cargo_contacto = $contacto_cotizacion["PUESTO"];
$telefono_contacto = $contacto_cotizacion["TELEFONO"];
$email = $contacto_cotizacion["CORREO"];
$norma = $nombrearea[0]["PNOMBRE"];
$NoEmpleados = $cotizacion[0]["NO_EMPLEADOS"];//"3";
$NoSitios = $cotizacion[0]["NO_SITIOS"];//"3";
$Importe_Certificado = $cotizacion[0]["IMPORTE_CERTIFICADO"];//"3";
$E1_Dias = $cotizacion[0]["DIAS_E1"];//"0";
$E2_Dias = $cotizacion[0]["DIAS_E2"];//"0";
$E1_Monto = $cotizacion[0]["MONTO_E1"];//"0,0.00";
$E2_Monto = $cotizacion[0]["MONTO_E2"];//"0,0.00";

$V1_Dias = $cotizacion[0]["DIAS_V1"];//"0";
$V1_Monto = $cotizacion[0]["MONTO_V1"];//"0,0.00";

$V2_Dias = $cotizacion[0]["DIAS_V2"];//"0";
$V2_Monto = $cotizacion[0]["MONTO_V2"];//"0,0.00";

/*/////////////////////////////////////////////////////////////////////////*/
$No1 = utf8_decode("Cotización ".$area);
$No2 = utf8_decode("Empresa: ".$name_prospecto);
$No3 = utf8_decode($fecha);
$No4 = utf8_decode($referencia_comercial);
$No5 = utf8_decode("Cotización de ".$area);
$No6 = utf8_decode($name_prospecto);
$No7 = utf8_decode($direccion_contacto);
$No8 = utf8_decode($name_contacto);
$No9 = utf8_decode($cargo_contacto);
$No10 = utf8_decode($telefono_contacto);
$No11 = utf8_decode($email);
$No12 = utf8_decode($norma);
$No13 = utf8_decode($NoEmpleados);
$No14 = utf8_decode($NoSitios);
$No15 = utf8_decode($E1_Dias);
$No16 = utf8_decode($E2_Dias);
$No17 = utf8_decode($E1_Monto);
$No18 = utf8_decode($E2_Monto);
$No22 = utf8_decode($Importe_Certificado);
$No19 = utf8_decode(doubleval($No17)+doubleval($No18)+doubleval($No22));
$No20 = utf8_decode(0.16*(doubleval($No19)));//$IVA_19;
$No21 = utf8_decode(doubleval($No19)+doubleval($No20));

$No23 = utf8_decode($V1_Dias);
$No24 = utf8_decode($V1_Monto);
$No25 = utf8_decode($V1_Monto);
$No26 = utf8_decode(0.16*$No25);//$IVA_25;
$No27 = utf8_decode(doubleval($No25)+doubleval($No26));
$No28 = utf8_decode($V2_Dias);
$No29 = utf8_decode($V2_Monto);
$No30 = utf8_decode($V2_Monto);
$No31 = utf8_decode(0.16*$No30);//$IVA_30;
$No32 = utf8_decode(doubleval($No30)+doubleval($No31));
$No33 = utf8_decode($fecha);
$NoN="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

/*/////////////////////////////////////////////////////////////////////////*/

$pdf = new Fpdi();
///////////////////////////////////////////
/*$pdf->AddFont('Calibri','','calibri.php');
$pdf->AddFont('Calibri','B','calibrib.php');
$pdf->AddFont('Calibri','I','calibrii.php');
$pdf->AddFont('Calibri','BI','calibribi.php');
$pdf->AddFont('Calibril','','calibril.php');*/
///////////////////////////////////////////

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
		$image_file = $this->selector . '/barra.jpg';
		$this->Image($image_file, 30, 10, 140, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$image_file1 = $this->selector . '/logob.jpg';
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
			Clave: FPEC23 <br>
			Fecha de aplicación: 2017-08-09 <br>
			Versión: 02 <br>
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
$No5= "Cotización de ".$area;
$No6 = $name_prospecto;
$No7 = $direccion_contacto;
$No8 = $name_contacto;
$No9 = $cargo_contacto;
$No10 = $telefono_contacto;
$No11 = $email;
$No12 = $norma;
$No13 = $NoEmpleados;
$No14 = $NoSitios;
//////////////////////////
// create new PDF document
//$pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$str_direccion="Daniel Hernandez Barroso";
$global_diffname="E:/xampp/htdocs/imnc/imnc/generar/pdf/cotizacion/";
// create new PDF document
$pdf1 = new MYPDF($No5, $str_direccion, $global_diffname, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$fontname = TCPDF_FONTS::addTTFfont('E:/xampp/htdocs/imnc/imnc/phplibs/libPDF/fonts/Calibri Bold Italic.ttf','TrueTypeUnicode','',96);
$pdf1->AddFont('Calibri','','calibri.php');
$pdf1->AddFont('Calibri','B','calibrib.php');
$pdf1->AddFont('Calibri','I','calibrii.php');
$pdf1->AddFont('Calibri','BI','calibribi.php');
$pdf1->AddFont('Calibril','','calibril.php');
// set document information
//$pdf1->SetCreator(PDF_CREATOR);
//$pdf1->SetAuthor('Nicola Asuni');
//$pdf1->SetTitle('TCPDF Example 021');
//$pdf1->SetSubject('TCPDF Tutorial');
//$pdf1->SetKeywords('TCPDF, PDF, example, test, guide');

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
$pdf1->Image('logob.jpg', 160, 10, 45, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

$pdf1->SetXY(0,0);
// Titulo de documento (centrado)
$html = '<br><br><br><br><div style="text-align:center;"><h3>Propuesta Económica </h3></div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibril','',18);
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
		<td style="font-size: medium;" width="350">$name_prospecto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Dirección:</td>
		<td style="font-size: medium;" width="350">$direccion_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Contacto:</td>
		<td style="font-size: medium;" width="350">$name_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Cargo:</td>
		<td style="font-size: medium;" width="350">$cargo_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Teléfono:</td>
		<td style="font-size: medium;" width="350">$telefono_contacto</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Email:</td>
		<td style="font-size: medium;" width="350">$email</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">Norma:</td>
		<td style="font-size: medium;" width="350">$norma</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:right" width="100">No. Empleados:</td>
		<td style="font-size: medium;" width="150">$NoEmpleados</td>
		<td style="font-size: medium; text-align:right" width="50">No. Sitios:</td>
		<td style="font-size: medium;" width="150">$NoSitios</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');

for($i=0;$i<4;$i++){
	if($i==3 || $i == 7 || $i==11){
		$pdf1->AddPage();
	}
	$html = <<<EOT
	<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
		<tr style="background-color: #1F487B;">
			<th style="font-size: medium; color:white;" colspan="1"><strong>SERVICIO DE AUDITORÍA DE CERTIFICACIÓN INICIAL</strong></th>
		</tr>
		<tr>
			<th style="font-size: medium;" width="225"><strong>Descripción del servicio</strong></th>
			<th style="font-size: medium; background-color: #D8E4F0;" width="100"><strong>Dias auditos requeridos</strong></th>
			<th style="font-size: medium;" width="125"><strong>Costo</strong></th>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="225">1. Auditoria Etapa 1</td>
			<td style="font-size: medium; background-color: #D8E4F0;" width="100"></td>
			<td style="font-size: medium; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="225">2. Auditoria Etapa 2</td>
			<td style="font-size: medium; background-color: #D8E4F0;" width="100"></td>
			<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="225">3. Emisión de certificado acreditado IMNC</td>
			<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
			<td style="font-size: medium; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Subtotal</strong></td>
			<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>I.V.A. 16%</strong></td>
			<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
		<tr>
			<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Total</strong></td>
			<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
			<td style="font-size: medium;" width="100"></td>
		</tr>
	</table>
EOT;
	$pdf1->writeHTML($html, true, false, true, false, '');

}
// add a page (ESTA ES LA TERCERA PAGINA)
$pdf1->AddPage();
$pdf1->SetFont('Calibri', 'B', 14);
$pdf1->SetTextColor(0,0,0);
$html = '<h3 > Propuesta económica/Cotización del servicio de certificación de sistemas
de gestión. </h3>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html = '<br><h3 > 1. Impuestos. </h3>';
$html .= ' Lo referente al I.V.A. o impuestos ya están incluidos en la presente Propuesta económica/Cotización.';
$html .= '<h3 > 2. Gastos de Servicio. </h3>';
$html .= ' La presente Propuesta económica/Cotización no incluye lo relacionado a gastos de servicio (Transportación, Alimentación y Hospedaje) y
tendrán que ser cubiertos por la empresa.<br>
En caso de que la empresa requiera hacerse cargo de los gastos de servicio para economizar recursos, tendrá que notificar al IMNC con al
menos 15 días naturales d e antelación al servicio programado.<br>
En el caso de que la Empresa requiera que el IMNC se haga cargo de los gastos, se cargará un 20% del valor de los mismos por gastos
administrativos.<br>
Cuando aplique los itinerarios de vuelos deberán ser aprobados por el IMNC antes de ser comprados. Es necesario que como máximo 5
días antes de la fecha de ejecución del servicio se envíen al IMNC los vuelos, hospedajes y transportación local.';
$html .= '<h3 > 3. Vigencia de la Propuesta económica/Cotización. </h3>';
$html .= ' La presente Propuesta económica/Cotización tiene una vigencia de 120 (ciento veinte) días naturales y contaran a partir de la fecha de
emisión.';
$html .= '<h3>4. Especificaciones Técnicas.</h3>';
$html .= 'El número de días auditor de esta Propuesta económica/Cotización, tiene como base las directrices del IAF (Foro Internacional de
Acreditación), norma ISO/IEC 17021-1:2015 documentos Mandatorios (MD´s) y Reglamentos complementarios aplicables. La presente
propuesta económica ha sido emitida con base a la información que el cliente ha hecho del conocimiento al IMNC, mediante la “Solicitud
de servicio de certificación” correspondiente.';
$html .= '<h3>4.1. Etapa 1 y Etapa 2.</h3>';
$html .= ' La Auditoría Etapa 1 se realizará en las instalaciones del cliente con el objetivo d e confirmar si el tiempo especificado en la presente
Propuesta económica/Cotización para conducir la auditoría Etapa 2, es suficiente. El IMNC se reserva el derecho de analizar si requerirá
generar una reducción, o un incremento del tiempo de auditoría cotizado inicialmente en la presente oferta. El tiempo mínimo entre
Etapa 1 y etapa 2 será de una semana; así mismo, el tiempo máximo entre estas dos etapas será de 6 meses';
$html .= '<h3>4.2 Auditoría de Vigilancia.</h3>';
$html .= ' Los servicios de Auditoría de Vigilancia se especifican en la presente Propuesta económica/Cotización sobre la base que deben realizarse
al menos una vez al año, excepto las auditorías de renovación y considerando que la auditoría de primera vigilancia no debe realizarse
transcurridos más de 12 meses después de la fecha de auditoría etapa 2.';
$html .= '<h3>4.3 Auditorías de Seguimiento.</h3>';
$html .= ' Si el IMNC requiere conducir Auditorías de Seguimiento en las instalaciones del cliente, con el objeto de verificar la implementación de las
acciones para dar atención a las no conformidades reportadas durante cualquier auditoría sobre la certificación, éstas tendrán un costo
de $10,000.00 pesos más I.V.A. por día/auditor. Si esta revisión se requiere hacer en instalaciones del IMNC, tendrá un costo de
$5,000.00 pesos por esta actividad.';
$html .= '<h3>4.3 Auditorías con notificación a corto plazo.</h3>';
$html .= ' El IMNC se reserva el derecho de tener que realizar auditorías a sus clientes certificados, bajo la forma de visita notificada a corto plazo,
con el fin de investigar quejas, o respuestas a cambios, o como seguimientos de clientes cuya certificación haya sido suspendida. Este tipo
de auditorías, no tendrán ningún costo para el cliente.';
$pdf1->writeHTML($html, true, false, true, false, '');

$pdf1->AddPage();
$html = '<h3>4.4 Certificado.</h3>';
$html .= ' La Vigencia del Certificado emitido por el IMNC, será de 3 años. Los certificados emitidos bajo una norma que se encuentre en periodo
de transición, quedarán obsoletos a partir de la fecha límite dada a conocer por el IMNC.<br>
La entrega del documento de certificación, se hará de 5 a 8 días hábiles posteriores a la aprobación del boceto.<br>
Cuando el cliente solicite su certificado en Placa, la entrega se hará en un plazo no mayor a los 20 días hábiles posteriores a la aprobación
del boceto y tendrá un costo de 10,000 pesos por cada placa.';
$html .= '<h3>4.5 Modificaciones al certificado.</h3>';
$html .= ' Si durante la vigencia de este contrato, surge la necesidad de hacer modificaciones al Certificado (por ejemplo: nombre o razón social de
la empresa, dirección, norma, alcance y número de sitios), tendrá un costo de $10,000.00 pesos más I.V.A.';
$html .= '<h3>4.6 Cambios en la organización del cliente.</h3>';
$html .= ' El Cliente se obliga a informar al IMNC dentro de los primeros 15 días posteriores a estos, que puedan afectar la capacidad de su sistema
de gestión certificado para continuar cumpliendo los requisitos de la norma utilizada para la certificación (por ejemplo: cambios en la
condición legal, comercial o de propiedad); la organización y la gestión (por ejemplo: personal clave como directivos, personal que toma
decisiones o personal técnico), cambio de domicilio y lugar de contacto; el alcance de las operaciones, procesos y productos cubiertos por
el sistema de gestión certificado. En tales casos el IMNC se reserva el derecho de determinar si es necesario conducir una auditoría
especial, y en su caso emitirá una nueva Propuesta económica/Cotización del servicio para el visto bueno del cliente.';
$html .= '<h3>4.7 Renovación</h3>';
$html .= ' Para renovar el certificado, se tendrá que realizar una auditoría de Renovación, y tendrá que ejecutarse preferentemente con una
antelación de 4 meses al vencimiento del certificado.<br>
En caso de que la Recertificación sea concluida después de la fecha del vencimiento del certificado, no se podrá conservar la fecha de
antigüedad del certificado de origen.';
$html .= '<h3>4.8 Testificación.</h3>';
$html .= ' En una testificación se evalúa al Organismo de Certificación por parte del Organismo de Acreditación ema durante la realización de una
auditoría practicada a un cliente. La ema en ningún caso estará evaluando al cliente y esto no afectará su auditoría.<br>
En caso de que el Organismo de Acreditación (ema) solicite que se haga una testificación durante alguna de sus auditorías, esta se deberá
realizar de acuerdo a lo solicitado. En caso de no poder recibir esta auditoría se deberá emitir una justificación, en caso de no ser
autorizada por la ema, o por el IMNC, se deberá recibir la auditoría de testificación.<br>
En caso de no cumplir con lo citado anteriormente, se podría proceder a la CANCELACIÓN DE SU CERTIFICADO con base en lo establecido
en el Documento Mandatorio IAF MD 17:2015 en su punto 2.2.2.';
$html .= '<h3>4.9</h3>';
$html .= ' En el caso de que algún elemento de los párrafos anteriores sea incumplido por parte del cliente, el IMNC podrá poner en proceso de
Suspensión la certificación; en caso de no atenderse los plazos y actividades marcadas en el plazo de suspensión, se procederá a la
Cancelación del Certificado del Cliente.';
$html .= '<h3>5. Cancelación.</h3>';
$html .= ' La cancelación de esta Propuesta económica/Cotización, podrá ser en cualquier momento por cualquiera de las partes, cuando se
presenten los siguientes supuestos:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a.  A solicitud del cliente, mediante un comunicado por escrito y con una antelación de 3 meses en relación a la fecha de inicio de
cualquiera de los servicios programados.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b.  Por no pagar las contraprestaciones correspondientes a los anticipos y/o a los servicios ejecutados por el IMNC.<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c.  Por incumplimiento de las obligaciones definidas a cargo de cada una de las partes en el presente documento.<br><br>
En caso de cancelación después del tiempo establecido en el inciso a, el cliente tendrá que pagar un monto de $10,000.00 pesos, con la
finalidad de cubrir gastos administrativos inherentes al cierre de la certificación.';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$html = '<h3>6. Programación y Logística.</h3>';
$html .= ' Para la programación y ejecución de las auditorías, es requisito indispensable que el cliente ingrese al IMNC la Propuesta
económica/Cotización aceptada.<br><br>
Las fechas para realizar las auditorías, serán programadas de común acuerdo con al menos 30 días naturales de antelación. En caso de
que la organización solicite la reprogramación y/o cancelación de su auditoría ya confirmada, dentro de los 15 días naturales de
antelación a la fecha de ejecución del servicio, el IMNC facturará el 25% del importe del servicio correspondiente por gastos
administrativos.<br><br>
En reprogramaciones por parte del cliente dentro de los 15 días naturales de antelación al servicio donde se requiera realizar
modificaciones al equipo auditor para cubrir el ejercicio de auditoría, el cliente absorberá los gastos correspondientes.<br><br>
El plan de auditoría se enviará al menos 15 días naturales a partir de la fecha de programada y confirmada, no así antes de los 5 días
naturales previos a la ejecución del servicio.';
$html .= '<h3>7. Facturación.</h3>';
$html .= ' El IMNC con una semana de antelación al servicio, emitirá un pedido (pre-factura) al cliente. El IMNC enviará la factura al cliente, máximo
5 días naturales posteriores a la terminación del mismo servicio, o antes en caso de haber confirmado el pedido o por solicitud expresa
del cliente.<br>
Toda re facturación tendrá un costo administrativo de $150.00 pesos adicional al monto de la factura, toda factura con adeudo mayor a
30 días tendrá un cargo del 5% de interés mensual tomando como referencia el monto total del adeudo de la factura, hasta la fecha de su
liquidación, si se rebasan los 45 días su adeudo será turnado al área legal.';
$html .= '<h3>8. Condiciones de pago.</h3>';
$html .= ' IMPORTANTE: Los pagos de los servicios serán con cheque empresarial, depósito bancario o transferencia bancaria a nombre del Instituto
Mexicano de Normalización y Certificación, A.C. a cualquiera de las cuentas siguientes:
1.  SCOTIABANK cuenta No. 001 006 90669 suc. 28 Sullivan Plaza 1 México, D.F., Para transferencia Inter. Bancaria CUENTA CLABE
044 18000 100690669 8 (tiene que solicitar en IMNC una referencia para pago en caja).
2.  BANAMEX cuenta No. 096530-8 suc. No. 224 San Rafael. Para transferencia Inter. Bancaria: CUENTA CLABE
002180022409653080.';
$html .= '<h3>9. Aceptación de la Propuesta económica/Cotización.</h3>';
$html .= ' Al firmar esta Propuesta económica/Cotización, está generando la aceptación de la Contratación de los servicios y su organización
manifiesta que ha leído, comprendido y acepta las Condiciones Generales para la Certificación de Sistemas de Gestión vigentes, que se
encuentran disponibles en la página del IMNC: http://www.imnc.org.mx “Condiciones de certificación”.<br>
En caso de aceptar la presente Propuesta económica/Cotización, favor de efectuar un pago mínimo del 50% sobre el monto total de cada
servicio y el 100% de los viáticos o Gastos adicionales en el momento de la programación de cada auditoría, como mínimo 5 días antes de
la ejecución de la auditoría, para tal efecto se deberá enviar por correo electrónico el comprobante de pago (ficha de depósito o
transferencia bancaria) a aavalos@imnc.org.mx, el 50% restante se cubrirá a la conclusión del servicio, en caso contrario se procederá
conforme a la Condiciones de Certificación vigentes. Para la emisión del dictamen y/o certificado se deberá contar con el comprobante de
pago.<br>
Aceptada la presente Propuesta económica/Cotización se deberá anexar copia simple de los siguientes documentos: acta constitutiva,
identificación del representante legal (en caso de que al representante legal no le sean conferidos poderes para firmar contratos en el
acta constitutiva, se requiere copia del testimonio notarial correspondiente), inscripción en el R.F.C. y comprobante de domicilio.<br>
Nota1: El envío de los documentos anteriormente citados y el pago anticipado NO aplica para sector público.';
$pdf1->writeHTML($html, true, false, true, false, '');
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