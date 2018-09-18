<?php
ob_start();
//error_reporting(E_ALL);
//ini_set("display_errors",1);

function valida_isset($variable, $mensaje){
	if (!isset($variable)) {
		print_r($mensaje);
		die();
	}
}


require_once('../../../common/apiserver.php'); //$global_apiserver
require_once('../../../diff/selector.php'); //$global_diffname
require_once('../../../diff/'.$global_diffname.'/strings.php'); 



$id_auditoria = $_REQUEST["ID_AUDITORIA"];
$id_domicilio = $_REQUEST["cmbDomicilioNotificacionPDF"];
$tipoNotificacionPDF = $_REQUEST["cmbTipoNotificacionPDF"];
$tipoCambiosPDF = $_REQUEST["cmbTipoCambiosPDF"];
$certificacionMantenimientoPDF = $_REQUEST["cmbCertificacionMantenimientoPDF"];
$nota1PDF = $_REQUEST["txtNota1PDF"];
$nota2PDF = $_REQUEST["txtNota2PDF"];
$nota3PDF = $_REQUEST["txtNota3PDF"];
$nombreAutorizaPDF = $_REQUEST["txtNombreAutorizaPDF"];
$cargoAutorizaPDF = $_REQUEST["txtCargoAutorizaPDF"];
$nombreAuxiliar = $_REQUEST["nombreUsuario"];


$json_response = file_get_contents($global_apiserver . "/sg_auditorias/getById/?completo=true&id=". $id_auditoria."&id_domicilio=".$id_domicilio);
valida_isset($json_response, "Error en la conexión a los datos para generar PDF en linea: " . __LINE__);

$json_object = json_decode($json_response);

//Datos para notificación
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


// Lugar, fecha y referencia

$LUGAR_Y_FECHA = $str_lugar . ", a ".date("d")." de ".$meses[date('n')-1]." de ". date("Y");

$REFERENCIA = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->REFERENCIA;
valida_isset($REFERENCIA, "Error: No se encuentra la REFERENCIA en linea: " . __LINE__);

$arr_sectores = $json_object->SG_TIPO_SERVICIO->SG_SECTORES; //Es arreglo
valida_isset($arr_sectores, "Error: No se encuentra arr_sectores en linea: " . __LINE__);

$SECTORES = "Sector IAF: ";
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

// Datos de contacto y domicilio

$obj_cliente = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->CLIENTE;
valida_isset($obj_cliente, "Error: No se encuentra obj_cliente en linea: " . __LINE__);
$obj_domicilio_fiscal = $obj_cliente->CLIENTE_DOMICILIO_FISCAL;
valida_isset($obj_domicilio_fiscal, "Error: No se encuentra obj_domicilio_fiscal en linea: " . __LINE__);

$NOMBRE_CLIENTE = $obj_cliente->NOMBRE;
valida_isset($NOMBRE_CLIENTE, "Error: No se encuentra NOMBRE_CLIENTE en linea: " . __LINE__);
$NOMBRE_CONTACTO = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->NOMBRE_CONTACTO;
valida_isset($NOMBRE_CONTACTO, "Error: es necesario definir un domicilio fiscal y con contacto para recibir notificación: " . __LINE__);
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

$TELEFONO_Y_EXTENSION = "Tel. " . $telefono_fijo . " ext. " . $extension;

$CORREO = $email;

$TRAMITE = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->ETAPA_PROCESO->ETAPA;
valida_isset($TRAMITE, "Error: No se encuentra TRAMITE en linea: " . __LINE__);

$NORMA = $json_object->SG_TIPO_SERVICIO->NORMA->ID;
valida_isset($NORMA, "Error: No se encuentra NORMA en linea: " . __LINE__);

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

	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->NOMBRE, "Error: No se encuentra NOMBRE en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_PATERNO, "Error: No se encuentra APELLIDO_PATERNO en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_MATERNO, "Error: No se encuentra APELLIDO_MATERNO en linea: " . __LINE__);

	$PERSONAL_TECNICO .= '<tr style="text-align:justify;">';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"> '.trim($pts[$i]->PERSONAL_TECNICO_ROL->ROL).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"> '.trim($PT_NOMBRE_COMPLETO).'  </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"> '.trim($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"> '.trim($PT_SECTORES).' </td>';
	$PERSONAL_TECNICO .= '</tr>';

	valida_isset($pts[$i]->PERSONAL_TECNICO_ROL->ROL, "Error: No se encuentra ROL en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO, "Error: No se encuentra REGISTRO en linea: " . __LINE__);
}

	


?>
<?php

ob_start();
//error_reporting(E_ALL);
//ini_set("display_errors",1);

// Include the main TCPDF library (search for installation path).
//print_r("ok");

