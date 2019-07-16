<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 23/02/2019
 * Time: 15:37
 */
require_once('../../../phplibs/PHP_EXCEL/Classes/PHPExcel.php');
require_once('../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Autoloader.php');
require_once('../../../phplibs/PHP_EXCEL/Classes/PHPExcel/IOFactory.php');
require_once('../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Calculation.php');
require_once('../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Cell.php');

include  '../../../../api.imnc/imnc/common/conn-apiserver.php';
include  '../../../../api.imnc/imnc/common/conn-medoo.php';

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
        die();
    }
}

function valida_isset($variable, $mensaje){
    if (!isset($variable)) {
        print_r($mensaje);
        die();
    }
}

$NOMBRE = $_REQUEST["NOMBRE"];
valida_isset($NOMBRE,"NO PUEDE SER VACIO");
$AREA = $_REQUEST["AREA"];
valida_isset($AREA,"NO PUEDE SER VACIO");
$COLUMNAS= $_REQUEST["COLUMNAS"];
valida_isset($COLUMNAS,"NO PUEDE SER VACIO");


$search = array("[","]","{","}");
$COLUMNS = str_replace($search,"",$COLUMNAS);

$COLUMNAS = explode(",",$COLUMNS);
$COLUMNAS = str_replace("*",",",$COLUMNAS);


$sql = "";
$TEXTS = array();
$SELECTS = array();


$SELECT = "";
//$ORDER = "";
for ($i = 0 ; $i <  count($COLUMNAS) ; $i+=2)
{
    $campo_text = explode(":",$COLUMNAS[$i]);
    $campo_select = explode(":",$COLUMNAS[$i+1]);
    $TEXTS [] = $campo_text[1];
    $SELECTS [] = $par = explode("|",$campo_select[1]);
    $SELECT .= $par[0].",";
    //$ORDER .=  explode(" AS ",$par[0])[1].",";
}
$SELECT = str_replace('"','',$SELECT);
$SELECT = substr($SELECT, 0, -1);
//$ORDER = substr($ORDER, 0, -1);

if(strtolower($AREA)=="comercial")
{
    $FROM = " FROM 
            PROSPECTO P
            LEFT JOIN PROSPECTO_PRODUCTO PP
            ON P.ID = PP.ID_PROSPECTO
            LEFT JOIN SERVICIOS S 
            ON PP.ID_SERVICIO = S.ID
            LEFT JOIN TIPOS_SERVICIO TS
            ON PP.ID_TIPO_SERVICIO = TS.ID
            LEFT JOIN PROSPECTO_PRODUCTO_NORMAS PPN
            ON PP.ID = PPN.ID_PRODUCTO
            LEFT JOIN PROSPECTO_DOMICILIO PD 
            ON P.ID = PD.ID_PROSPECTO
            LEFT JOIN PROSPECTO_SECTORES PS 
            ON PP.ID = PS.ID_PRODUCTO
            LEFT JOIN SECTORES SEC 
            ON PS.ID_SECTOR = SEC.ID_SECTOR
            LEFT JOIN COTIZACIONES COT 
            ON P.ID = COT.ID_PROSPECTO
            LEFT JOIN PROSPECTO_ESTATUS_SEGUIMIENTO PES
            ON COT.ESTADO_COTIZACION = PES.ID
			LEFT JOIN COTIZACIONES_STATUS_FECHA CSF
			ON COT.ID = CSF.ID_COTIZACION";

    //$sql = "SELECT ".$SELECT.$FROM." ORDER BY ".$ORDER;
    $sql = "SELECT DISTINCT ".$SELECT.$FROM;

}

if(strtolower($AREA)=="programación")
{
    $FROM = "   FROM SERVICIO_CLIENTE_ETAPA SCE 
                LEFT JOIN SERVICIOS S 
                ON SCE.ID_SERVICIO = S.ID
                LEFT JOIN TIPOS_SERVICIO TS 
                ON SCE.ID_TIPO_SERVICIO = TS.ID 
                LEFT JOIN SCE_NORMAS 
                ON SCE.ID = SCE_NORMAS.ID_SCE
                LEFT JOIN CLIENTES C 
                ON C.ID = SCE.ID_CLIENTE
                LEFT JOIN CLIENTES_DOMICILIOS CD 
                ON CD.ID_CLIENTE = SCE.ID_CLIENTE
                LEFT JOIN I_SG_AUDITORIAS SGA
                ON SCE.ID = SGA.ID_SERVICIO_CLIENTE_ETAPA
                LEFT JOIN I_SG_SECTORES SGS
                ON SCE.ID = SGS.ID_SERVICIO_CLIENTE_ETAPA
                LEFT JOIN SECTORES
                ON SECTORES.ID_SECTOR = SGS.ID_SECTOR 
                LEFT JOIN ETAPAS_PROCESO 
                ON SCE.ID_ETAPA_PROCESO = ETAPAS_PROCESO.ID_ETAPA";
    $sql = "SELECT DISTINCT ".$SELECT.$FROM;
}

