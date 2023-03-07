<?php
/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
//date_default_timezone_set('Europe/London');
//
//if (PHP_SAPI == 'cli')
//	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Helpers/Classes/PHPExcel.php';

class GenerateExcelBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new PHPExcel();
    }

    // <editor-fold defaultstate="collapsed" desc="Auxiliares y conductores HELPERS">
    private function Auxiliar($auxlst) {
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'Auxiliar')
                ->setCellValue('B' . $cont, 'Cantidad');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(72);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
        foreach ($auxlst as $value) {
            $cont++;
            $this->objPHPExcel
                    ->getActiveSheet()->setCellValue('A' . $cont, $value['Auxiliar'])
                    ->setCellValue('B' . $cont, $value['ContAuxiliar']);
        }


//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Auxiliares');
    }

    private function Conductor($conlst) {
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->createSheet()
                ->setCellValue('A' . $cont, 'Conductor')
                ->setCellValue('B' . $cont, 'Cantidad')->setTitle('Conductores');
        $this->objPHPExcel->setActiveSheetIndexByName('Conductores');
        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(72);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
        foreach ($conlst as $value) {
            $cont++;
            $this->objPHPExcel
                    ->getActiveSheet()->setCellValue('A' . $cont, $value['Conductor'])
                    ->setCellValue('B' . $cont, $value['ContConductor']);
        }
    }

// </editor-fold>

    public function GenerateExcelEspecifico($auxlst, $conlst, $From, $To) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Reportes");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->Auxiliar($auxlst);
        $this->Conductor($conlst);

        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'Reporte_' . $this->getDatetimeNow() . '.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header($this->getDatetimeNow()); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function Generate($array, $From, $To) {
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Reportes");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'Admisión')
                ->setCellValue('B' . $cont, 'Paciente')
                ->setCellValue('C' . $cont, 'Origen')
                ->setCellValue('D' . $cont, 'Destino')
                ->setCellValue('E' . $cont, 'Tipo de Traslado')
                ->setCellValue('F' . $cont, 'Conductor')
                ->setCellValue('G' . $cont, 'Auxiliar')
                ->setCellValue('H' . $cont, 'Fecha Inicio Traslado')
                ->setCellValue('I' . $cont, 'Fecha Fin Traslado')
                ->setCellValue('J' . $cont, 'Diagnostico')
                ->setCellValue('K' . $cont, 'Fecha')
                ->setCellValue('L' . $cont, 'EPS')
                ->setCellValue('M' . $cont, 'Movil')
                ->setCellValue('N' . $cont, 'Variable')
                ->setCellValue('O' . $cont, 'Cantidad')
                ->setCellValue('P' . $cont, 'Observaciones')
                ->setCellValue('Q' . $cont, 'FECHA REG. SISTEMA');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':Q' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
//        echo print_r($array);
        foreach ($array as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value['Admision'])
                    ->setCellValue('B' . $cont, $value['Paciente'])
                    ->setCellValue('C' . $cont, $value['Origen'])
                    ->setCellValue('D' . $cont, $value['Destino'])
                    ->setCellValue('E' . $cont, $value['TipoTraslado'])
                    ->setCellValue('F' . $cont, $value['Conductor'])
                    ->setCellValue('G' . $cont, $value['Auxiliar'])
                    ->setCellValue('H' . $cont, $value['FechaInicioTraslado'] != NULL ? $value['FechaInicioTraslado']->format('Y\-m\-d H:I:S') : "")
                    ->setCellValue('I' . $cont, $value['FechaFinTraslado'] != NULL ? $value['FechaFinTraslado']->format('Y\-m\-d H:I:S') : "")
                    ->setCellValue('J' . $cont, $value['Diagnostico'] != NULL ? $value['Diagnostico'] : "")
                    ->setCellValue('K' . $cont, $value['Fecha']->format('Y\-m\-d'))
                    ->setCellValue('L' . $cont, $value['EPS'])
                    ->setCellValue('M' . $cont, $value['Movil'])
                    ->setCellValue('N' . $cont, $value['Variable'])
                    ->setCellValue('O' . $cont, $value['Cantidad'])
                    ->setCellValue('P' . $cont, $value['Observaciones'])
                    ->setCellValue('Q' . $cont, $value['CreatedAt']);
        }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Reporte');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'Reporte_' . $this->getDatetimeNow() . '.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=' . $cont);

