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
            ->setCellValue('A3', '7:00 - 7:30')
            ->setCellValue('A4', '7:30 - 8:00')
            ->setCellValue('A5', '8:00 - 8:30')
            ->setCellValue('A6', '8:30 - 9:00')
            ->setCellValue('A7', '9:00 - 9:30')
            ->setCellValue('A8', '9:30 - 10:00')
            ->setCellValue('A9', '10:00 - 10:30')
            ->setCellValue('A10', '10:30 - 11:00')
            ->setCellValue('A11', '11:00 - 11:30')
            ->setCellValue('A12', '11:30 - 12:00')
            ->setCellValue('A13', '12:00 - 12:30')
            ->setCellValue('A14', '12:30 - 13:00')
            ->setCellValue('A15', '13:00 - 13:30')
            ->setCellValue('A16', '13:30 - 14:00')
            ->setCellValue('A17', '14:00 - 14:30')
            ->setCellValue('A18', '14:30 - 15:00')
            ->setCellValue('A19', '15:00 - 15:30')
            ->setCellValue('A20', '15:30 - 16:00')
            ->setCellValue('A21', '16:00 - 16:30 ')
            ->setCellValue('A22', '16:30 - 17:00')
            ->setCellValue('A23', '17:00 - 17:30')
            ->setCellValue('A24', '17:30 - 18:00 ')
            ->setCellValue('A25', '18:00 - 18:30 ')
            ->setCellValue('A26', '18:30 - 19:00 ')
            ->setCellValue('A27', '19:00 - 19:30 ')
            ->setCellValue('A28', '19:30 - 20:00 ')
            ->setCellValue('A29', '20:00 - 20:30')
            ->setCellValue('A30', '20:30 - 21:00 ')
            ->setCellValue('A31', '21:00 - 21:30 ')
            ->setCellValue('A32', '21:30 - 22:00')
            ->setCellValue('A33', '22:00 - 22:30')
            ->setCellValue('A34', '22:30 - 23:00')
            ->setCellValue('A35', '23:00 - 23:30');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Lunes')
            ->setCellValue('C2', 'Martes')
            ->setCellValue('D2', 'Miércoles')
            ->setCellValue('E2', 'Jueves')
            ->setCellValue('F2', 'Viernes')
            ->setCellValue('G2', 'Sábado');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I2', 'Clave')
            ->setCellValue('J2', 'Grupo')
            ->setCellValue('K2', 'Tipo')
            ->setCellValue('L2', 'Nombre de la materia')
            ->setCellValue('M2', 'Profesor')
            ->setCellValue('N2', 'Salón')
            ->setCellValue('O2', 'Créditos')
            ->setCellValue('P2', 'Comentarios');


     
$c = 3;
for ($i=0; $i < sizeof($lunes); $i++) { 
      
      
      if ($lunes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B' . $c, $lunes[$i]);
      }
      $c++;
}

$c = 3;
for ($i=0; $i < sizeof($martes); $i++) { 
      
      if ($martes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('C' . $c, $martes[$i]);
      }
      $c++;
}

$c = 3;
for ($i=0; $i < sizeof($miercoles); $i++) { 
      
      if ($miercoles[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $c, $miercoles[$i]);
      }
      $c++;
}

$c = 3;
for ($i=0; $i < sizeof($jueves); $i++) { 
      
      if ($jueves[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('E' . $c, $jueves[$i]);
      }
      $c++;
}

$c = 3;
for ($i=0; $i < sizeof($viernes); $i++) { 
      
      if ($viernes[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('F' . $c, $viernes[$i]);
      }
      $c++;
}

$c = 3;
for ($i=0; $i < sizeof($sabado); $i++) { 
      
      if ($sabado[$i] != "-") {
            
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('G' . $c, $sabado[$i]);
      }
      $c++;
}


$c = 3;
for ($i=0; $i < sizeof($materias); $i++) { 

      if ($materias[$i] -> TIPO == "T") {
            $tipo = "Teoria";
      }

      if ($materias[$i] -> TIPO == "L") {
            $tipo = "Laboratorio";
      }

      
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('I' . $c, $materias[$i] -> DEPTO . '-' . $materias[$i] -> CLAVE)
                  ->setCellValue('J' . $c, $materias[$i] -> GRUPO)
                  ->setCellValue('K' . $c, $tipo)
                  ->setCellValue('L' . $c, $materias[$i] -> NOMBRE)
                  ->setCellValue('M' . $c, $materias[$i] -> PROFESOR)
                  ->setCellValue('N' . $c, $materias[$i] -> SALON . ' - ' . $materias[$i] -> CAMPUS)
                  ->setCellValue('O' . $c, $materias[$i] -> CREDITOS)
                  ->setCellValue('P' . $c, $materias[$i] -> COMENTARIOS);
      $c++;
}
$c--;





// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Horario ' . $nombre_horario );


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//definiendo el tamaño de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

//Ajustando los bordes 
$styleThinBlackBorderOutline = array(
      'borders' => array(
            'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN,
                  'color' => array('argb' => 'FF000000'),
            ),
      ),
);
$objPHPExcel->getActiveSheet()->getStyle('A1:G35')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('I2:P'.$c)->applyFromArray($styleThinBlackBorderOutline);

//Ajustando colores
$objPHPExcel->getActiveSheet()->getStyle('I2:P2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$objPHPExcel->getActiveSheet()->getStyle('B2:G2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

//Ajustando color de relleno
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->getStartColor()->setARGB('FF000000');

$objPHPExcel->getActiveSheet()->getStyle('I2:P2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('I2:P2')->getFill()->getStartColor()->setARGB('FF000000');

//Ajustando alineamiento
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I2:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Ajustando link

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Horarios Itamitas');
$objPHPExcel->getActiveSheet()->getCell('A1')->getHyperlink()->setUrl('http://codeart.com.mx/demos/horarios-itamitas');
$objPHPExcel->getActiveSheet()->getCell('A1')->getHyperlink()->setTooltip('Crear un nuevo horario');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');




//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
$time = microtime();
$ti = str_replace( " ", "_", $time);
      
$random = rand(1,1000);
$time_rand = $ti . '_' . $random;
$tim = str_replace( ".", "_", $time_rand);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$name = '/var/www/html/demos/horarios-itamitas/temp/' . $tim . '.xlsx';
$objWriter->save($name);

$respuesta["resultado"] = "ok";
$respuesta["mensaje"] = $tim;

print_r(json_encode($respuesta));


exit;
?>
