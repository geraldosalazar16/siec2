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
//$id_auditoria = $_REQUEST["ID_AUDITORIA"];
$id_sce = $_REQUEST["ID_SCE"];
$id_tipo_auditoria = $_REQUEST["ID_TA"];
$ciclo = $_REQUEST["CICLO"];
$abcde = explode("string:",$_REQUEST["cmbDomicilioNotificacionPDF"]);
$id_domicilio =$abcde[1] ;
$tipoNotificacionPDF = $_REQUEST["cmbTipoNotificacionPDF"];
$tipoCambiosPDF = $_REQUEST["cmbTipoCambiosPDF"];
$certificacionMantenimientoPDF = $_REQUEST["cmbCertificacionMantenimientoPDF"];
$nota1PDF = $_REQUEST["txtNota1PDF"];
$nota2PDF = $_REQUEST["txtNota2PDF"];
$nota3PDF = $_REQUEST["txtNota3PDF"];
$nombreAutorizaPDF = $_REQUEST["txtNombreAutorizaPDF"];
$cargoAutorizaPDF = $_REQUEST["txtCargoAutorizaPDF"];
$nombreAuxiliar = $_REQUEST["nombreUsuario"];

/*/////////////////////////////////////////////////////////////////////////*/
//Para obtener datos de la base de datos
/*/////////////////////////////////////////////////////////////////////////*/
$valor = false;
$json_response = file_get_contents($global_apiserver . "/i_sg_auditorias/getById/?completo=true&id_sce=". $id_sce."&id_ta=".$id_tipo_auditoria."&ciclo=".$ciclo."&id_domicilio=".$id_domicilio);
valida_isset($json_response, "Error en la conexión a los datos para generar PDF en linea: " . __LINE__);

$json_object = json_decode($json_response);

//Datos para notificación
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


// Lugar, fecha y referencia

$LUGAR_Y_FECHA = date("d")." de ".$meses[date('n')-1]." de ". date("Y");

$REFERENCIA = $json_object->SERVICIO_CLIENTE_ETAPA->REFERENCIA;
valida_isset($REFERENCIA, "Error: No se encuentra la REFERENCIA en linea: " . __LINE__);

$arr_sectores = $json_object->SERVICIO_CLIENTE_ETAPA->SG_SECTORES; //Es arreglo
valida_isset($arr_sectores, "Error: No se encuentra arr_sectores en linea: " . __LINE__);

$SECTORES = "";
for ($i=0; $i < count($arr_sectores); $i++) {
	if (($i+1) ==  count($arr_sectores)) { //Si es el ultimo elemento
		$SECTORES .= $arr_sectores[$i]->SECTORES->NOMBRE;
		valida_isset($SECTORES, "Error: No se encuentra SECTORES en linea: " . __LINE__);
	}
	else{
		$SECTORES .= $arr_sectores[$i]->SECTORES->NOMBRE . ", ";
		valida_isset($SECTORES, "Error: No se encuentra SECTORES en linea: " . __LINE__);
	}
}

// Datos de contacto y domicilio

$obj_cliente = $json_object->SERVICIO_CLIENTE_ETAPA->CLIENTE;
valida_isset($obj_cliente, "Error: No se encuentra obj_cliente en linea: " . __LINE__);
$obj_domicilio_fiscal = $obj_cliente->CLIENTE_DOMICILIO_FISCAL;
valida_isset($obj_domicilio_fiscal, "Error: No se encuentra obj_domicilio_fiscal en linea: " . __LINE__);

$NOMBRE_CLIENTE = $obj_cliente->NOMBRE;
valida_isset($NOMBRE_CLIENTE, "Error: No se encuentra NOMBRE_CLIENTE en linea: " . __LINE__);
//$NOMBRE_CONTACTO = "AAAAAA";
$NOMBRE_CONTACTO = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->NOMBRE_CONTACTO;
valida_isset($NOMBRE_CONTACTO, "Error: es necesario definir un domicilio fiscal y con contacto para recibir notificación: " . __LINE__);
//$CARGO_CONTACTO = "BBBBBBBB";
$CARGO_CONTACTO = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->CARGO;
valida_isset($CARGO_CONTACTO, "Error: es necesario definir un domicilio fiscal y con contacto para recibir notificación: " . __LINE__);

