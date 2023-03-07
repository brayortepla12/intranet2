<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../Helpers/Classes/PHPExcel.php';
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelBLL {

    public function __construct() {
        $this->objPHPExcel = new Spreadsheet();
    }

    public function BuildExcel_FacturasEmitidasHoy($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'USUARIO FACTURA')
                ->setCellValue('B' . $cont, 'NOMBRE')
                ->setCellValue('C' . $cont, 'CANTIDAD');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);

        foreach ($array->FacturasEmitidasHoy as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->USUARIOFACTURA)
                    ->setCellValue('B' . $cont, $value->NOMBRE)
                    ->setCellValue('C' . $cont, $value->Cantidad);
        }

        foreach ($array->FacturasEmitidasHoyTotal as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B' . $cont, $value->Total)
                    ->setCellValue('C' . $cont, $value->Cantidad);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Facturas Emitidas Hoy');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'FacturasEmitidasHoy_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_FacturasEmitidasMes($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'NOMBRE')
                ->setCellValue('B' . $cont, 'CANTIDAD');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);

        foreach ($array->FacturasEmitidasMes as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->NOMBRE)
                    ->setCellValue('B' . $cont, $value->Cantidad);
        }

        foreach ($array->FacturasEmitidasMESTotal as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->total1)
                    ->setCellValue('B' . $cont, $value->Cantidad);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Facturas Emitidas Mes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'FacturasEmitidasMes_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_Radicacion($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'RAZON SOCIAL')
                ->setCellValue('B' . $cont, 'CANTIDAD')
                ->setCellValue('C' . $cont, 'VALOR FACTURA');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);

        foreach ($array->Radicacion as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->RAZONSOCIAL)
                    ->setCellValue('B' . $cont, $value->CANTIDAD)
                    ->setCellValue('C' . $cont, $value->VALORFACTURA);
        }

        foreach ($array->TotalRadicacion as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->RAZON)
                    ->setCellValue('B' . $cont, $value->CANTIDAD)
                    ->setCellValue('C' . $cont, $value->VALORFACTURA);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Radicación');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'Radicacion_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_CensoPorPeriodo($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'MES')
                ->setCellValue('B' . $cont, 'CANTIDAD');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
        foreach ($array->CensoPorPeriodo as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->MES)
                    ->setCellValue('B' . $cont, $value->CANTIDAD);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Censo Por Periodo');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'CensoPorPeriodo_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_CensoPorEPS($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'EPS')
                ->setCellValue('B' . $cont, 'TOTAL');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
        foreach ($array->CensoPorEPS as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->EPS)
                    ->setCellValue('B' . $cont, $value->Total);
        }

        foreach ($array->TotalCensoPorEPS as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->EPS)
                    ->setCellValue('B' . $cont, $value->Total);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Censo Por EPS');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'CensoPorEPS_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_CensoPorSector($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'SECTOR')
                ->setCellValue('B' . $cont, 'DESCRIPCIÓN')
                ->setCellValue('C' . $cont, 'TOTAL')
                ->setCellValue('D' . $cont, 'CAPACIDAD')
                ->setCellValue('E' . $cont, 'DIF');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':E' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);TotalCensoPorSector
        foreach ($array->CensoPorSector as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Sector)
                    ->setCellValue('B' . $cont, $value->Descripcion)
                    ->setCellValue('C' . $cont, $value->Total)
                    ->setCellValue('D' . $cont, $value->Capacidad)
                    ->setCellValue('E' . $cont, $value->DIF);
        }

        foreach ($array->TotalCensoPorSector as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Sector)
                    ->setCellValue('B' . $cont, $value->Descripcion)
                    ->setCellValue('C' . $cont, $value->Total);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':E' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Censo Por Sector');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'CensoPorSector_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_Acostados($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'SEDE')
                ->setCellValue('B' . $cont, 'TOTAL');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);TotalAcostados
        foreach ($array->Acostados as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->sede)
                    ->setCellValue('B' . $cont, $value->total);
        }

        foreach ($array->TotalAcostados as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->total)
                    ->setCellValue('B' . $cont, $value->total1);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Acostados');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'Acostados_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_FacturadoEPS($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'RAZONSOCIAL')
                ->setCellValue('B' . $cont, 'VALOR');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':B' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
        foreach ($array->FacturadoEPSMes as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->RAZONSOCIAL)
                    ->setCellValue('B' . $cont, $value->VALOR);
        }

