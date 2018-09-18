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

/*//////////////////////////////////////////////////////////////////////////*/
/*					CAPTURA E INICIALIZACION DE VARIABLES					*/
/*//////////////////////////////////////////////////////////////////////////*/
function valida_isset($variable, $mensaje){
	if (!isset($variable)) {
		print_r($mensaje);
		die();
	}
}

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


$valor = false;
$json_response = file_get_contents($global_apiserver . "/sg_auditorias/getById/?completo=true&id=". $id_auditoria."&id_domicilio=".$id_domicilio);
valida_isset($json_response, "Error en la conexión a los datos para generar PDF en linea: " . __LINE__);

$json_object = json_decode($json_response);

//Datos para notificación
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


// Lugar, fecha y referencia

$LUGAR_Y_FECHA = date("d")." de ".$meses[date('n')-1]." de ". date("Y");

$REFERENCIA = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->REFERENCIA;
valida_isset($REFERENCIA, "Error: No se encuentra la REFERENCIA en linea: " . __LINE__);

$arr_sectores = $json_object->SG_TIPO_SERVICIO->SG_SECTORES; //Es arreglo
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

$obj_cliente = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->CLIENTE;
valida_isset($obj_cliente, "Error: No se encuentra obj_cliente en linea: " . __LINE__);
$obj_domicilio_fiscal = $obj_cliente->CLIENTE_DOMICILIO_FISCAL;
valida_isset($obj_domicilio_fiscal, "Error: No se encuentra obj_domicilio_fiscal en linea: " . __LINE__);

$NOMBRE_CLIENTE = $obj_cliente->NOMBRE;
valida_isset($NOMBRE_CLIENTE, "Error: No se encuentra NOMBRE_CLIENTE en linea: " . __LINE__);
$NOMBRE_CONTACTO = "AAAAAA";
//$NOMBRE_CONTACTO = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->NOMBRE_CONTACTO;
valida_isset($NOMBRE_CONTACTO, "Error: es necesario definir un domicilio fiscal y con contacto para recibir notificación: " . __LINE__);
$CARGO_CONTACTO = "BBBBBBBB";
//$CARGO_CONTACTO = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->CARGO;
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
//$telefono_fijo = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->TELEFONO_FIJO;
//valida_isset($telefono_fijo, "Error: No se encuentra telefono_fijo en linea: " . __LINE__);
//$extension = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->EXTENSION;
//valida_isset($extension, "Error: No se encuentra extension en linea: " . __LINE__);
//$email = $obj_domicilio_fiscal->CLIENTE_DOMICILIO_CONTACTO_PRINCIPAL->EMAIL;
//valida_isset($email, "Error: No se encuentra email en linea: " . __LINE__);

$CALLE_Y_NUMERO = $calle . " No. ". $numero_exterior;

$COLONIA_DELEGACION_Y_CP = "Col. " . $colonia_barrio . " " . $delegacion_municipio . ", C.P. " . $cp;

$ENTIDAD_FEDERATIVA = $entidad_federativa;

//$TELEFONO_Y_EXTENSION = "Tel. " . $telefono_fijo . " ext. " . $extension;

//$CORREO = $email;

$TRAMITE = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->ETAPA_PROCESO->ETAPA;
valida_isset($TRAMITE, "Error: No se encuentra TRAMITE en linea: " . __LINE__);