$calle  = $obj_domicilio_fiscal->CALLE;
valida_isset($calle, "Error: No se encuentra calle en linea: " . __LINE__);
$numero_exterior = $obj_domicilio_fiscal->NUMERO_EXTERIOR;
valida_isset($numero_exterior, "Error: No se encuentra numero_exterior en linea: " . __LINE__);
$numero_interior = $obj_domicilio_fiscal->NUMERO_INTERIOR;
valida_isset($numero_interior, "Error: No se encuentra numero_interior en linea: " . __LINE__);
$colonia_barrio = $obj_domicilio_fiscal->COLONIA_BARRIO;
valida_isset($colonia_barrio, "Error: No se encuentra colonia_barrio en linea: " . __LINE__);
$cp = $obj_domicilio_fiscal->CP;
valida_isset($cp, "Error: No se encuentra cp en linea: " . __LINE__);
$delegacion_municipio = $obj_domicilio_fiscal->DELEGACION_MUNICIPIO;
valida_isset($delegacion_municipio, "Error: No se encuentra delegacion_municipio en linea: " . __LINE__);
$entidad_federativa = $obj_domicilio_fiscal->ENTIDAD_FEDERATIVA;
valida_isset($entidad_federativa, "Error: No se encuentra entidad_federativa en linea: " . __LINE__);
$telefono_fijo = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->TELEFONO_FIJO;
valida_isset($telefono_fijo, "Error: No se encuentra telefono_fijo en linea: " . __LINE__);
$extension = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->EXTENSION;
valida_isset($extension, "Error: No se encuentra extension en linea: " . __LINE__);
$email = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->EMAIL;
valida_isset($email, "Error: No se encuentra email en linea: " . __LINE__);

$CALLE_Y_NUMERO = $calle . " No. ". $numero_exterior;

$COLONIA_DELEGACION_Y_CP = "Col. " . $colonia_barrio . " " . $delegacion_municipio . ", C.P. " . $cp;

$ENTIDAD_FEDERATIVA = $entidad_federativa;

//$TELEFONO_Y_EXTENSION = "Tel. " . $telefono_fijo . " ext. " . $extension;
$TELEFONO =  $telefono_fijo;
$CORREO = $email;

$TRAMITE = $json_object->SERVICIO_CLIENTE_ETAPA->ETAPA_PROCESO->ETAPA;
valida_isset($TRAMITE, "Error: No se encuentra TRAMITE en linea: " . __LINE__);

$ID_ETAPA = $json_object->SERVICIO_CLIENTE_ETAPA->ETAPA_PROCESO->ID_ETAPA;
$ID_SERVICIO = $json_object->SERVICIO_CLIENTE_ETAPA->ID;

//$NORMA = $json_object->SG_TIPO_SERVICIO->NORMA->ID;
//valida_isset($NORMA, "Error: No se encuentra NORMA en linea: " . __LINE__);

$fecha_aux = $json_object->SG_AUDITORIA_FECHAS[0]->FECHA;
valida_isset($fecha_aux, "Error: No se encuentra FECHA_INICIO en linea: " . __LINE__);
$FECHA_INICIO_AUDITORIA = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));

$fecha_aux = $json_object->SG_AUDITORIA_FECHAS[count($json_object->SG_AUDITORIA_FECHAS)-1]->FECHA;
valida_isset($fecha_aux, "Error: No se encuentra FECHA_FIN en linea: " . __LINE__);
$FECHA_FIN_AUDITORIA = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));

// $duracion_auditoria = $json_object->DURACION_DIAS;
// valida_isset($duracion_auditoria, "Error: No se encuentra DURACION_DIAS en linea: " . __LINE__);
// $fecha_aux =  date('Ymd',strtotime($fecha_aux . "+".strval(intval($duracion_auditoria)-1)." days")); 
// $FECHA_FIN_AUDITORIA = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));


$pts = $json_object->SG_AUDITORIA_GRUPO; //Arreglo de auditores
valida_isset($pts, "Error: No se encuentra SG_AUDITORIA_GRUPO en linea: " . __LINE__);