//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Facturado EPS');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'FacturadoEPS_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcelInformeSistemas($Reportes) {
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'ID')
                ->setCellValue('B' . $cont, 'Sede')
                ->setCellValue('C' . $cont, 'Servicio')
                ->setCellValue('D' . $cont, 'Ubicación')
                ->setCellValue('E' . $cont, 'Tipo Servicio')
                ->setCellValue('F' . $cont, 'Solicitante')
                ->setCellValue('G' . $cont, 'Equipo')
                ->setCellValue('H' . $cont, 'Serial')
                ->setCellValue('I' . $cont, 'Contador')
                ->setCellValue('J' . $cont, 'Fecha Reporte')
                ->setCellValue('K' . $cont, 'Creado Por')
                ->setCellValue('L' . $cont, 'Fecha Creación')
                ->setCellValue('M' . $cont, 'Estado');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':L' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
//        echo print_r($array);
        foreach ($Reportes as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->ReporteId)
                    ->setCellValue('B' . $cont, $value->Sede)
                    ->setCellValue('C' . $cont, $value->Servicio)
                    ->setCellValue('D' . $cont, $value->Ubicacion)
                    ->setCellValue('E' . $cont, $value->TipoServicio)
                    ->setCellValue('F' . $cont, $value->Solicitante)
                    ->setCellValue('G' . $cont, $value->Equipo)
                    ->setCellValue('H' . $cont, $value->NSerial)
                    ->setCellValue('I' . $cont, $value->Contador)
                    ->setCellValue('J' . $cont, $value->Fecha)
                    ->setCellValue('K' . $cont, $value->CreatedBy)
                    ->setCellValue('L' . $cont, $value->CreatedAt)
                    ->setCellValue('M' . $cont, $value->Estado);
        }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Estadisticas');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'InformeReportes_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_MesActual($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'Mes')
                ->setCellValue('B' . $cont, 'Sede')
                ->setCellValue('C' . $cont, 'Valor');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
        foreach ($array->MesActual as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Mes)
                    ->setCellValue('B' . $cont, $value->Sede)
                    ->setCellValue('C' . $cont, $value->Valor);
        }
        foreach ($array->SumPendientePorFac as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Mes)
                    ->setCellValue('B' . $cont, $value->Sede)
                    ->setCellValue('C' . $cont, $value->Valor);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('MesActual');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'MesActual_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function BuildExcel_FacturadoHoy($array) {

        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de reportes en un rango determinado")
                ->setSubject("Comparativo de reportes en un rango determinado")
                ->setDescription("Comparativo de reportes en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Facturacion");


// Add some data
        $cont = 1;
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, 'Mes')
                ->setCellValue('B' . $cont, 'Sede')
                ->setCellValue('C' . $cont, 'Valor');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(35);
//        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//        echo print_r($array);
        foreach ($array->FacturadoHoyLabel as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->mes)
                    ->setCellValue('B' . $cont, $value->sede)
                    ->setCellValue('C' . $cont, $value->valor);
        }
        foreach ($array->FacturadoHoy as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->mes)
                    ->setCellValue('B' . $cont, $value->sede)
                    ->setCellValue('C' . $cont, $value->valor);
            $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':C' . $cont)->getFont()->setBold(true);
        }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Facturado Hoy');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'FacturadoHoy_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d h:i:s');
    }

}
