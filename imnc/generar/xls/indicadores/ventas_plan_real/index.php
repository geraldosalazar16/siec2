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
$MES= $_REQUEST["mes"];
valida_isset($MES,"NO SE RECIBIERON DATOS");
$flag= $_REQUEST["flag"];
valida_isset($flag,"NO SE RECIBIERON DATOS");
$objetivos= $_REQUEST["objetivos"];
valida_isset($objetivos,"NO SE RECIBIERON DATOS");
$objetivos = json_decode($objetivos);


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
$ABCD = array("B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$objWorkSheet = $objPHPExcel->createSheet(0);
$objPHPExcel->setActiveSheetIndex(0);
$objWorkSheet = $objPHPExcel->getActiveSheet();
$objWorkSheet->setTitle("Reporte");


$objWorkSheet->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
);



$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
$hasta = 5;
$fila = 2;
$objWorkSheet->setCellValue($ABCD[0]."1", "RESULTADO DE VENTAS - ".$MES);
if($flag==1) {
    $objPHPExcel->getActiveSheet()->mergeCells($ABCD[0] . '1:' . $ABCD[5] . "1");
}
if($flag==2) {
    $objPHPExcel->getActiveSheet()->mergeCells($ABCD[0] . '1:' . $ABCD[4] . "1");
}
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(90);
$objWorkSheet->getStyle($ABCD[0].'1:'.$ABCD[$hasta]."1")->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."1")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'1:'.$ABCD[$hasta]."1")->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle($ABCD[0]."1")->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle($ABCD[0].'2:'.$ABCD[$hasta]."2")->getFill();
//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
$tipo = null;

$totalE = 0;
$totalG = 0;
$totalAE = 0;
$totalAG = 0;
$totalAC = 0;
$tlE = 0;
$tlG = 0;
$tlAE = 0;
$tlAG = 0;
$tlAC = 0;
$start = true;
$acumulado = '';
if($flag == 1)
{
    $acumulado = 'DICIEMBRE';
}
if($flag == 2)
{
    $MES = strtoupper($MES);
    $acumulado = $MES;
}


