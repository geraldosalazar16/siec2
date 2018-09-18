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
$pdf->AddFont('Calibri','','calibri.php');
$pdf->AddFont('Calibri','B','calibrib.php');
$pdf->AddFont('Calibri','I','calibrii.php');
$pdf->AddFont('Calibri','BI','calibribi.php');
$pdf->AddFont('Calibril','','calibril.php');
///////////////////////////////////////////
$file = 'Plantilla1.pdf';
$pageCount = $pdf->setSourceFile($file);
///////////////////////////////////////////////////////////////////
//Pagina 1
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
//Texto No1
$pdf->SetFont('Calibril','',18);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(10,95);
$pdf->MultiCell(0,0,$No1,0,'C');
//Texto No2
/*
$pdf->SetFont('Calibri','',18);
$pdf->SetTextColor(128,128,128);
*/
$pdf->SetFont('Arial','B',18);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(10,118);
$pdf->MultiCell(0,0,$No2,0,'C');
//Texto No3
$pdf->SetFont('Calibril','',18);
$pdf->SetTextColor(128,128,128);
$pdf->SetXY(10,133);
$pdf->MultiCell(0,0,$No3,0,'C');
//Texto No4
$pdf->SetFont('Arial','B',18);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(10,156);
$pdf->MultiCell(0,0,$No4,0,'C');
///////////////////////////////////////////////////////////////////
//Pagina 2
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(2, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
//Texto No5
$pdf->SetFont('Calibri','B',14);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(29,30);
$pdf->Write(0,$No5);
//Texto No6
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,52);
$pdf->Write(0,$No6);
//Texto No7
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,56.2);
$pdf->Write(0,$No7);
//Texto No8
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,60.4);
$pdf->Write(0,$No8);
//Texto No9
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,64.2);
$pdf->Write(0,$No9);
//Texto No10
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,68.4);
$pdf->Write(0,$No10);
//Texto No11
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,72.4);
$pdf->Write(0,$No11);
//Texto No12
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,76.4);
$pdf->Write(0,$No12);
//Texto No13
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(50,80.6);
$pdf->Write(0,$No13);
//Texto No14
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(138,80.6);
$pdf->Write(0,$No14);
//Texto No15
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(130,98.8);
$pdf->Write(0,$No15);
//Texto No16
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(130,102.8);
$pdf->Write(0,$No16);
//Texto No17
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,98.8);
$pdf->Write(0,$No17);
//Texto No18
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,102.8);
$pdf->Write(0,$No18);
//Texto No19
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,115);
$pdf->Write(0,$No19);
//Texto No20
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,123);
$pdf->Write(0,$No20);
//Texto No21
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,131);
$pdf->Write(0,$No21);
//Texto No22
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,106.8);
$pdf->Write(0,$No22);
//Texto No23
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(130,147);
$pdf->Write(0,$No23);
//Texto No24
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,147);
$pdf->Write(0,$No24);
//Texto No25
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,155);
$pdf->Write(0,$No25);
//Texto No26
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,163);
$pdf->Write(0,$No26);
//Texto No27
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,170);
$pdf->Write(0,$No27);
//Texto No28
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(130,186.5);
$pdf->Write(0,$No28);
//Texto No29
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,186.5);
$pdf->Write(0,$No29);
//Texto No30
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,194.5);
$pdf->Write(0,$No30);
//Texto No31
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,202.5);
$pdf->Write(0,$No31);
//Texto No32
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(54,95,145);
$pdf->SetXY(177,210.5);
$pdf->Write(0,$No32);
///////////////////////////////////////////////////////////////////
//Pagina 3
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(3, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
///////////////////////////////////////////////////////////////////
//Pagina 4
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(4, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
///////////////////////////////////////////////////////////////////
//Pagina 5
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(5, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
///////////////////////////////////////////////////////////////////
//Pagina 6
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(6, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);

//Texto No33
$pdf->SetFont('Calibri','',10);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(60,145.8);
$pdf->Write(0,$No33);
$pdf->Output("ex_dan.pdf",'I');/*
//////////////////////////////////////////////////////////////////////////////////////////////
//Prueba
//////////////////////////////////////////////////////////////////////////////////////////////
//require_once('../../../phplibs/libPDF/examples/tcpdf_include.php');

// create new PDF document
$pdf1 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
//$pdf1->SetCreator(PDF_CREATOR);
//$pdf1->SetAuthor('Nicola Asuni');
//$pdf1->SetTitle('TCPDF Example 021');
//$pdf1->SetSubject('TCPDF Tutorial');
//$pdf1->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf1->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 021', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf1->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf1->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf1->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf1->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf1->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf1->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf1->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
//$pdf1->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
//	require_once(dirname(__FILE__).'/lang/eng.php');
//	$pdf1->setLanguageArray($l);
//}

// ---------------------------------------------------------

// set font
$pdf1->SetFont('helvetica', '', 9);

// add a page
$pdf1->AddPage();

// create some HTML content
//$html = '<h1>Example of HTML text flow</h1>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. <em>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?</em> <em>Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</em><br /><br /><b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i> -&gt; &nbsp;&nbsp; <b>A</b> + <b>B</b> = <b>C</b> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>B</i> = <i>A</i> &nbsp;&nbsp; -&gt; &nbsp;&nbsp; <i>C</i> - <i>A</i> = <i>B</i><br /><br /><b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u> <b>Bold</b><i>Italic</i><u>Underlined</u>';
$html = <<<EOT
<table cellpadding="15" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium;" width="319"><strong> Atentamente,  </strong></td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="319"><strong> Vo.Bo </strong></td>
	</tr>
	<tr>
		<td style="font-size: medium;" width="319">
			<strong>
				________________________________________________<br>Daniel de certificación
  			</strong>
  		</td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="319">
			<strong>
				________________________________________________<br>Daniel<br>Jefe
  			</strong>
  		</td>
	</tr>
</table>
EOT;
// output the HTML content
$pdf1->writeHTML($html, true, 0, true, 0);
//$pageID11=$pdf1->importPage(1);
//$pdf->useTemplate($pageID11,0,0);
// reset pointer to the last page
//$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf1->Output('ex_dan1.pdf','I');
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
*/

?>