$PERSONAL_TECNICO = "";
for ($i=0; $i < count($pts) ; $i++) { 
	$PT_NOMBRE_COMPLETO = $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->NOMBRE . " " . $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_PATERNO . " " . $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_MATERNO;
	$PT_SECTORES = implode (", ", $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO_CALIF_SECTORES);
//	$PT_SECTORES = 35;
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->NOMBRE, "Error: No se encuentra NOMBRE en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_PATERNO, "Error: No se encuentra APELLIDO_PATERNO en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_MATERNO, "Error: No se encuentra APELLIDO_MATERNO en linea: " . __LINE__);

	$PERSONAL_TECNICO .= '<tr style="text-align:RIGHT;">';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;" align="CENTER" width="75"> '.trim($pts[$i]->PERSONAL_TECNICO_ROL->ROL).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="225"> '.trim($PT_NOMBRE_COMPLETO).'  </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="75"> '.trim($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="75"> '.trim($PT_SECTORES).' </td>';
	$PERSONAL_TECNICO .= '</tr>';

	valida_isset($pts[$i]->PERSONAL_TECNICO_ROL->ROL, "Error: No se encuentra ROL en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO, "Error: No se encuentra REGISTRO en linea: " . __LINE__);
}
$CLAVE_CERTIFICADO = "12323";
$CC_FECHA_INICIO = "02/04/2018";
$CC_FECHA_FIN = "02/05/2018";
//$CLAVE_CERTIFICADO = $json_object->SG_AUDITORIA_CERTIFICADO->CLAVE;
valida_isset($CLAVE_CERTIFICADO, "Error: No se encuentra la clave certificado: " . __LINE__);
//$CC_FECHA_INICIO = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_INICIO;
valida_isset($CC_FECHA_INICIO, "Error: No se encuentra la fecha de inicio: " . __LINE__);
//$CC_FECHA_FIN = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_FIN;
valida_isset($CC_FECHA_FIN, "Error: No se encuentra la fecha final: " . __LINE__);

$FA = $json_object->SG_AUDITORIA_FECHAS;
valida_isset($FA, "Error: No se encuentra las fechas auditoria " . __LINE__);
$FECHAS_AUDITORIAS = "";
for ($i=0; $i < count($FA) ; $i++) { 
	$FECHAS_AUDITORIAS .= $FA[$i]->FECHA.",";
}

