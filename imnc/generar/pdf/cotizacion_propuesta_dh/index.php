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
//valida_parametro_and_die($id_prospecto,"Es necesario seleccionar un prospecto");
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
$area = "de los Servicios de Verificación de Distintivo H";//$cotizacion[0]['SERVICIO']["NOMBRE"];
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
		$ruta = $global_apiserver.'/cotizaciones_tramites_dh/getSitios/?id='.$tramite_item->ID.'&cotizacion='.$id_cotizacion;
		$obj_cotizacion_tramite[$key] = file_get_contents($ruta);
		$obj_cotizacion_tramite[$key] = json_decode($obj_cotizacion_tramite[$key]);
}	

/*=======================================================================================*/
//aqui concateno las normas
$norma2 = $cotizacion[0]->NORMAS[0]->ID_NORMA;//$nombrearea[0]["PNOMBRE"];//$norma=$datos->norma;;//
for($z=1;$z<count($cotizacion[0]->NORMAS);$z++){
	$norma2 .= ";".$cotizacion[0]->NORMAS[$z]->ID_NORMA;
}

$cant_personas = $obj_cotizacion_tramite[0]->COTIZACION_SITIOS[0]->CANTIDAD_PERSONAS;//$NoEmpleados = $cotizacion[0]["NO_EMPLEADOS"];//"3";
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
			Clave: FPDH18 <br>
			Fecha de aplicación: 2018-04-16 <br>
			Versión: 01 <br>
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
		<td style="font-size: medium; text-align:right" width="100">Alcance:</td>
		<td style="font-size: medium;  text-align:left" width="350"> Verificación Distintivo H</td>
		
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');