if($flag == 1) {
    $objWorkSheet->setCellValue($ABCD[0] . $fila, $row->NOMBRE);
    $objWorkSheet->setCellValue($ABCD[1] . $fila, "ENERO - " . $acumulado);
    $objPHPExcel->getActiveSheet()->mergeCells($ABCD[1].$fila.':'.$ABCD[5].$fila);
    $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[5].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[5].$fila );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[1].$fila)->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCCCCC');
    $objWorkSheet->getStyle($ABCD[0].$fila.':'.$ABCD[0].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
    $fila++;
    $objWorkSheet->setCellValue($ABCD[1] . $fila, "PROPUESTAS EMITIDAS");
    $objWorkSheet->setCellValue($ABCD[2] . $fila, "OBJETIVO EMITIDAS");
    $objWorkSheet->setCellValue($ABCD[3] . $fila, "PROPUESTAS GANADAS");
    $objWorkSheet->setCellValue($ABCD[4] . $fila, "OBJETIVO GANADAS");
    $objWorkSheet->setCellValue($ABCD[5] . $fila, "PROPUESTAS ACTIVAS");
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[5].$fila );
    $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[5].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
}
if($flag == 2)
{
    $objWorkSheet->setCellValue($ABCD[0] . $fila, '');
    $objWorkSheet->setCellValue($ABCD[1] . $fila, $MES);
    $objPHPExcel->getActiveSheet()->mergeCells($ABCD[1].$fila.':'.$ABCD[4].$fila);
    $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[4].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[4].$fila );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[4].$fila)->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setARGB('CCCCCC');
    $objWorkSheet->getStyle($ABCD[0].$fila.':'.$ABCD[0].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
    $fila++;
    $objWorkSheet->setCellValue($ABCD[1] . $fila, "PROPUESTAS EMITIDAS");
    $objWorkSheet->setCellValue($ABCD[2] . $fila, "OBJETIVO EMITIDAS");
    $objWorkSheet->setCellValue($ABCD[3] . $fila, "PROPUESTAS GANADAS");
    $objWorkSheet->setCellValue($ABCD[4] . $fila, "OBJETIVO GANADAS");
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[4].$fila );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila)->getAlignment()->setWrapText(true);
    $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[4].$fila)->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
    );
}
$fila++;
foreach ($PROSPECTOS as $key=>$row)
{

    if($tipo!=$row->TIPO)
    {
        if($flag==1) {
            $objWorkSheet->setCellValue($ABCD[0].$fila, $row->TIPO);
            $objPHPExcel->getActiveSheet()
                ->setCellValue($ABCD[1].$fila,$row->TOTALE);
            $objPHPExcel->getActiveSheet()
                ->getStyle($ABCD[1].$fila)
                ->getNumberFormat()
                ->setFormatCode('0.00' );
            $objWorkSheet->setCellValue($ABCD[2].$fila, '');
            $objPHPExcel->getActiveSheet()
                ->setCellValue($ABCD[3].$fila,$row->TOTALG);
            $objPHPExcel->getActiveSheet()
                ->getStyle($ABCD[3].$fila)
                ->getNumberFormat()
                ->setFormatCode('0.00' );
            $objWorkSheet->setCellValue($ABCD[4].$fila, '');
            $objPHPExcel->getActiveSheet()
                ->setCellValue($ABCD[5].$fila,$row->ACTIVAS);
            $objPHPExcel->getActiveSheet()
                ->getStyle($ABCD[5].$fila)
                ->getNumberFormat()
                ->setFormatCode('0.00' );
            $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
            $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[5].$fila );
            $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[5].$fila)->applyFromArray($styleborder);
            $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[5].$fila)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
            );
            $objWorkSheet->getStyle($ABCD[0].$fila.':'.$ABCD[0].$fila)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
            );

        }
        if($flag==2) {
            $objWorkSheet->setCellValue($ABCD[0].$fila, $row->TIPO);
            $objPHPExcel->getActiveSheet()
                ->setCellValue($ABCD[1].$fila,$row->TOTALE);
            $objPHPExcel->getActiveSheet()
                ->getStyle($ABCD[1].$fila)
                ->getNumberFormat()
                ->setFormatCode('0.00' );
            $objWorkSheet->setCellValue($ABCD[2].$fila, '');
            $objPHPExcel->getActiveSheet()
                ->setCellValue($ABCD[3].$fila,$row->TOTALG);
            $objPHPExcel->getActiveSheet()
                ->getStyle($ABCD[3].$fila)
                ->getNumberFormat()
                ->setFormatCode('0.00' );
            $objWorkSheet->setCellValue($ABCD[4].$fila, '');

            $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
            $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[4].$fila );
            $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[4].$fila)->applyFromArray($styleborder);
            $objWorkSheet->getStyle($ABCD[1].$fila.':'.$ABCD[4].$fila)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
            );
            $objWorkSheet->getStyle($ABCD[0].$fila.':'.$ABCD[0].$fila)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,)
            );


        $TIPO = $row->TIPO;
    }


    }

    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[0])->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[1])->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[2])->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[3])->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[4])->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension($ABCD[5])->setWidth(25);



    $fila++;
    $totalE += $row->TOTALE;
    $totalG += $row->TOTALG;
    $totalAC += $row->ACTIVAS;

}
if($flag == 1)
{
    $objWorkSheet->setCellValue($ABCD[0].$fila, "TOTAL");
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[1].$fila,$totalE);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[1].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[2].$fila,$objetivos->OBJETIVOS->E);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[2].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[3].$fila,$totalG);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[3].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[4].$fila,$objetivos->OBJETIVOS->G);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[4].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[5].$fila,$totalAC);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[5].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[5].$fila );

    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[$hasta].$fila)->getFont()->setBold(true);
    $fila = $fila+2;
}
if($flag == 2)
{
    $objWorkSheet->setCellValue($ABCD[0].$fila, "TOTAL");
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[1].$fila,$totalE);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[1].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[2].$fila,$objetivos->OBJETIVOS->E);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[2].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[3].$fila,$totalG);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[3].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()
        ->setCellValue($ABCD[4].$fila,$objetivos->OBJETIVOS->G);
    $objPHPExcel->getActiveSheet()
        ->getStyle($ABCD[4].$fila)
        ->getNumberFormat()
        ->setFormatCode('0.00' );
    $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila)->applyFromArray($styleborder);
    $objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle($ABCD[1].$fila), $ABCD[0].$fila.':'.$ABCD[4].$fila );

    $objPHPExcel->getActiveSheet()->getStyle($ABCD[0].$fila.':'.$ABCD[4].$fila)->getFont()->setBold(true);
    $fila = $fila+2;
}

$fila++;



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

exit();
