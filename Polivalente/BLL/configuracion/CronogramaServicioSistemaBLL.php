<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CronogramaServicioSistemaDAL.php';
require_once dirname(__FILE__) . '/../../DAL/configuracion/ServicioDAL.php';
require_once dirname(__FILE__) . '/../../DAL/configuracion/DetalleCronogramaSistemaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as PHPExcel_Worksheet_Drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory

class CronogramaServicioSistemaBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new Spreadsheet();
    }

    public function GenerateExcelCronograma($UserId) {
        $sh = new ServicioDAL();
        $Servicios = json_decode($sh->getAllByUserId($UserId));
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
                ->setCellValue('A' . $cont, 'Servicio')
                ->setCellValue('B' . $cont, 'ServicioId')
                ->setCellValue('C' . $cont, 1)
                ->setCellValue('D' . $cont, 2)
                ->setCellValue('E' . $cont, 3)
                ->setCellValue('F' . $cont, 4)
                ->setCellValue('G' . $cont, 5)
                ->setCellValue('H' . $cont, 6)
                ->setCellValue('I' . $cont, 7)
                ->setCellValue('J' . $cont, 8)
                ->setCellValue('K' . $cont, 9)
                ->setCellValue('L' . $cont, 10)
                ->setCellValue('M' . $cont, 11)
                ->setCellValue('N' . $cont, 12);
        $style = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':N' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':N' . $cont)->applyFromArray($style);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(6);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(6);
//        echo print_r($array);
        foreach ($Servicios as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Nombre)
                    ->setCellValue('B' . $cont, $value->ServicioId);
        }
//        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
//// Miscellaneous glyphs, UTF-8
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A4', 'Miscellaneous glyphs')
//                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Cronograma');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);
        header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
        header('Content-Disposition: attachment; filename="Cronograma_sistemas_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }
    /**
     * Esta sera una funcion que se ejecutara en un script bat programado cada 15 dias
     * @return Listado_MantPendientes
     */
    public function EnviarNotificacionMantenimiento() {
        $Helper = new EmpresaBLL();
        $db = new CronogramaServicioSistemaDAL();
        $Mant = $db->getMantenimientosPendientes();
        $hs = new sendMail();
        $EmpresaObj = $Helper->GetEmpresa();
        $Contenido = '';
        foreach ($Mant as $m) {
            $Contenido = "Se le informa que durante el mes $m->Mes/$m->Vigencia, " . 
            "se llevara acabo el mantenimiento de los equipos relacionados con el departamento de SISTEMAS.<br>";
            echo $hs->EnviarEmail_Notificacion($EmpresaObj, "NOTIFICACION DE MANTENIMIENTO SISTEMAS", $Contenido, $m->Email, $m->NombreCompleto);
        }
//        echo $hs->EnviarEmail_Notificacion($EmpresaObj, "NOTIFICACION DE MANTENIMIENTO SISTEMAS", $Contenido, "zlinker89@gmail.com", "frank");
        return $Mant;
    }

    public function getAllByVigencia($VigenciaId) {
        $db = new CronogramaServicioSistemaDAL();
        return $db->getAllByVigencia($VigenciaId);
    }

    public function GetCronogramaServicioSistemaByCronogramaServicioSistemaId($CronogramaServicioSistemaId) {
        $db = new CronogramaServicioSistemaDAL();
        return $db->GetCronogramaServicioSistemaByCronogramaServicioSistemaId($CronogramaServicioSistemaId);
    }

    public function GetAllByUserId($UserId) {
        $db = new CronogramaServicioSistemaDAL();
        return $db->getAllByUserId($UserId);
    }

    public function CreateCronogramaServicioSistema($Data, $Year, $CreatedBy) {
        $db = new CronogramaServicioSistemaDAL();
        $c = $db->GetCronogramaServicioSistema($Year);
        $ids = null;
        if (count($c) == 0) {
            $ids = $db->CreateCronogramaServicioSistema($this->MAPToArray($Year, $CreatedBy));
        } else {
            $ids = [$c[0]->CronogramaId];
        }
        $dch = new DetalleCronogramaSistemaDAL();
        $dch->DeleteDetalleCronogramaSinReportes($ids[0]);
        $list = $this->MapToCreateDetalleCronograma($Data, $ids[0]);
        $dch->CreateDetalleCronogramaSistema($list);
        return $list;
//        $c = $db->GetCronogramaServicioSistema($CronogramaServicioSistema[0]->ServicioId, $CronogramaServicioSistema[0]->Vigencia);
//        if ($c) {
//            return 'Este servicio ya tiene un cronograma en la base de datos';
//        }
//        return $db->CreateCronogramaServicioSistema($this->MAPToArray($CronogramaServicioSistema));
    }

    public function MAPToArray($Vigencia, $CreatedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'Vigencia' => $Vigencia,
            'CreatedBy' => $CreatedBy
        ));
        return $list2;
    }

    public function MapToCreateDetalleCronograma($Data, $CronogramaId) {
        $list2 = Array();
        for ($i = 0; $i < count($Data); $i++) {
            for ($k = 1; $k <= 12; $k++) {
                if (property_exists($Data[$i], "$k")) {
                    array_push($list2, Array(
                        'CronogramaID' => $CronogramaId,
                        'ServicioId' => $Data[$i]->ServicioId,
                        'Mes' => $k,
                    ));
                }
            }
        }

        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