$fecha = mes_esp(date('d/n/Y'));
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
		$this->SetFont('Helvetica', 'B', 14);
		// Title
		$this->setCellMargins(15,15,0,0);
		$this->Cell(0, 10, 'NOTIFICACIÓN DE AUDITORÍA DE', 0, false, 'L', 0, '', 0, false, 'M', 'M');
		
		// Set font
		//$this->SetFont('Calibri', 'B', 14);
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
			Clave: FPEC14 <br>
			Fecha de aplicación: 2018-04-09 <br>
			Versión: 05 <br>
			Página $NumPage de $TotPage<br>
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
//////////////////////////
// create new PDF document
//$pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$str_direccion="Daniel Hernandez Barroso";
//$global_diffname="E:/xampp/htdocs/imnc/imnc/generar/pdf/cotizacion/";
$No5="CERTIFICACIÓN DE SISTEMAS DE GESTIÓN";
$global_diffname="E:/xampp/htdocs/pruebagit/siec2/imnc/generar/pdf/notificacion_servicio/imnc";
// create new PDF document
$pdf1 = new MYPDF($No5, $str_direccion, $global_diffname, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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


//$pdf1->SetTextColor(54,95,145);
// add a page (ESTA ES LA PAGINA DE PORTADA)
$pdf1->SetPrintHeader(true);
$pdf1->AddPage();
$pdf1->SetPrintFooter(true);

// Titulo de documento (centrado)
$pdf1->SetXY(0,25);
// set font
$pdf1->SetFont('Calibri', 'B', 12);
$html = '<br><br><br><div style="text-align:center;">Notificación </div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html =  '<br><div style="text-align:right;">Ciudad de México, a</div>';
$html .= '<div style="text-align:right;">'.$fecha.'</div>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html = '<div style="text-align:left;"><strong><i>Nombre de la Organización:&nbsp;</i></strong> '.$NOMBRE_CLIENTE.'<br>';
$html .= '<strong><i>Dirección:&nbsp;</i></strong> '.$CALLE_Y_NUMERO.'&nbsp;'.$COLONIA_DELEGACION_Y_CP.'&nbsp;'.$ENTIDAD_FEDERATIVA.'<br>';
$html .= '<strong><i>Representante Autorizado:&nbsp;</i></strong> '.$NOMBRE_CONTACTO.'<br>';
$html .= '<strong><i>Puesto:&nbsp;</i></strong> '.$CARGO_CONTACTO.'<br>';
$html .= '<strong><i>Teléfono:&nbsp;</i></strong> '.$TELEFONO.'<br>';
$html .= '<strong><i>Email:&nbsp;</i></strong> '.$CORREO.'</div>';
$html .= '<br> Por medio de este conducto le informo que de acuerdo a su Proceso de Certificación, se llevará a cabo su servicio de auditoría como a continuación se indica:';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html= <<<EOT
<br><br><table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;No(s). De Solicitud</strong></td>
		<td style="font-size: medium; text-align:left" width="350"> &nbsp;&nbsp;$REFERENCIA</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Sector IAF</strong></td>
		<td style="font-size: medium; text-align:left" width="350"> &nbsp;&nbsp;$SECTORES</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Categoría (Exclusivo SGIA)</strong></td>
		<td style="font-size: medium; text-align:left" width="350"> &nbsp;&nbsp;N/A</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Número de registro</strong></td>
		<td style="font-size: medium; text-align:left" width="350"> &nbsp;&nbsp;$CLAVE_CERTIFICADO</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Fecha de inicio de vigencia</strong></td>
		<td style="font-size: medium; text-align:left" width="125"> &nbsp;&nbsp;$CC_FECHA_INICIO</td>
		<td style="font-size: medium; text-align:left" width="100" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Fecha de expiración</strong></td>
		<td style="font-size: medium; text-align:left" width="125"> &nbsp;&nbsp;$CC_FECHA_FIN</td>
	</tr>
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html= <<<EOT
<br><br><table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:center"  BGCOLOR="#1F487B"><strong>TIPO DE SERVICIO</strong></td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:center"  BGCOLOR="#E0E0E0"><i>Marque por favor con una “X” los recuadros que correspondan a la auditoría a realizar:</i></td>
		
	</tr>
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html= <<<EOT
<br><br><table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:center"  width="225"><input type="checkbox" name="chk1" value="1" checked="false" readonly="false">Auditoría en instalaciones del IMNC</td>
		<td style="font-size: medium; text-align:center"  width="225"><input type="checkbox" name="chk2" value="1" checked="false" readonly="false">Auditoría en Sitio</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk3" value="1" checked="false" readonly="false">Auditoría Etapa 1</td>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk4" value="1" checked="false" readonly="false">Auditoría Especial:</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk18" value="1" checked="false" readonly="false">Auditoría Etapa 2</td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk19" value="1" checked="false" readonly="false">Ampliación de alcance</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk5" value="1" checked="false" readonly="false">Auditoría de Vigilancia 1</td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk6" value="1" checked="false" readonly="false">Reducción de alcance</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk7" value="1" checked="false" readonly="false">Auditoría de Vigilancia 2</td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk8" value="1" checked="false" readonly="false">Actualización de Sistema de Gestión</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk9" value="1" checked="false" readonly="false">Otra (Indique el No. de vigilancia que corresponda)</td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk10" value="1" checked="false" readonly="false">Por cambios de domicilio</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"></td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk11" value="1" checked="false" readonly="false">Por cambio de situación legal</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"></td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk12" value="1" checked="false" readonly="false">Por cambio en personal clave</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk13" value="1" checked="false" readonly="false">Renovación de la certificación</td>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk14" value="1" checked="false" readonly="false">Auditoría con notificación a corto plazo:</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chk15" value="1" checked="false" readonly="false">Transferencia de la certificación</td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk16" value="1" checked="false" readonly="false">Por quejas de clientes</td>	
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"></td>
		<td style="font-size: medium; text-align:left"  width="225">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk17" value="1" checked="false" readonly="false">Por seguimiento de la certificación suspendida</td>	
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html= <<<EOT
<br><br><table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000ff style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:center"  BGCOLOR="#E0E0E0" width="150"><strong>En la(s) fecha(s) siguiente(s):</strong></td>
		<td style="font-size: medium; text-align:center"   width="300">$FECHAS_AUDITORIAS</td>
	</tr>
	
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');



// add pagina 2
$pdf1->SetPrintHeader(true);
$pdf1->AddPage();
$pdf1->SetPrintFooter(true);

$html= <<<EOT
<br><br><strong>Con el siguiente equipo auditor asignado para conducir la auditoría de certificación:</strong>
<br><br><table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000ff style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0" width="75"><strong>FUNCIÓN</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="225"><strong>NOMBRE</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="75"><strong>VALIDACIÓN</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="75"><strong>SECTOR(ES)</strong></td>
	</tr>
	$PERSONAL_TECNICO
</table>
<br><br> Bajo la(s) siguiente(s) norma(s) de referencia: 
<br>
<br><br><table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;" width="450">
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn1" value="1" checked="false" readonly="false">NMX-CC-9001-IMNC-2015 / ISO 9001:2015</td>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn2" value="1" checked="false" readonly="false">NMX-SAST-001-IMNC-2008</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn1" value="1" checked="false" readonly="false">NMX-SAA-14001-IMNC-2015 / ISO 14001:2015</td>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn2" value="1" checked="false" readonly="false">NMX-J-SAA-50001-ANCE-IMNC-2011 / ISO 50001:2011</td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:left"  width="450"><input type="checkbox" name="chkn1" value="1" checked="false" readonly="false">Otro(s) Indique: _____________________________________</td>
		
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html= <<<EOT
<br><br><strong>NOTA 1:</strong> El servicio de auditoría que se describe en la presente Notificación, ha sido programado con base al ciclo actual de su
certificación, y en apego a las “Condiciones generales de certificación” vigentes, dadas a conocer previamente por el IMNC y
publicadas en <a>www.imnc.org.mx</a> En caso de requerir reprogramar la(s) fecha(s) de su auditoría, consulte con su Ejecutivo de
Atención a Clientes.
<br><br><strong>NOTA 2:</strong> Para el mantenimiento de su certificación, las auditorias de vigilancia, el IMNC deberá confirmar con el cliente, las
fechas propuestas 30 días naturales antes de la fecha máxima; en caso contrario, la auditoría se programara en las fechas que
el IMNC tenga disponibles.
<br><br><strong>NOTA 3:</strong> Para el caso de la auditoria de renovación se recomienda que ésta se lleve a cabo con al menos tres meses antes de
la fecha de expiración de su registro.
<br><br><strong> NOTA 4:</strong> En caso de no estar de acuerdo con la designación de algún miembro del equipo auditor y/o fecha, el cliente debe
notificarlo por escrito al IMNC, presentando las razones correspondientes, en un plazo no mayor a 5 días hábiles a partir de la
recepción de esta notificación. En caso contrario se considerará como aceptado el equipo auditor y/o fecha propuesta.
<br><br><strong> NOTA 5:</strong> Los documentos solicitados para elaboración del plan de auditoría deben ser enviados en un plazo no mayor a 5 días hábiles a partir de la recepción de esta notificación, en caso contrario el IMNC se reserva el derecho de reprogramar la auditoría conforme al clausulado del acuerdo legalmente ejecutable entre ambas partes.
EOT;
if($nota1PDF!=""){
$html .= <<<EOT
<br><br><strong>NOTA 6:</strong> $nota1PDF
EOT;
}
if($nota2PDF!=""){
$html .= <<<EOT
<br><br><strong>NOTA 7:</strong> $nota2PDF
EOT;
}
if($nota3PDF!=""){
$html .= <<<EOT
<br><br><strong>NOTA 8:</strong> $nota3PDF
EOT;
}
$html .=<<<EOT
<br><br>
Por lo anterior le agradeceremos se sirva enviarnos el presente documento con la firma de Visto Bueno.
</div>
<br>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');


// Espacio para firmas
$html = <<<EOT
<table cellpadding="15" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium; text-align:center" width="200"><strong> Atentamente,  </strong></td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium; text-align:center" width="200"><strong> Vo.Bo </strong></td>
	</tr>
	<tr>
		<td style="font-size: medium; text-align:center" width="200">
			<strong>
				________________________________<br>$nombreAuxiliar<br>Programador(a) IMNC
			</strong>
  		</td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium; text-align:center" width="200">
			<strong>
				________________________________<br>$nombreAutorizaPDF<br>Representante Autorizado
  			</strong>
  		</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$pdf1->Output();
// ---------------------------------------------------------


?>