$ID_ETAPA = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->ETAPA_PROCESO->ID_ETAPA;
$ID_SERVICIO = $json_object->SG_TIPO_SERVICIO->SERVICIO_CLIENTE_ETAPA->ID;

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
//	$PT_SECTORES = 35;
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->NOMBRE, "Error: No se encuentra NOMBRE en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_PATERNO, "Error: No se encuentra APELLIDO_PATERNO en linea: " . __LINE__);
	valida_isset($pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_MATERNO, "Error: No se encuentra APELLIDO_MATERNO en linea: " . __LINE__);

	$PERSONAL_TECNICO .= '<tr style="text-align:RIGHT;">';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;" align="CENTER" width="270"> '.trim($pts[$i]->PERSONAL_TECNICO_ROL->ROL).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="170"> '.trim($PT_NOMBRE_COMPLETO).'  </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="124"> '.trim($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO).' </td>';
	$PERSONAL_TECNICO .= '	<td style="font-size: medium;"  align="CENTER" width="74"> '.trim($PT_SECTORES).' </td>';
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
$posX=0;$posY=0;
//$ID_ETAPA = 14;
switch ($ID_ETAPA){
case 1:
	$posX = 25;
	$posY = 149;
	break;
case 2:
	$posX = 25;
	$posY = 153.8;
	break;
case 3:
	$posX = 0;
	$posY = 0;
	break;
case 4:
	$posX = 25;
	$posY = 158.8;
	break;
case 5:
	$posX = 25;
	$posY = 163.8;
	break;
case 6:
	$posX = 25;
	$posY = 168.8;
	break;
case 7:
	$posX = 25;
	$posY = 173.0;
	break;	
case 8:
	$posX = 25;
	$posY = 178.0;
	break;
case 9:
	$posX = 25;
	$posY = 182.5;
	break;	
case 10:
	$posX = 25;
	$posY = 187.0;
	break;
case 11:
	$posX = 25;
	$posY = 191.5;
	break;
case 12:
	$posX = 25;
	$posY = 196;
	break;		
case 13:
	$posX = 25;
	$posY = 201;
	break;	
case 14:
	$posX = 25;
	$posY = 205.5;
	$ID_CAMBIO = $database->get("SERVICIO_CLIENTE_CAMBIO", "ID_CAMBIO", ["ID_SERVICIO_CLIENTE"=>$ID_SERVICIO]);
	valida_error_medoo_and_die();
	switch($ID_CAMBIO){
		case 1:
			$posX1 = 108;
			$posY1 = 163.8;
			$valor = true;
			break;
		case 2:
			$posX1 = 108;
			$posY1 = 154;
			$valor = true;
			break;
		case 3:
			$posX1 = 108;
			$posY1 = 168.5;
			$valor = true;
			break;
		case 4:
			$posX1 = 108;
			$posY1 = 158.5;
			$valor = true;
			break;
		case 5:
			$posX1 = 108;
			$posY1 = 173.0;
			$valor = true;
			break;
		case 6;
			$posX1 = 108;
			$posY1 = 177.5;
			$valor = true;
			break;
	
	}
	$aaa=$ID_CAMBIO;
	
	break;	
case 15:
	$posX = 25;
	$posY = 210.0;
	break;	
case 16:
	$posX = 25;
	$posY = 214.5;
	break;	
}


/*/////////////////////////////////////////////////////////////////////////*/

$pdf = new Fpdi();
///////////////////////////////////////////
$pdf->AddFont('Calibri','','calibri.php');
$pdf->AddFont('Calibri','B','calibrib.php');
$pdf->AddFont('Calibri','I','calibrii.php');
$pdf->AddFont('Calibri','BI','calibribi.php');
$pdf->AddFont('Calibril','','calibril.php');
///////////////////////////////////////////
$file = 'Plantilla2.pdf';
$pageCount = $pdf->setSourceFile($file);
///////////////////////////////////////////////////////////////////
//Pagina 1
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(1, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);

//Texto No1
$pdf->SetFont('Calibri','B',10);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(20,45);
$pdf->MultiCell(0,0,utf8_decode($NOMBRE_CLIENTE),0,'');
$pdf->SetXY(20,50);
$pdf->MultiCell(0,0,utf8_decode($CALLE_Y_NUMERO),0,'');
$pdf->SetXY(20,55);
$pdf->MultiCell(0,0,utf8_decode($COLONIA_DELEGACION_Y_CP),0,'');
$pdf->SetXY(20,60);
$pdf->MultiCell(0,0,utf8_decode($ENTIDAD_FEDERATIVA),0,'');
$pdf->SetXY(20,70);
$pdf->MultiCell(0,0,utf8_decode($NOMBRE_CONTACTO),0,'');
$pdf->SetXY(20,75);
$pdf->MultiCell(0,0,utf8_decode($CARGO_CONTACTO),0,'');

$pdf->SetFont('Calibri','',11);
$pdf->SetXY(-55,50);
$pdf->MultiCell(0,0,utf8_decode($LUGAR_Y_FECHA),0,'');
$pdf->SetFont('Calibri','',10);
$pdf->SetXY(66,99.5);
$pdf->MultiCell(0,0,utf8_decode($REFERENCIA),0,'');
$pdf->SetXY(66,104.2);
$pdf->MultiCell(0,0,utf8_decode($SECTORES),0,'');
$pdf->SetXY(66,113);
$pdf->MultiCell(0,0,utf8_decode($CLAVE_CERTIFICADO),0,'');
$pdf->SetXY(66,118);
$pdf->MultiCell(0,0,utf8_decode($CC_FECHA_INICIO),0,'');
$pdf->SetXY(146,118);
$pdf->MultiCell(0,0,utf8_decode($CC_FECHA_FIN),0,'');

$pdf->SetXY(90,228);
$pdf->MultiCell(0,0,utf8_decode($FECHAS_AUDITORIAS),0,'');

$pdf->SetXY($posX,$posY);
$pdf->MultiCell(0,0,"x",0,'');

