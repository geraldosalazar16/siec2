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
if($id_sce)
{
$id_servicio = $_REQUEST["SERVICIO"];
$id_tipo_auditoria = $_REQUEST["ID_TA"];
$ciclo = $_REQUEST["CICLO"];
$abcde = explode("string:",$_REQUEST["cmbDomicilioNotificacionPDF"]);
$chck1 = $_REQUEST["CHCK1"];
$chck2 = $_REQUEST["CHCK2"];
$chck1 = ($chck1?'checked="true"':"");
$chck2 = ($chck2?'checked="true"':"");
$save = $_REQUEST["SAVE"];

$id_domicilio =$abcde[1] ;
//$tipoNotificacionPDF = $_REQUEST["cmbTipoNotificacionPDF"];
//$tipoCambiosPDF = $_REQUEST["cmbTipoCambiosPDF"];
//$certificacionMantenimientoPDF = $_REQUEST["cmbCertificacionMantenimientoPDF"];
$notasPDF = $_REQUEST["txtNotas"];
if($notasPDF)
{
    $notasPDF = explode("<|>",$notasPDF);
}else{
    $notasPDF = [];
}
if($save == "save")
{
    $object = $database->get("AUDITORIAS_NOTIFICACION",["ID"],["AND"=>["ID_SCE"=>$id_sce,"ID_TA"=>$id_tipo_auditoria,"CICLO"=>$ciclo,"SERVICIO"=>$id_servicio]] );
    valida_error_medoo_and_die();
    $Domicilio = $_REQUEST["DOMICILIO"];
    if(!$object)
    {
        $idc = $database->insert("AUDITORIAS_NOTIFICACION", [
            "ID_SCE" => $id_sce,
            "ID_TA"=>$id_tipo_auditoria,
            "CICLO"=>$ciclo,
            "SERVICIO"=>$id_servicio,
            "DOMICILIO"=>$Domicilio,
        ]);
        valida_error_medoo_and_die();
    }else{
        $idc = $database->update("AUDITORIAS_NOTIFICACION", [
            "DOMICILIO"=>$Domicilio,
        ]);
        valida_error_medoo_and_die();
    }

    $notasPDFEdit = $_REQUEST["txtNotasEdit"];
    if($notasPDFEdit)
    {
        $notasPDFEdit = explode("<|>",$notasPDFEdit);
        if(!$object)
        {
            foreach ($notasPDFEdit as $NOTA)
            {

                    $idn = $database->insert("AUDITORIAS_NOTIFICACION_NOTAS", [
                        "ID_AUDITORIAS_NOTIFICACION" => $idc,
                        "NOTA"=>$NOTA,
                    ]);
                    valida_error_medoo_and_die();

            }

        }
        else
        {

            foreach ($notasPDFEdit as $NOTA)
            {
                $delete = $database->delete("AUDITORIAS_NOTIFICACION_NOTAS",["AND"=>["ID_AUDITORIAS_NOTIFICACION"=>$object["ID"],"NOTA"=>$NOTA]]);
                if($delete==0)
                {
                    $idn = $database->insert("AUDITORIAS_NOTIFICACION_NOTAS", [
                        "ID_AUDITORIAS_NOTIFICACION" => $object["ID"],
                        "NOTA"=>$NOTA,
                    ]);
                    valida_error_medoo_and_die();
                }

            }


        }

    }
}


//$nombreAutorizaPDF = $_REQUEST["txtNombreAutorizaPDF"];
//$cargoAutorizaPDF = $_REQUEST["txtCargoAutorizaPDF"];
$nombreAuxiliar = strtoupper($_REQUEST["nombreUsuario"]);

/*/////////////////////////////////////////////////////////////////////////*/
//Para obtener datos de la base de datos
/*/////////////////////////////////////////////////////////////////////////*/
$valor = false;
if($id_servicio==1)
{
    $json_response = file_get_contents($global_apiserver . "/i_sg_auditorias/getById/?completo=true&id_sce=". $id_sce."&id_ta=".$id_tipo_auditoria."&ciclo=".$ciclo."&id_domicilio=".$id_domicilio);
}
if($id_servicio==2)
{
    $json_response = file_get_contents($global_apiserver . "/i_ec_auditorias/getById/?completo=true&id_sce=". $id_sce."&id_ta=".$id_tipo_auditoria."&ciclo=".$ciclo."&id_domicilio=".$id_domicilio);
}
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
		$SECTORES .= $arr_sectores[$i]->SECTORES->ID;
		valida_isset($SECTORES, "Error: No se encuentra SECTORES en linea: " . __LINE__);
	}
	else{
		$SECTORES .= $arr_sectores[$i]->SECTORES->ID . ", ";
		valida_isset($SECTORES, "Error: No se encuentra SECTORES en linea: " . __LINE__);
	}
}
    $SECTORES =   ($SECTORES?$SECTORES:'N/A');
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