require_once('../../../phplibs/libPDF/tcpdf.php');
//print_r($global_apiserver);
//die();

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	private $selector;
	private $direccion;
	private $certificacion;

	function __construct($CERTIFICACION, $DIRECCION, $SELECTOR, $PDF_PAGE_ORIENTATION, $PDF_UNIT, $PDF_PAGE_FORMAT, $B1, $coding, $B2) {
       parent::__construct($PDF_PAGE_ORIENTATION, $PDF_UNIT, $PDF_PAGE_FORMAT, $B1, $coding, $B2);
       $this->selector = $SELECTOR;
       $this->direccion = $DIRECCION;
       $this->certificacion = strtoupper($CERTIFICACION);
   }

	//Page header
	public function Header() {
		// Set font
		$this->SetFont('helvetica', 'B', 14);
		// Title
		$this->setCellMargins(0,10,0,0);
		$this->Cell(0, 20, 'NOTIFICACIÓN DE '.$this->certificacion.' DE SISTEMAS DE GESTIÓN', 0, false, 'L', 0, '', 0, false, 'M', 'M');
		// Logo
		$image_file = $this->selector . '/header.jpg';
		$this->Image($image_file, 180, 10, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-20);
		// Set font
		// Page number
		//$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

		$image_file = $this->selector . '/footer.jpg';
		//$this->SetY(0);
		$this->SetFont('Helvetica', '', 8);

		// Lugar, fecha y claves (alineado a la derecha)
$html = <<<EOD
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td style="font-size: small; text-align:center;" width="125">
			<img src="$image_file"  width="110" height="40" border="0" />
		</td>
		<td style="font-size: small; text-align:center;" width="388">
			$this->direccion;
		</td>
		<td style="font-size: small; text-align:rigth;" width="125"> 
			Clave: FPEC14 <br>
			Fecha de aplicación: 2017-06-28 <br>
			Versión: 02 <br>
			Página 1 de 1
		</td>
	</tr>
</table>
EOD;

		$this->writeHTML($html, true, false, true, false, '');
	}
}


// create new PDF document
$pdf = new MYPDF($str_certificacion, $str_direccion, $global_diffname, PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('--');
$pdf->SetTitle('Notificación de servicio');
$pdf->SetSubject('--');
$pdf->SetKeywords('--');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// -------------------  COMIENZA DOCUMENTO ------------------------------

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 8);


// Titulo de documento (centrado)
$html = '<div style="text-align:center;"><h3>'.$tipoNotificacionPDF.' </h3></div>';
$pdf->writeHTML($html, true, false, true, false, '');


// Lugar, fecha y claves (alineado a la derecha)
$html = '<div style="text-align:rigth; font-size: medium;">';
$html .= '		'.$LUGAR_Y_FECHA.'<br>';
$html .= '		'.$REFERENCIA.'<br>';
$html .= '		'.$SECTORES;
$html .= '</div>';
$pdf->writeHTML($html, true, false, true, false, '');


// Dirigido a... y dirección (alineado a la izquierda)
$html = <<<EOT
<div style="text-align:left; font-size: medium;">
	<strong>$NOMBRE_CONTACTO<br>
		$CARGO_CONTACTO<br>
		$NOMBRE_CLIENTE<br>
		$CALLE_Y_NUMERO<br>
		$COLONIA_DELEGACION_Y_CP<br>
		$ENTIDAD_FEDERATIVA<br>
		$TELEFONO_Y_EXTENSION<br>
		$CORREO<br>
	</strong>
</div>
EOT;
$pdf->writeHTML($html, true, false, true, false, '');


// Cuerpo del mensaje (justificado)
$html = '<div style="text-align:justify; font-size: large;">';
$html .= '<br>De acuerdo al programa de '.strtolower($str_auditoria).' '. $tipoCambiosPDF .'  para llevar a cabo  '. $certificacionMantenimientoPDF .', le informo que la '. $TRAMITE . '  a su sistema de gestión bajo la norma ' . $NORMA . '  se realizará de conformidad a lo siguiente:<br>';
$pdf->writeHTML($html, true, false, true, false, '');

$str_auditoria_minus = strtolower($str_auditoria);
$str_auditoria_mayus = strtoupper($str_auditoria);
// Tabla de audioria
$tramite_uppercase = strtoupper($TRAMITE);
$html = <<<EOT
<table cellpadding="3" cellspacing="0" border="1" style="text-align:center;">
	<tr style="background-color: #BDBDBD;">
		<th style="font-size: large;" colspan="4"><strong>$tramite_uppercase</strong></th>
	</tr>
	<tr>
		<td style="font-size: large;" width="270"><strong> CATEGORÍA </strong></td>
		<td style="font-size: large;" width="170"><strong> NOMBRE </strong></td>
		<td style="font-size: large;" width="124"><strong> VALIDACIÓN </strong></td>
		<td style="font-size: large;" width="74"><strong> SECTOR </strong></td>
	</tr>
	$PERSONAL_TECNICO
	<tr style="text-align:justify; background-color: #BDBDBD;">
		<th style="font-size: medium;" colspan="4">
			<strong> Fecha de $str_auditoria_minus: del $FECHA_INICIO_AUDITORIA al $FECHA_FIN_AUDITORIA  </strong>
		</th>
	</tr>
