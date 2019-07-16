<?php
/**
 * Created by PhpStorm.
 * User: bmyorth
 * Date: 23/02/2019
 * Time: 15:37
 */
require_once('../../../../phplibs/PHP_EXCEL/Classes/PHPExcel.php');
require_once('../../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Autoloader.php');
require_once('../../../../phplibs/PHP_EXCEL/Classes/PHPExcel/IOFactory.php');
require_once('../../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Calculation.php');
require_once('../../../../phplibs/PHP_EXCEL/Classes/PHPExcel/Cell.php');

include  '../../../../../api.imnc/imnc/common/conn-apiserver.php';
include  '../../../../../api.imnc/imnc/common/conn-medoo.php';

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
$PROSPECTOS= $_REQUEST["prospectos"];
valida_isset($PROSPECTOS,"NO SE RECIBIERON DATOS");
$PROSPECTOS = json_decode($PROSPECTOS);





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
$objDrawing->setPath('../../../../diff/imnc/logob.png');
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
$hasta = 1;
$fila = 3;
$objWorkSheet->setCellValue('A1', "REPORTE DE VENTAS");

$objPHPExcel->getActiveSheet()->mergeCells($ABCD[0].'1:'.$ABCD[$hasta]."1");
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(90);
$objWorkSheet->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'1:'.$ABCD[$hasta]."1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."1")->applyFromArray($styleborder);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getFill();
$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
$objPHPExcel->getActiveSheet()->freezePane('A3');
$status = null;
$total = 0;
foreach ($PROSPECTOS as $key=>$row)
{
    $total += floatval(substr($row->TOTAL, 1, strlen($row->TOTAL)));

    if($status!=$row->ID_STATUS)
    {
        $objWorkSheet->setCellValue($ABCD[0].$fila, $row->ESTATUS_SEGUIMIENTO);
        $objPHPExcel->getActiveSheet()
                        ->setCellValue($ABCD[1].$fila,$row->TOTAL);
                    $objPHPExcel->getActiveSheet()
                        ->getStyle($ABCD[1].$fila)
                        ->getNumberFormat()
                        ->setFormatCode('0.00' );
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':B'.$fila)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->applyFromArray($styleborder);
        $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle('A'.$fila), 'A'.$fila.':'.'B'.$fila );
        $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[1].$fila)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[1].$fila)->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setARGB('7e4808');
        $objWorkSheet->getStyle($ABCD[1].$fila)->getAlignment()->applyFromArray(
            array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
        );
        $fila++;

    }
    $objWorkSheet->setCellValue($ABCD[0].$fila, $row->NOMBRE);
    $objWorkSheet->setCellValue($ABCD[1].$fila, $row->MONTO);

    //$objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[0])->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[0])->setWidth(100);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[1])->setAutoSize(true);
    $objWorkSheet->getStyle($ABCD[1].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
    $fila++;
    $status = $row->ID_STATUS;
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

exit();