$NORMA = $json_object->SERVICIO_CLIENTE_ETAPA->NORMA;
valida_isset($NORMA, "Error: No se encuentra NORMA en linea: " . __LINE__);
/*===========================================================================*/
//				CODIGO PARA LAS MOSTRAR LAS NORMAS
$TEXT_NORMA ="";
if(count($NORMA)>0){
	
	for($i=0;$i<count($NORMA);$i++){
		if($i%2 == 0){
			$TEXT_NORMA .='<tr>';
			$TEXT_NORMA .='<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn'.($i+1).'" value="1" checked="true" readonly="false">'.trim($NORMA[$i]->ID_NORMA).'</td>';
		}
		else{
			$TEXT_NORMA .='<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn'.($i+1).'" value="1" checked="true" readonly="false">'.trim($NORMA[$i]->ID_NORMA).'</td>';
			$TEXT_NORMA .='</tr>';
		}		
	/*	<tr>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn1" value="1" checked="false" readonly="false">NMX-CC-9001-IMNC-2015 / ISO 9001:2015</td>
		<td style="font-size: medium; text-align:left"  width="225"><input type="checkbox" name="chkn2" value="1" checked="false" readonly="false">NMX-SAST-001-IMNC-2008</td>
	</tr>*/
	}
	if(count($NORMA)%2 == 1){
		$TEXT_NORMA .='</tr>';
	}
	
}
/*===========================================================================*/

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
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;" align="CENTER" width="20%"> '.trim($pts[$i]->PERSONAL_TECNICO_ROL->ROL).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="40%"> '.trim($PT_NOMBRE_COMPLETO).'  </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="20%"> '.trim($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="20%"> '.(trim($PT_SECTORES)?trim($PT_SECTORES):'S/C').' </td>';
	$PERSONAL_TECNICO .= '</tr>';

	valida_isset($pts[$i]->PERSONAL_TECNICO_ROL->ROL, "Error: No se encuentra ROL en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO, "Error: No se encuentra REGISTRO en linea: " . __LINE__);
}
$CLAVE_CERTIFICADO = " ";
$CC_FECHA_INICIO = " ";
$CC_FECHA_FIN = " ";
$CLAVE_CERTIFICADO = $json_object->SERVICIO_CLIENTE_ETAPA->INFO_SERVICIO[0]->VALOR;
//valida_isset($CLAVE_CERTIFICADO, "Error: No se encuentra la clave certificado: " . __LINE__);
//$CC_FECHA_INICIO = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_INICIO;
valida_isset($CC_FECHA_INICIO, "Error: No se encuentra la fecha de inicio: " . __LINE__);
//$CC_FECHA_FIN = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_FIN;
valida_isset($CC_FECHA_FIN, "Error: No se encuentra la fecha final: " . __LINE__);

$FA = $json_object->SG_AUDITORIA_FECHAS;
valida_isset($FA, "Error: No se encuentra las fechas auditoria " . __LINE__);
$FECHAS_AUDITORIAS = "";
if(count($FA)>0)
{
    $FECHAS_AUDITORIAS .= substr($FA[0]->FECHA,6,8)."/".substr($FA[0]->FECHA,-4,2)."/".substr($FA[0]->FECHA,0,4);
    if(count($FA)>1)
    {
        $FECHAS_AUDITORIAS .= " - ".substr($FA[count($FA)-1]->FECHA,6,8)."/".substr($FA[count($FA)-1]->FECHA,-4,2)."/".substr($FA[count($FA)-1]->FECHA,0,4);
    }
}