for($i=0;$i<count($datos);$i++){
	if($i==3 || $i == 7 || $i==11){
		$pdf1->AddPage();
	}
		$Titulo_Tabla="SERVICIO DE AUDITORÍA DE ".strtoupper($datos[$i]->TIPO);
		

		$Descripcion_servicio=$datos[$i]->TIPO;
		$costo	=	$datos[$i]->TRAMITE_COSTO_DES;
		$viaticos = $datos[$i]->VIATICOS;
		$total_dias_auditoria = $cotizacion[0]->COTIZACION_TRAMITES[$i]->DIAS_AUDITORIA;
		$html = <<<EOT
		<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
			<tr style="background-color: #1F487B;">
				<th style="font-size: medium; color:white;" colspan="1"><strong>$Titulo_Tabla</strong></th>
			</tr>
			<tr>
				<th style="font-size: medium;" width="225"><strong>Descripción del servicio</strong></th>
				<th style="font-size: medium; background-color: #D8E4F0;" width="100"><strong>Dias auditor requeridos</strong></th>
				<th style="font-size: medium;" width="125"><strong>Costo</strong></th>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="225">Visita de verificación (solicitud SVDH-17002)</td>
				<td style="font-size: medium; background-color: #D8E4F0;" width="100">$total_dias_auditoria</td>
				<td style="font-size: medium; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$costo</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="225">Visita de seguimiento* </td>
				<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
				<td style="font-size: medium; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100"></td>
			</tr>
			
EOT;
			$tarifa=0;
			$suma_tarifa=0;
			if(count($datos1[$i])>0){
				for($j=0;$j<count($datos1[$i]);$j++){
				$descripcion = $datos1[$i][$j]->DESCRIPCION;
				$tarifa	=	$datos1[$i][$j]->TARIFA;
				$suma_tarifa += $tarifa;
				$html .= <<<EOT
						<tr>
							<td style="font-size: medium; text-align:right; color:#5779A3" width="225">$descripcion</td>
							<td style="font-size: medium; background-color: #D8E4F0;" width="100">N/A</td>
							<td style="font-size: medium; color:#5779A3" width="25">$</td>
							<td style="font-size: medium;" width="100">$tarifa</td>
						</tr>
EOT;
			}}		
			$subtotal=$costo+$suma_tarifa+$viaticos;
			$IVA16=0.16*$subtotal;
			$total=$subtotal+$IVA16;
			$html .= <<<EOT
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325">Viáticos</td>
				<td style="font-size: medium;  color:#5779A3" width="25">$</td>
				<td style="font-size: medium;  text-align:center" width="100">$viaticos</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Subtotal</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$subtotal</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>I.V.A. 16%</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$IVA16</td>
			</tr>
			<tr>
				<td style="font-size: medium; text-align:right; color:#5779A3" width="325"><strong>Total</strong></td>
				<td style="font-size: medium; background-color: #D8E4F0; color:#5779A3" width="25">$</td>
				<td style="font-size: medium;" width="100">$total</td>
			</tr>
			<tr>
				<td style="font-size: small; text-align:center; " width="450">* Solo aplica a los establecimientos que hayan presentado no conformidades y se requiere verificar las acciones correctivas efectuadas.</td>
			</tr>
		</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');

	
	
	
}
// add a page (ESTA ES LA TERCERA PAGINA)
$pdf1->AddPage();
$pdf1->SetTextColor(0,0,0);
$pdf1->SetFont('Calibri', '', 9);
$html =' La presente cotización tiene vigencia de 60 días. Si es aceptada y desea los servicios del IMNC, favor de enviar copia del Registro Federal de Contribuyentes (RFC).';
$html .= '<br><br><b>Nota:</b> El pago del servicio cotizado será de la siguiente forma: <b>cubrir el costo total previo al inicio de la Verificación y  entregar al verificador el comprobante de pago</b>, con cheque empresarial o deposito bancario a nombre del Instituto Mexicano de Normalización y Certificación, A.C. en las cuentas <b>No. 001 006 90669 SUC. 28 SULLIVAN PLAZA 1 MEXICO D.F. CLABE 044 18000 100690669 8 de ScotiaBank.</b>
<br><b>BANAMEX </b> cuenta No. 096530-8 suc. No. 224 San Rafael. Para transferencia Inter. Bancaria: CUENTA CLABE 002180022409653080.
.';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html =' <br><br><b>Viáticos</b>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html = '<br> En el caso de visitas fuera del área metropolitana de la Ciudad de México, los costos correspondientes a transporte, hospedaje, taxis en el D.F. y alimentos del técnico (s) en evaluación de la conformidad del IMNC, correrán por cuenta de la empresa solicitante.';
$html .= '<br><br> La responsabilidad del cumplimiento de la norma de referencia es exclusiva del establecimiento verificado, por lo cual en caso de que cualquier autoridad imponga cualquier sanción por violaciones a la norma de referencia, esta no se podrá considerar como responsabilidad del IMNC.';
$html .= '<br>El IMNC no asume ni acepta alguna responsabilidad por cualquier daño o perjuicio por información publicitaria engañosa de las verificaciones de los establecimientos. Lo anterior no libera al IMNC por cualquier daño o prejuicio causado por negligencia comprobada. En caso de que el IMNC, incurra en alguna responsabilidad civil contra el cliente, el IMNC responderá con una póliza de seguro, previas indagaciones.';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html =' <br><br><b>CANCELACIONES </b>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html =' <br>En caso de cancelación o reprogramación del servicio, el cliente deberá notificar al IMNC con al menos 5 días naturales de anticipación, ésta procederá solo si se reciben vía escrita por fax o por correo electrónico, y en caso contrario se penalizará con el 50 % del costo total del servicio.';
$html .=' <br><br>
Debe (mos) y pagare (mos) incondicionalmente por este Pagaré a la orden del Instituto Mexicano de Normalización y Certificación, A.C. en México, D.F. en la fecha comprometida y por el monto que ha sido pactado en la cotización celebrada entre las partes. Valor recibido a entera satisfacción del Instituto Mexicano de Normalización y Certificación, A.C.
 ';