if($valor == true){
	$pdf->SetXY(101,149);
	$pdf->MultiCell(0,0,"x",0,'');
	$pdf->SetXY($posX1,$posY1);
	$pdf->MultiCell(0,0,"x",0,'');
}
///////////////////////////////////////////////////////////////////
//Pagina 2
///////////////////////////////////////////////////////////////////
$pdf->addPage();
$pageId = $pdf->importPage(2, PdfReader\PageBoundaries::MEDIA_BOX);
$pdf->useImportedPage($pageId);
$pdf->SetFont('Calibri','',9);
$pdf->SetTextColor(0,0,0);
for ($i=0; $i < count($pts) ; $i++) { 
	$PT_NOMBRE_COMPLETO = $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->NOMBRE . " " . $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_PATERNO . " " . $pts[$i]->PERSONAL_TECNICO_CALIFICACION->PERSONAL_TECNICO->APELLIDO_MATERNO;
	$CADENA_ROL = $pts[$i]->PERSONAL_TECNICO_ROL->ROL;
	$ARR_ROL = explode(" ",$CADENA_ROL);
	if(count($ARR_ROL)<4){
		$pdf->SetXY(28,45+$i*9);
		$pdf->MultiCell(0,0,utf8_decode($pts[$i]->PERSONAL_TECNICO_ROL->ROL),0,'');
	}
	else{
		$cadena1="";$cadena2="";
		
		$pdf->SetXY(28,45+$i*9);
		$pdf->MultiCell(0,0,utf8_decode($ARR_ROL[0]." ".$ARR_ROL[1]." ".$ARR_ROL[2]),0,'');
		$pdf->SetXY(28,45+$i*9+4);
		for($j=4;$j<count($ARR_ROL);$j++){
			$cadena1 .= $ARR_ROL[$j]." ";
		}
		$pdf->MultiCell(0,0,utf8_decode($cadena1),0,'');
	}
	$pdf->SetXY(68,45+$i*9);
	$pdf->MultiCell(0,0,utf8_decode($PT_NOMBRE_COMPLETO),0,'');
	$pdf->SetXY(135,45+$i*9);
	$pdf->MultiCell(0,0,utf8_decode($pts[$i]->PERSONAL_TECNICO_CALIFICACION->REGISTRO),0,'');
	$pdf->SetXY(165,45+$i*9);
	$pdf->MultiCell(0,0,utf8_decode($PT_SECTORES),0,'');
	
}
$pdf->SetXY(25,100);
$pdf->MultiCell(0,0,utf8_decode($NORMA),0,'');
if($nota1PDF!=""){
/*	$html = <<<EOT
	<b>NOTA 5:</b> $nota1PDF
EOT;
	$pdf->SetXY(25,170);*/
//	$pdf->writeHTML(utf8_decode($html));

	$pdf->SetFont('Calibri','B',9);
	$pdf->SetXY(24,170);
	
	$pdf->Cell(0,0,"NOTA 5:",0,'');
	
	$pdf->SetFont('Calibri','',9);
	/***************************************************/
	
	$pdf->SetXY(24,168.5);
	$pdf->MultiCell(165,3,"NOTA 5: ".utf8_decode($nota1PDF),0,'J');
	
	/***************************************************/
	//$pdf->SetXY(36,170);
	//$pdf->MultiCell(90,2,utf8_decode($nota1PDF),0,'');
	//$pdf->Write(0,utf8_decode($nota1PDF));
	
}
if($nota2PDF!=""){
/*	$html = <<<EOT
	<b>NOTA 6:</b> $nota2PDF
EOT;
	$pdf->SetXY(25,180);
	//$pdf->writeHTML(utf8_decode($html));
	$pdf->MultiCell(0,0,utf8_decode($html),0,'');*/
	$pdf->SetFont('Calibri','B',9);
	$pdf->SetXY(24,180);
	$pdf->MultiCell(0,0,"NOTA 6:",0,'');
	
	$pdf->SetFont('Calibri','',9);
	/***************************************************/
	
	$pdf->SetXY(24,178.5);
	$pdf->MultiCell(165,3,"NOTA 6: ".utf8_decode($nota2PDF),0,'J');
	
	/***************************************************/
	//$pdf->SetXY(36,180);
	//$pdf->MultiCell(0,0,utf8_decode($nota2PDF),0,'');
}	
if($nota3PDF!=""){
/*	$html = <<<EOT
	<b>NOTA 7:</b> $nota3PDF
EOT;
	$pdf->SetXY(25,190);
	//$pdf->writeHTML(utf8_decode($html));
	$pdf->MultiCell(0,0,utf8_decode($html),0,'');*/
	$pdf->SetFont('Calibri','B',9);
	$pdf->SetXY(24,190);
	$pdf->MultiCell(0,0,"NOTA 7:",0,'');
	
	$pdf->SetFont('Calibri','',9);
	/***************************************************/
	
	$pdf->SetXY(24,188.5);
	$pdf->MultiCell(165,3,"NOTA 7: ".utf8_decode($nota3PDF),0,'J');
	
	/***************************************************/
//	$pdf->SetXY(36,190);
//	$pdf->MultiCell(0,0,utf8_decode($nota3PDF),0,'');
}	

$pdf->Output();


?>