/*for ($i=0; $i < count($FA) ; $i++) {

	$FECHAS_AUDITORIAS .= substr($FA[$i]->FECHA,6,8)."/".substr($FA[$i]->FECHA,-4,2)."/".substr($FA[$i]->FECHA,0,4).",";
}
    $FECHAS_AUDITORIAS = substr($FECHAS_AUDITORIAS,0,strripos($FECHAS_AUDITORIAS,","));
*/

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
		$this->setCellMargins(0,15,0,0);
		$this->Cell(0, 10, 'NOTIFICACIÓN DE SERVICIO ', 0, false, 'L', 0, '', 0, false, 'M', 'M');

		// Logo
		$image_file = 'imnc/barra.jpg';
		$this->Image($image_file, 10, 10, 170, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$image_file1 = 'imnc/logob.jpg';
		$this->Image($image_file1, 170, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
			Clave: FPOP01 <br>
			Fecha de aplicación: 2019-01-07 <br>
			Versión: 00 <br>
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

$str_direccion="Brian Perez Castro";
//$global_diffname="E:/xampp/htdocs/imnc/imnc/generar/pdf/cotizacion/";
$No5="CERTIFICACIÓN DE SISTEMAS DE GESTIÓN";
$global_diffname="imnc/";
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


$pdf1->SetFont('Helvetica', 'B', 14);
    // Title
$pdf1->Cell(0, 5, 'Notificación', 0, false, 'C', 0, '', 0, false, 'M', 'M');
$pdf1->setCellMargins(0,0,0,0);
// Titulo de documento (centrado)
$pdf1->SetXY(0,25);
// set font
$pdf1->SetFont('Calibri', 'B', 12);
$html = '<br><br><br><br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html =  '<br><div style="text-align:right;"> Ciudad de México, a  '.$LUGAR_Y_FECHA.'</div>';
$html .= '<br>';
$pdf1->writeHTML($html, true, false, true, false, 'L');
$pdf1->SetFont('Calibri', '', 10);
$html = '<div style="text-align:center;"><strong><i>'.$NOMBRE_CLIENTE.'</i></strong><br>';
$html .= '<strong><i>Dirección:&nbsp;</i></strong> '.$CALLE_Y_NUMERO.'&nbsp;'.$COLONIA_DELEGACION_Y_CP.'&nbsp;'.$ENTIDAD_FEDERATIVA.'<br>';
$html .= '<br>';
$html .= '<strong><i>'.$NOMBRE_CONTACTO.'</i></strong><br>';
$html .= ''.$CARGO_CONTACTO.'<br><br>';
$html .= '<strong><i>Teléfono:&nbsp;</i></strong> '.$TELEFONO.'<br>';
$html .= '<strong><i>Email:&nbsp;</i></strong> '.$CORREO.'</div>';
$html .= '<br>';
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html= <<<EOT
<br>
<table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000FF style="text-align:center;">
    <tr>
     <td BGCOLOR="#E0E0E0" colspan="2">
       DATOS DEL CLIENTE
     </td> 
   </tr>
	<tr>
		<td width="20%" style="font-size: medium; text-align:left" BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;No(s). De Solicitud</strong></td>
		<td width="80%" style="font-size: medium; text-align:left" > &nbsp;&nbsp;$REFERENCIA</td>
	</tr>
	<tr>
		<td width="20%" style="font-size: medium; text-align:left"  BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Sector IAF</strong></td>
		<td width="80%" style="font-size: medium; text-align:left" > &nbsp;&nbsp;$SECTORES</td>
	</tr>
	<tr>
		<td width="20%" style="font-size: medium; text-align:left"  BGCOLOR="#E0E0E0"><strong>&nbsp;&nbsp;Número de registro</strong></td>
		<td width="80%" style="font-size: medium; text-align:left"> &nbsp;&nbsp;$CLAVE_CERTIFICADO</td>
	</tr>
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 11);
$html= <<<EOT
<br><table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;">
	<tr>
		<td style="font-size: medium; text-align:center; color: #ffffff"  BGCOLOR="#1F487B"><strong>TIPO DE SERVICIO</strong></td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 9);