$html .= '<br><br>Este pagaré está sujeto a la condición de que, al no pagarse a su vencimiento será exigible hasta la fecha de su liquidación  y causará intereses moratorios al tipo de 15% mensual, pagadero en México, D.F. junto con el principal.';
$html .= '<br><br>Acepta (mos) y pagare (mos) a su vencimiento.';
$html .= '<br><br>Sin otro asunto en particular y esperando vernos favorecidos con su preferencia, quedo de usted.';

$html .= '<br><br><br>';
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
			
				____________________________________<br>IMNC<br>Rodrigo de Matheus Bustamante<br>Representante Legal

  			
  		</td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="219">
			
				____________________________________<br>Empresa<br>Nombre<br>Representante Legal

  			
  		</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$pdf1->SetTextColor(0,0,0);
$pdf1->SetFont('Calibri', '', 11);
$html =' <br><b>El cliente declara</b>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html =' <br>Ser una persona física o moral legalmente constituida conforme a la les leyes de los estados Unidos Mexicanos. ';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 13);
$html =' <br><br><b>C L Á U S U L A S </b>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html =' <br><br><b>Primera.</b>    “El Cliente” en este acto solicita al “IMNC” y éste acepta la prestación del servicio de la verificación de Distintivo H para el establecimiento y para ello lleva a cabo las actividades de verificación de Distintivo H a fin de obtener el dictamen aprobatorio de cumplimiento ';
$html .=' <br>Una vez que las evaluaciones han sido confirmadas, ésta podrá ser reprogramada a solicitud por escrito de la persona hasta con 10 días hábiles anteriores a la fecha establecida. ';
$html .=' <br><br><b>Segunda. </b>   “El IMNC” se compromete a prestar los servicios solicitados por “El Cliente”, siempre y cuando éste cumpla con sus obligaciones bajo el presente contrato, y pague las cuotas que se establecen en la cotización y en los plazos establecidos. ';
$html .=' <br><br><b>Tercera. </b>   “El IMNC” se compromete a prestar los servicios solicitados por “El Cliente”, siempre y cuando éste cumpla con sus obligaciones bajo el presente contrato, y pague las cuotas que se establecen en la cotización y en los plazos establecidos. ';
$html .=' <br><br><b>- </b>  GUÍA DE LA UNIDAD DE VERIFICACIÓN DE DISTINTIVO H. ';
$html .=' <br><b>- </b>  SOLICITUD Y CUESTIONARIOS DE VERIFICACIÓN DE DISTINTIVO H. ';
$html .=' <br><b>- </b>  NOTIFICACIÓN DE TÉCNICOS/AS VERICADORES DE DISTINTIVO H. ';
$html .=' <br><b>- </b>  Lista de verificación para el Distintivo H NMX-F-605-NORMEX-2015. ';
$html .=' <br><b>- </b>  Acta de verificación – DISTINTIVO H. ';
$html .=' <br><b>- </b>  CARTA COMPROMISO DE CONFIDENCIALIDAD. ';
$html .=' <br><br><b>Cuarta. </b>   “EL CLIENTE”, cubrirá a “IMNC” por concepto de honorario previa presentación de su factura y retención correspondiente la cantidad $   IVA incluido, la cantidad antes señalada será pagadera en moneda nacional y compensarán a este por los materiales, sueldos, honorarios y todos los demás gastos que se originen como consecuencia de este contrato. ';
$html .=' <br><br><b>Quinta. </b>     OBLIGACIONES DEL IMNC. El IMNC se obliga a: ';
$html .=' <br><br>1. Ejecutar los servicios de acuerdo con las especificaciones técnicas solicitadas y conforme a los alcances y presupuestos de los servicios establecidos en la cotización. ';
$html .=' <br><br><b>Sexta.  </b>     OBLIGACIONES DE EL CLIENTE. EL CLIENTE se obliga a: ';
$html .=' <br><br>1.Proporcionar la información técnica de que disponga, relacionada exclusivamente con la ejecución de los servicios que sea requerida por el IMNC.';
$html .=' <br> El resultado es aprobatorio, elaborar el dictamen colocando el número de dictamen consecutivo según le corresponda en el documento Seguimiento de Verificaciones y firmar por el Técnico Verificador.';
$html .=' <br>Enviar vía correo electrónico a la Secretaria de Turismo en un lapso no mayor a 10 días hábiles después de haber realizado la verificación oficial, el dictamen aprobatorio de manera confidencial con el formato de datos establecido por la SECTUR federal con los siguientes anexos: Carátula de SECTUR, Lista de verificación debidamente integrada y documentos comprobatorios, tal como se especifican en el apartado 6 de la Norma.';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$pdf1->SetTextColor(0,0,0);
$pdf1->SetFont('Calibri', '', 10);
$html ='<br><b>Septima. CONFIDENCIALIDAD: </b>';
$html .='<br><br>1. Las partes reconocen que durante la realización de los servicios a que se refiere a las l presentes clausulas, el IMNC podrá tener acceso a conocimientos técnicos, formulaciones, procedimientos, secretos, patentes, estrategias, programas y productos y otra información confidencial (en lo sucesivo la “Información Confidencial”), de la cual pueden ser propietarios el IMNC o el Cliente y que la divulgación de dicha información pueda causar daños o perjuicios a sus propietarios.';
$html .='<br>2. Reconoce el IMNC, además, que tiene el acceso a la “Información Confidencial” en relación o como resultado de los servicios y para el único propósito de cumplir con los objetivos del mismo, por lo cual se compromete a dar el tratamiento de confidencialidad a dicha información, sea que la haya adquirido en documentos, medios electromagnéticos o de forma verbal, reservada para el uso indispensable y necesario de cumplir con sus obligaciones bajo las presentes clausulas.';
$html .='<br>3. El IMNC, enviará a las oficinas estatales de turismo, el dictamen aprobatorio de manera confidencial con el formato de datos establecido por la SECTUR federal con los siguientes anexos: Carátula de SECTUR, Lista de verificación debidamente integrada y documentos comprobatorios.';
$html .='<br>4. Asimismo, las Partes se comprometen a que, no obstante que la “Información Confidencial” pueda ser evidente para un técnico en la materia, a darle trato de la mayor confidencialidad y a no divulgar la “Información Confidencial” por ningún medio sin la autorización expresa del IMNC o del Cliente según sea el caso, y a mantenerla en todo momento bajo un adecuado cuidado a fin de evitar que llegue a conocimiento de personas ajenas al proceso de verificación o su reproducción o divulgación por parte de cualquier tercera persona. Lo anterior, no sea aplicará a la información que única y exclusivamente para efectos estadísticos o de análisis divulgue de manera general en el IMNC, o aquella que le soliciten las autoridades competentes.';
$html .='<br>5. Por último, en caso de terminación anticipada las partes se comprometen a no divulgar dicha información total o parcialmente, de manera directa o indirecta por cualquiera de sus funcionarios, empleados o terceros autorizados por el cliente.';
$html .='<br>6. Las partes reconocen y expresan que los expedientes de los Clientes caen en dicha categoría y se consideran como Información Confidencial y por lo tanto sujetos a las disposiciones de la presente Cotización.';
$html .='<br><br><b>Octava. CASO FORTUITO O FUERZA MAYOR :</b> <br><br>  De conformidad con los términos apuntados en el Artículo 2111 del Código Civil Federal, las partes reconocen y acuerdan que ante un acontecimiento que esté fuera del dominio de su voluntad originado por cualquier causa ajena al control de las mismas, que sea imprevisible, o que aun siendo previsible fuera inevitable o insuperable, y en la que no haya mediado negligencia o culpa de las partes, sus empleados o representantes, que les impida cumplir de manera absoluta con cualquiera de sus obligaciones respecto a las clausulas, originando con ello un daño  la otra parte, ninguna de ellas será responsable para con la otra por los daños y perjuicios provocados en virtud de tal acontecimiento, en el entendido que para la parte que invoque el caso fortuito o fuerza mayor sea liberada de responsabilidad, será necesario que tanto dicho acontecimiento como la imposibilidad de cumplimiento de sus obligaciones sean debidamente probados, o en su defecto, que la existencia de ambos sea del dominio público.  Lo anterior incluye de manera ilustrativa más no limitativa, actos definitivos de autoridad, bloqueos, invasiones, rebeliones, conflictos armados, actos terroristas, explosiones, incendios, invasiones, huracanes, tormentas, inundaciones, terremotos, accidentes o fallas en el servicio de energía eléctrica que impidan continuar con el cumplimiento de la obligación.';
$html .='<br>Para que cualquiera de las partes sea liberada de su responsabilidad por caso fortuito o de fuerza mayor, serán condiciones indispensables de la parte que lo invoque:';
$html .='<br>a)	Lo notifique por escrito a la otra parte, inclusive cuando la existencia del acontecimiento sea del dominio público.';
$html .='<br>b)	Pruebe dentro de los cinco (5) días hábiles siguientes a la notificación correspondiente, la existencia del caso fortuito o fuerza mayor, así como la imposibilidad del cumplimiento de sus obligaciones; aceptando ambas partes que de no hacerlo así, el caso fortuito o de fuerza mayor invocado no será considerado como tal.';
$html .='<br>Cualquiera de las partes que reclamare caso fortuito o de fuerza mayor, notificará con toda prontitud a la otra el caso fortuito o fuerza mayor en cuestión, los efectos con respecto al cumplimiento de sus obligaciones contenidas en el presente documento, la duración estimada del mismo y el momento en que el caso fortuito o fuerza mayor termine.';
$html .='<br>En cualquiera de los casos que se indican, la parte a la que se notifique la suspensión, podrá dar por terminado anticipadamente el presente contrato, sin necesidad de declaración judicial y sin responsabilidad.';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->AddPage();
$pdf1->SetTextColor(0,0,0);
$pdf1->SetFont('Calibri', '', 10);
$html ='<br><b>Novena.  QUEJAS, RECLAMACIONES TÉCNICAS Y APELACIONES.</b>';
$html .='<br><br>1.	El cliente tiene en todo momento el derecho a interponer quejas, reclamaciones técnicas y apelaciones sobre las prestaciones de los servicios de verificación de Distintivo H del IMNC, estas podrán ser vía telefónica, por escrito o personalmente a través del departamento de gestión de clientes, quien proporcionara el procedimiento a seguir, con domicilio en Manuel Ma. Contreras No. 133-6° Piso Col. Cuauhtémoc, C.P. 06500, México, D.F., quien junto con el personal que realizó el servicio resolverá la queja, reclamación técnica o apelación, el IMNC se reserva el derecho de llevar a cabo la investigación de inmediato.';
$html .='<br>2.	El personal responsable de la Unidad de Verificación del IMNC, tendrá un plazo no mayor a 10 días hábiles, para atender, y responder, cualquier queja o reclamación técnica que presenten los interesados, con copia a las dependencias competentes, previas las indagatorias de esta, realizar y aplicar las medidas correspondientes, conforme lo dispuesto en la Ley Federal sobre Metrología y Normalización, en su Título Sexto, Capitulo lll, Art. 122.';
$html .='<br>3.	Se informa al cliente que los gastos de investigación o de gastos con organismos en tercería u otros gastos deben ser pagados por la parte que resultara ser responsable.<br><br><br>';
$pdf1->writeHTML($html, true, false, true, false, '');

$pdf1->SetFont('Calibri','',10);
$html = <<<EOT
<table cellpadding="15" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium;" width="100"></td>
		<td style="font-size: medium;" width="276">_____________________________________________<br>Responsable del Establecimiento</td>
		<td style="font-size: medium;" width="100"></td>
	</tr>
	
</table>
EOT;
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