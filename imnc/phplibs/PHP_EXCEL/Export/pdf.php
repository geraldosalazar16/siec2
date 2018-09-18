 <?php 

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

$json = file_get_contents('php://input');
$json_deco = json_decode($json);



$horario = $json_deco -> horario;
$materias = $json_deco -> materias;

$lunes = $horario -> LU;
$martes = $horario -> MA;
$miercoles = $horario -> MI;
$jueves = $horario -> JU;
$viernes = $horario -> VI;
$sabado = $horario -> SA;


$nombre_horario = "Otoño 2015";

$respuesta = array();

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');


/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';



// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Horarios Itamitas")
							 ->setLastModifiedBy("Horarios Itamitas")
							 ->setTitle("Horario " . $nombre_horario)
							 ->setSubject("Horario de clases")
							 ->setDescription("Horario generado por Horarios Itamitas")
							 ->setKeywords("horarios itamitas")
							 ->setCategory("Horario escolar");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '7:00 - 7:30')
            ->setCellValue('A3', '7:30 - 8:00')
            ->setCellValue('A4', '8:00 - 8:30')
            ->setCellValue('A5', '8:30 - 9:00')
            ->setCellValue('A6', '9:00 - 9:30')
            ->setCellValue('A7', '9:30 - 10:00')
            ->setCellValue('A8', '10:00 - 10:30')
            ->setCellValue('A9', '10:30 - 11:00')
            ->setCellValue('A10', '11:00 - 11:30')
            ->setCellValue('A11', '11:30 - 12:00')
            ->setCellValue('A12', '12:00 - 12:30')
            ->setCellValue('A13', '12:30 - 13:00')
            ->setCellValue('A14', '13:00 - 13:30')
            ->setCellValue('A15', '13:30 - 14:00')
            ->setCellValue('A16', '14:00 - 14:30')
            ->setCellValue('A17', '14:30 - 15:00')
            ->setCellValue('A18', '15:00 - 15:30')
            ->setCellValue('A19', '15:30 - 16:00')
            ->setCellValue('A20', '16:00 - 16:30 ')
            ->setCellValue('A21', '16:30 - 17:00')
            ->setCellValue('A22', '17:00 - 17:30')
            ->setCellValue('A23', '17:30 - 18:00 ')
            ->setCellValue('A24', '18:00 - 18:30 ')
            ->setCellValue('A25', '18:30 - 19:00 ')
            ->setCellValue('A26', '19:00 - 19:30 ')
            ->setCellValue('A27', '19:30 - 20:00 ')
            ->setCellValue('A28', '20:00 - 20:30')
            ->setCellValue('A29', '20:30 - 21:00 ')
            ->setCellValue('A30', '21:00 - 21:30 ')
            ->setCellValue('A31', '21:30 - 22:00')
            ->setCellValue('A32', '22:00 - 22:30')
            ->setCellValue('A33', '22:30 - 23:00')
            ->setCellValue('A34', '23:00 - 23:30');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'Lunes')
            ->setCellValue('C1', 'Martes')
            ->setCellValue('D1', 'Miércoles')
            ->setCellValue('E1', 'Jueves')
            ->setCellValue('F1', 'Viernes')
            ->setCellValue('G1', 'Sábado');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A36', 'Clave')
            ->setCellValue('B36', 'Grupo')
            ->setCellValue('C36', 'Tipo')
            ->setCellValue('D36', 'Nombre de la materia')
            ->setCellValue('E36', 'Profesor')
            ->setCellValue('F36', 'Salón')
            ->setCellValue('G36', 'Créditos')
            ->setCellValue('H36', 'Comentarios');


     
$c = 2;
for ($i=0; $i < sizeof($lunes); $i++) { 
      
      
      if ($lunes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B' . $c, $lunes[$i]);
      }
      $c++;
}

$c = 2;
for ($i=0; $i < sizeof($martes); $i++) { 
      
      if ($martes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C' . $c, $martes[$i]);
      }
      $c++;
}

$c = 2;
for ($i=0; $i < sizeof($miercoles); $i++) { 
      
      if ($miercoles[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $c, $miercoles[$i]);
      }
      $c++;
}

$c = 2;
for ($i=0; $i < sizeof($jueves); $i++) { 
      
      if ($jueves[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('E' . $c, $jueves[$i]);
      }
      $c++;
}

$c = 2;
for ($i=0; $i < sizeof($viernes); $i++) { 
      
      if ($viernes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('F' . $c, $viernes[$i]);
      }
      $c++;
}

$c = 2;
for ($i=0; $i < sizeof($sabado); $i++) { 
      
      if ($sabado[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('G' . $c, $sabado[$i]);
      }
      $c++;
}


$c = 37;
for ($i=0; $i < sizeof($materias); $i++) { 

      if ($materias[$i] -> TIPO == "T") {
            $tipo = "Teoria";
      }

      if ($materias[$i] -> TIPO == "L") {
            $tipo = "Laboratorio";
      }

      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A' . $c, $materias[$i] -> DEPTO . '-' . $materias[$i] -> CLAVE)
                  ->setCellValue('B' . $c, $materias[$i] -> GRUPO)
                  ->setCellValue('C' . $c, $tipo)
                  ->setCellValue('D' . $c, $materias[$i] -> NOMBRE)
                  ->setCellValue('E' . $c, $materias[$i] -> PROFESOR)
                  ->setCellValue('F' . $c, $materias[$i] -> SALON . ' - ' . $materias[$i] -> CAMPUS)
                  ->setCellValue('G' . $c, $materias[$i] -> CREDITOS)
                  ->setCellValue('H' . $c, $materias[$i] -> COMENTARIOS);
      $c++;

      //$objPHPExcel->getActiveSheet()->getStyle('A'.$c.':G'.$c)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}
$c--;




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Horario ' . $nombre_horario );


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//definiendo el tamaño de las columnas

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);

//Ajustando los bordes 
$styleThinBlackBorderOutline = array(
      'borders' => array(
            'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
                  'color' => array('argb' => 'FF000000'),
            ),
      ),
);
$objPHPExcel->getActiveSheet()->getStyle('A1:G34')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A36:H'.$c)->applyFromArray($styleThinBlackBorderOutline);

//Ajustando colores

$objPHPExcel->getActiveSheet()->getStyle('B1:G1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

//Ajustando color de relleno
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF000000');


//Ajustando alineamiento
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);




//Ajustando link



/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';


//	Change these values to select the Rendering library that you wish to use
//		and its directory location on your server
//$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;

$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
$rendererLibrary = 'dompdf';


//$rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
//$rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
//$rendererLibrary = 'tcPDF5.9';
//$rendererLibrary = 'mpdf60';
//$rendererLibrary = 'domPDF0.6.0beta3';
$rendererLibraryPath = '/var/www/html/demos/horarios-itamitas/' . $rendererLibrary;



$objPHPExcel->getActiveSheet()->setShowGridLines(false);


$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);




if (!PHPExcel_Settings::setPdfRenderer(
		$rendererName,
		$rendererLibraryPath
	)) {
	die(
		'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
		EOL .
		'at the top of this script as appropriate for your directory structure'
	);
}

$time = microtime();
$ti = str_replace( " ", "_", $time);
      
$random = rand(1,1000);
$time_rand = $ti . '_' . $random;
$tim = str_replace( ".", "_", $time_rand);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->setSheetIndex(0);
$name = '/var/www/html/demos/horarios-itamitas/temp/' . $tim . '.pdf';
$objWriter->save($name);


$respuesta["resultado"] = "ok";
$respuesta["mensaje"] = $tim;

print_r(json_encode($respuesta));