$html= <<<EOT
<table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;" >
	<tr>
		<td style="font-size: medium; text-align:center"  width="225"><input type="checkbox" name="chk1" value="1" $chck1 readonly="false">Servicio en instalaciones del IMNC</td>
		<td style="font-size: medium; text-align:center"  width="225"><input type="checkbox" name="chk2" value="1" $chck2  readonly="false">Servicio en Sitio</td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
$pdf1->SetFont('Calibri', '', 10);
$html= <<<EOT
<br><table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000ff style="text-align:center;" >
	<tr>
		<td style="font-size: medium; text-align:center"  BGCOLOR="#E0E0E0" width="30%"><strong>En la(s) fecha(s) siguiente(s):</strong></td>
		<td style="font-size: medium; text-align:center"   width="70%">$FECHAS_AUDITORIAS</td>
	</tr>
	
	
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');



$html= <<<EOT
<br><strong>Con el siguiente equipo asignado:</strong>
<br><table cellpadding="2" cellspacing="0"  border="1" bordercolor=#0000ff style="text-align:center;" >
	<tr>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0" width="20%"><strong>FUNCIÓN</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="40%"><strong>NOMBRE</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="20%"><strong>VALIDACIÓN</strong></td>
		<td style="font-size: medium; text-align:center" BGCOLOR="#E0E0E0"  width="20%"><strong>SECTOR(ES)</strong></td>
	</tr>
	$PERSONAL_TECNICO
</table>
<br><br>
<table cellpadding="2" cellspacing="0"  border="0" bordercolor=#ffffff style="text-align:center;" >
    <tr>
     <td BGCOLOR="#1F487B" style="color: #ffffff; text-align: left;"> Bajo el/los siguiente (s) lineamiento (s) de referencia: </td>
    </tr>
    <tr>
     <td  BGCOLOR="#E0E0E0">$TEXT_NORMA</td>
    </tr>

</table>


EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
/*$pdf1->SetPrintHeader(true);
$pdf1->AddPage();
$pdf1->SetPrintFooter(true);*/

$pdf1->SetFont('Calibri', '', 10);
$html= <<<EOT
<p style="text-align: justify"><strong>NOTA 1:</strong> El servicio que se describe en la presente Notificación, ha sido programado en apego a las “Condiciones generales” vigentes del servicio contratado, dadas a conocer previamente por el IMNC y publicadas en <a>www.imnc.org.mx</a></p>

<br><p style="text-align: justify"><strong>NOTA 2:</strong> En caso de no estar de acuerdo con la designación de algún miembro del equipo y/o fecha, el cliente debe notificarlo por escrito al IMNC, presentando las razones correspondientes, en un plazo no mayor a 3 días hábiles a partir de la recepción de esta notificación. En caso contrario se considerará como aceptado el equipo y/o fecha propuesta.</p>
EOT;
 $index = 3;
 foreach ($notasPDF as $nota)
 {
     if($nota)
     {
$html .= <<<EOT
<br><p style="text-align: justify"><strong>NOTA $index:</strong> $nota</p>
EOT;
$index ++;
     }

 }

$pdf1->writeHTML($html, true, false, true, false, 'J');



$html = <<<EOT
<br><br><table cellpadding="1" cellspacing="0" border="0" style="text-align:center;">
	<tr>
		<td style="font-size: medium; text-align:center" >Atentamente, </td>
	</tr>
</table>
EOT;
$pdf1->writeHTML($html, true, false, true, false, '');
    $pdf1->SetFont('Calibri', 'B', 10);
$pdf1->Cell(0, 0, $nombreAuxiliar, 0, false, 'C', 0, '', 0, false, 'M', 'M');
// Espacio para firmas


// ---------------------------------------------------------

//Close and output PDF document
$pdf1->Output();
// ---------------------------------------------------------
}
else
{
    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "</script>";
}

?>