// If you're serving to IE over SSL, then the following may be needed
        header($this->getDatetimeNow()); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function GenerateExcelPresidencia($array, $From, $To) {
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Reportes");



// Add some data
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $contador_hoja = 0;
        for ($index = 0; $index < count($array); $index++) {
            if (count($array[$index]->Datos) > 0) {
                $this->objPHPExcel->createSheet($contador_hoja);
                $this->objPHPExcel->setActiveSheetIndex($contador_hoja);
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setName('test_img');
                $objDrawing->setDescription('test_img');
                $objDrawing->setPath(dirname(__FILE__) . '\..\..\public_html\image\logo_c.png');
                $objDrawing->setCoordinates('A1');
//setOffsetX works properly
                $objDrawing->setOffsetX(100);
                $objDrawing->setOffsetY(5);
//set width, height
                $objDrawing->setWidth(300);
                $objDrawing->setHeight(105);
                $objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
                $cont = 9;
                // <editor-fold defaultstate="collapsed" desc="Header">
                $this->objPHPExcel->getActiveSheet()->mergeCells('C2:G2');
                $this->objPHPExcel->getActiveSheet()->mergeCells('C3:G3');
                $this->objPHPExcel->getActiveSheet()->mergeCells('C4:G4');
                $style = array(
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    )
                );
                $this->objPHPExcel->getActiveSheet()->getStyle("C2:G2")->applyFromArray($style);
                $this->objPHPExcel->getActiveSheet()->getStyle("C3:G3")->applyFromArray($style);
                $this->objPHPExcel->getActiveSheet()->getStyle("C4:G4")->applyFromArray($style);
                $this->objPHPExcel->setActiveSheetIndex($contador_hoja)
                        ->setCellValue('C2', "CLINICA INTEGRAL DE EMERGENCIAS LAURA DANIELA S.A")
                        ->setCellValue('C3', "NIT: 900.008.328-1")
                        ->setCellValue('C4', "REPORTE DE TRASLADO ASISTENCIAL");


                $this->objPHPExcel->setActiveSheetIndex($contador_hoja)
                        ->setCellValue('A' . $cont, 'Admisión')
                        ->setCellValue('B' . $cont, 'Paciente')
                        ->setCellValue('C' . $cont, 'Origen')
                        ->setCellValue('D' . $cont, 'Destino')
                        ->setCellValue('E' . $cont, 'EPS')
                        ->setCellValue('F' . $cont, 'Patologia')
                        ->setCellValue('G' . $cont, 'Variable')
                        ->setCellValue('H' . $cont, 'Hora Inicio')
                        ->setCellValue('I' . $cont, 'Hora Final')
                        ->setCellValue('J' . $cont, 'Observaciones'); // </editor-fold>

                $this->objPHPExcel->getActiveSheet()->mergeCells('A7:D8');
                $this->objPHPExcel->getActiveSheet()->mergeCells('E7:I8');
                $this->objPHPExcel->getActiveSheet()->mergeCells('J7:J8');
                $this->objPHPExcel->getActiveSheet()->setCellValue('A7', 'MOVIL: ' . $array[$index]->Movil);
                $this->objPHPExcel->getActiveSheet()->setCellValue('E7', 'CONDUCTOR: ');

                $this->objPHPExcel->getActiveSheet()->setCellValue('J7', 'FECHA: ' . $array[$index]->Datos[0]['Fecha']->format('Y\-m\-d'));

                $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':J' . $cont)->getFont()->setBold(true);
                $this->objPHPExcel->getActiveSheet()->setAutoFilter('A' . $cont . ':J' . $cont);
                // Ancho
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(24);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
//        echo print_r($array);

                foreach ($array[$index]->Datos as $value) {
                    $cont++;
                    $this->objPHPExcel->setActiveSheetIndex($contador_hoja)
                            ->setCellValue('A' . $cont, $value['Admision'])
                            ->setCellValue('B' . $cont, $value['Paciente'])
                            ->setCellValue('C' . $cont, $value['Origen'])
                            ->setCellValue('D' . $cont, $value['Destino'])
                            ->setCellValue('E' . $cont, $value['EPS'])
                            ->setCellValue('F' . $cont, $value['Diagnostico'] != NULL ? $value['Diagnostico'] : "")
                            ->setCellValue('G' . $cont, $value['Variable'])
//                        ->setCellValue('E' . $cont, $value['TipoTraslado'])
//                        ->setCellValue('F' . $cont, $value['Conductor'])
//                        ->setCellValue('G' . $cont, $value['Auxiliar'])
                            ->setCellValue('H' . $cont, $value['FechaInicioTraslado'] != NULL ? $value['FechaInicioTraslado']->format('H:i:s') : "")
                            ->setCellValue('I' . $cont, $value['FechaFinTraslado'] != NULL ? $value['FechaFinTraslado']->format('H:i:s') : "")

//                        ->setCellValue('K' . $cont, $value['Fecha']->format('Y\-m\-d'))
//                        ->setCellValue('N' . $cont, $value['Variable'])
//                        ->setCellValue('O' . $cont, $value['Cantidad'])
                            ->setCellValue('J' . $cont, $value['Observaciones']);
//                        ->setCellValue('Q' . $cont, $value['CreatedAt']);
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
                    $this->objPHPExcel->getActiveSheet()->setTitle($array[$index]->Movil);
                }
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $this->objPHPExcel->setActiveSheetIndex(0);
                $contador_hoja++;
            }
        }

// Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . 'ReporteDia_' . date('Y-m-d',strtotime("-1 days")) . '.xlsx"');
                header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=' . $cont);

// If you're serving to IE over SSL, then the following may be needed
                header($this->getDatetimeNow()); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
        
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