if(strtolower($AREA)=="auditores")
{
    $FROM = "  FROM PERSONAL_TECNICO PT
LEFT JOIN PERSONAL_TECNICO_CALIFICACIONES PTC ON PT.ID = PTC.ID_PERSONAL_TECNICO
LEFT JOIN PERSONAL_TECNICO_ROLES PTR ON PTC.ID_ROL = PTR.ID
LEFT JOIN PERSONAL_TECNICO_CALIF_SECTOR PTCS ON PTC.ID = PTCS.ID_PERSONAL_TECNICO_CALIFICACION
LEFT JOIN SECTORES SC ON PTCS.ID_SECTOR = SC.ID_SECTOR
LEFT JOIN TIPOS_SERVICIO TS ON PTC.ID_TIPO_SERVICIO = TS.ID
LEFT JOIN SERVICIOS S ON TS.ID_SERVICIO = S.ID
ORDER BY PT.NOMBRE,PT.ID,FIELD(PTC.ID_ROL,'3','1','6','4','2','8','5','7','11','9','10','12','13','14'), S.ID,TS.ID";
    $sql = "SELECT DISTINCT ".$SELECT.$FROM;
}

$consulta = $database->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("IMNC REPORTES")
    ->setLastModifiedBy("bmyorth")
    ->setTitle("REPORTE")
    ->setSubject("REPORTE");

$styleborder= array(
    'borders' => array(
        'top'     => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000'),
        ),
        'bottom'     => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000'),
        ),
        'left'     => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000'),
        ),
        'right'     => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000'),
        ),

    )

);

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
    ));
$ABCD = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$objWorkSheet = $objPHPExcel->createSheet(0);
$objPHPExcel->setActiveSheetIndex(0);
$objWorkSheet = $objPHPExcel->getActiveSheet();
$objWorkSheet->setTitle("Reporte");


$objWorkSheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
);



$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('logo');
$objDrawing->setPath('../../../diff/imnc/logob.png');
$objDrawing->setCoordinates('A1');
//setOffsetX works properly
$objDrawing->setOffsetX(10);
$objDrawing->setOffsetY(5);
//set width, height
$objDrawing->setWidth(100);
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);

$fila = 2;
$objWorkSheet->setCellValue('A1', "REPORTE:  ".strtoupper($NOMBRE)." - ". strtoupper($AREA));

$hasta = 0;
foreach ($TEXTS as $key=>$value)
{
    $objWorkSheet->setCellValue($ABCD[$key].$fila, str_replace('"',"",$value));
    //$objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[$key])->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[$key])->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[$key].$fila)->getAlignment()->setWrapText(true);
    $hasta = $key;
}
$objPHPExcel->getActiveSheet()->mergeCells($ABCD[0].'1:'.$ABCD[$hasta]."1");
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(90);
$objWorkSheet->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'1:'.$ABCD[$hasta]."1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."2")->applyFromArray($styleborder);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."1")->applyFromArray($styleborder);
$objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."2"), $ABCD[0].'2:'.$ABCD[$hasta]."2" );
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()->setARGB('7e4808');
$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
$objPHPExcel->getActiveSheet()->freezePane('A3');
$fila = 3;

foreach ($consulta as $row)
{
    foreach ($SELECTS as $key=>$value)
    {
        $index =  explode(" AS ",$value[0])[1];
        if(str_replace('"',"",$value[1])=="date")
        {
            if($row[$index]!="")
            {
                $dateValue = PHPExcel_Shared_Date::PHPToExcel( new DateTime($row[$index]) );
                $objPHPExcel->getActiveSheet()
                    ->setCellValue($ABCD[$key].$fila, $dateValue);
                $objPHPExcel->getActiveSheet()
                    ->getStyle($ABCD[$key].$fila)
                    ->getNumberFormat()
                    ->setFormatCode("dd/mm/yyyy" );
            }
            else
            {
                $objWorkSheet->setCellValue($ABCD[$key].$fila, null);
            }

        }
        else
        {
            if(str_replace('"',"",$value[1])=="float")
            {

                    $objPHPExcel->getActiveSheet()
                        ->setCellValue($ABCD[$key].$fila, $row[$index]);
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($ABCD[$key].$fila)
                        ->getNumberFormat()
                        ->setFormatCode('0.00' );

            }else
            {
                $objWorkSheet->setCellValue($ABCD[$key].$fila, $row[$index]);
            }

        }
    }
    $fila++;

}




$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

exit();