</table>
EOT;
$pdf->writeHTML($html, true, false, true, false, '');

if ($json_object->SG_AUDITORIA_CERTIFICADO){ //verifica que tenga CERTIFICADO
	$num_registro_certificado = $json_object->SG_AUDITORIA_CERTIFICADO->CLAVE;
	$fecha_aux = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_INICIO_ACREDITACION;
	$fecha_inicio_certificado = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));
	$fecha_aux = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_FIN_ACREDITACION;
	$fecha_fin_certificado = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));
	$fecha_aux = $json_object->SG_AUDITORIA_CERTIFICADO->FECHA_RENOVACION;
	$fecha_renovacion_certificado = date('d',strtotime($fecha_aux)) . " de " . $meses[date('n',strtotime($fecha_aux))-1] . " de " . date('Y',strtotime($fecha_aux));
	$periodicidad_certificado = $json_object->SG_AUDITORIA_CERTIFICADO->PERIODICIDAD;
	$status_certificado = $json_object->SG_AUDITORIA_CERTIFICADO->STATUS;
	// Datos adicionales
	$html = <<<EOT
<div style="text-align:left; font-size: large;">
Número de registro: $num_registro_certificado<br>
		Fecha de certificación inicial: $fecha_inicio_certificado  <br>
		Fecha de recertificación: $fecha_renovacion_certificado <br>
		Fecha de expiración: $fecha_fin_certificado <br>
		Periodicidad: $periodicidad_certificado meses <br>
		Status: $status_certificado 
	</div>
	<br>
EOT;
	$pdf->writeHTML($html, true, false, true, false, '');
}



// Tabla de visitas futuras
// $html = <<<EOT
// {EN CONSTRUCCIÓN}<br>
// <table cellpadding="3" cellspacing="0" border="1" style="text-align:justify;">
// 	<tr>
// 		<td style="font-size: medium;" width="319"><strong> 2ª $str_auditoria_mayus DE VIGILANCIA ANUAL  </strong></td>
// 		<td style="font-size: medium;" width="319"><strong> Fecha recomendada de $str_auditoria_minus: 2017-07-21 </strong></td>
// 	</tr>
// 	<tr>
// 		<td style="font-size: medium;" width="319"><strong> RECERTIFICACIÓN  </strong></td>
// 		<td style="font-size: medium;" width="319"><strong> Fecha recomendada de $str_auditoria_minus: 2018-07-18 </strong></td>
// 	</tr>
// </table>
// EOT;
// $pdf->writeHTML($html, true, false, true, false, '');
$nota1html = "";
if ($nota1PDF != "") {
	$nota1html = <<<EOT
	<strong>NOTA 3:</strong> $nota1PDF
EOT;
}

$nota2html = "";
if ($nota2PDF != "") {
	$nota2html = <<<EOT
	<strong>NOTA 4:</strong> $nota2PDF
EOT;
}

$nota3html = "";
if ($nota3PDF != "") {
	$nota3html = <<<EOT
	<strong>NOTA 5:</strong> $nota3PDF
EOT;
}

// Notas finales
$html = <<<EOT
<div style="text-align:left; font-size: medium;">
<strong>NOTA 1:</strong> Para el caso de las auditorias de vigilancias para el mantenimiento de su certificado deberá confirmar las fechas 30 días naturales antes de la fecha máxima, en caso contrario la auditoría se programara en las fechas que el IMNC tenga disponibles.
<br><br>
<strong>NOTA 2:</strong> Para el caso de la auditoria de renovación se recomienda que ésta se lleve a cabo con al menos tres meses antes de la fecha de expiración de su registro.
<br><br>
$nota1html
<br><br>
$nota2html
<br><br>
$nota3html
<br><br>
Por lo anterior solicito visto bueno.
</div>
<br>
EOT;
$pdf->writeHTML($html, true, false, true, false, '');


// Espacio para firmas
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
				________________________________________________<br>$nombreAuxiliar<br>Auxiliar de certificación
  			</strong>
  		</td>
		<td style="font-size: medium;" width="38"></td>
		<td style="font-size: medium;" width="319">
			<strong>
				________________________________________________<br>$nombreAutorizaPDF<br>$cargoAutorizaPDF
  			</strong>
  		</td>
	</tr>
</table>
EOT;
$pdf->writeHTML($html, true, false, true, false, '');




// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('notificacion.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+