<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CIDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../../DAL/configuracion/ServicioDAL.php';
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as PHPExcel_Worksheet_Drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory

class CIBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new Spreadsheet();
    }

    // <editor-fold defaultstate="collapsed" desc="Excel">
    public function GetExcelCronograma($Vigencia, $SedeId) {
        $sh = new ServicioDAL();
        $Servicios = json_decode($sh->getAllBySede($SedeId));
        $Items = $this->GetItemsCI();
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Comparativo de referencia en un rango determinado")
                ->setSubject("Comparativo de referencia en un rango determinado")
                ->setDescription("Comparativo de referencia en un rango determinado.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Cronograma");
// Add some data
//        $this->objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $Numero_hoja = 0;
//            echo print_r($Servicios);
        foreach ($Servicios as $value) {
//            echo $value->Nombre;
            $cont = 1;
            $this->objPHPExcel->createSheet($Numero_hoja);
            $this->objPHPExcel->setActiveSheetIndex($Numero_hoja);
            $HOJA = $this->objPHPExcel->getActiveSheet();
            $HOJA->setCellValue('A' . $cont, 'Item')
                    ->setCellValue('B' . $cont, 'DESCRIPCION')
                    ->setCellValue('C' . $cont, 'PERIODICIDAD')
                    ->setCellValue('D' . $cont, 1)
                    ->setCellValue('E' . $cont, 2)
                    ->setCellValue('F' . $cont, 3)
                    ->setCellValue('G' . $cont, 4)
                    ->setCellValue('H' . $cont, 5)
                    ->setCellValue('I' . $cont, 6)
                    ->setCellValue('J' . $cont, 7)
                    ->setCellValue('K' . $cont, 8)
                    ->setCellValue('L' . $cont, 9)
                    ->setCellValue('M' . $cont, 10)
                    ->setCellValue('N' . $cont, 11)
                    ->setCellValue('O' . $cont, 12)
                    ->setCellValue('P' . $cont, 'RESPONSABLE')
                    ->setCellValue('Q' . $cont, 'ItemCIId')
                    ->setCellValue('R' . $cont, 'ServicioId');
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            $HOJA->getStyle('A' . $cont . ':R' . $cont)->getFont()->setBold(true);
            $HOJA->getStyle('A' . $cont . ':R' . $cont)->applyFromArray($style);
            $HOJA->setAutoFilter($HOJA->calculateWorksheetDimension());
            // Ancho
            $HOJA->getColumnDimension('A')->setWidth(6);
            $HOJA->getColumnDimension('B')->setWidth(60);
            $HOJA->getColumnDimension('C')->setWidth(20);
            $HOJA->getColumnDimension('D')->setWidth(6);
            $HOJA->getColumnDimension('E')->setWidth(6);
            $HOJA->getColumnDimension('F')->setWidth(6);
            $HOJA->getColumnDimension('G')->setWidth(6);
            $HOJA->getColumnDimension('H')->setWidth(6);
            $HOJA->getColumnDimension('I')->setWidth(6);
            $HOJA->getColumnDimension('J')->setWidth(6);
            $HOJA->getColumnDimension('K')->setWidth(6);
            $HOJA->getColumnDimension('L')->setWidth(6);
            $HOJA->getColumnDimension('M')->setWidth(6);
            $HOJA->getColumnDimension('N')->setWidth(6);
            $HOJA->getColumnDimension('O')->setWidth(6);
            $HOJA->getColumnDimension('P')->setWidth(15);
//        echo print_r($array);
            $contador_item = 0;
            foreach ($Items as $v) {

                $cont++;
                $contador_item++;
                $HOJA
                        ->setCellValue('A' . $cont, $contador_item)
                        ->setCellValue('B' . $cont, $v->Descripcion)
                        ->setCellValue('Q' . $cont, $v->ItemCIId)
                        ->setCellValue('R' . $cont, $value->ServicioId);
            }
            $HOJA->getProtection()->setSheet(true);
            $HOJA->getProtection()->setPassword('123456789.');
            $HOJA->getStyle('C2:P' . $cont)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
// Rename worksheet
            $HOJA->setTitle(substr($value->Nombre, 0, 30));
            $Numero_hoja++;
        }
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);
        header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
        header('Content-Disposition: attachment; filename="Cronograma_infra_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

// </editor-fold>
    
    
    public function GetAllByServicioId($Vigencia, $ServicioId) {
        $db = new CIDAL();
        $sh = new ServicioDAL();
        $Ser = $sh->GetServicioBYID($ServicioId);
        $Ser->Cronograma = $db->GetDCIByVigenciaAndServicioId($Vigencia, $ServicioId);
        return $Ser;
    }

    public function GetItemsCI() {
        $db = new CIDAL();
        return $db->GetItemsCI();
    }

    public function GetAllByUserId($UserId) {
        $db = new CIDAL();
        return $db->getAllByUserId($UserId);
    }

    public function CreateCI($DCI, $Vigencia, $CreatedBy) {
        $db = new CIDAL();
        $ci_db = $db->GetCIByVigencia($Vigencia);
        $id = Array();
        if (count($ci_db) == 0) {
            $id = $db->CreateCI([array(
            "Vigencia" => $Vigencia,
            "CreatedBy" => $CreatedBy,
            )]);
        } else {
            $id = [$ci_db[0]->CIId];
        }
        $Servicios = $db->GetServiciosIdArr($id[0]);
        return $db->CreateDCI($this->MAPToArray($DCI, $Servicios != NULL ? $Servicios : [], $id[0], $CreatedBy));
    }

    public function CreateCIUsuario($list) {
        $db = new CIDAL();
        $c = $db->GetCIByUsuario($list[0]->CIId, $list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateCIUsuario($this->MAPToArray3($list));
        }
    }

    public function UpdateCI($list, $id) {
        $db = new CIDAL();
        return $db->UpdateCI($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list, $Servicios, $CIId, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            if (!in_array($list[$index]->ServicioId, $Servicios) &&
                    (
                    // verificamos solo se deben registrar los que tengan informacion
                    property_exists($list[$index], '1') ||
                    property_exists($list[$index], '2') ||
                    property_exists($list[$index], '3') ||
                    property_exists($list[$index], '4') ||
                    property_exists($list[$index], '5') ||
                    property_exists($list[$index], '6') ||
                    property_exists($list[$index], '7') ||
                    property_exists($list[$index], '8') ||
                    property_exists($list[$index], '9') ||
                    property_exists($list[$index], '10') ||
                    property_exists($list[$index], '11') ||
                    property_exists($list[$index], '12')
                    )
            ) {
                array_push($list2, Array(
                    'ServicioId' => $list[$index]->ServicioId,
                    'CIId' => $CIId,
                    'Ene' => property_exists($list[$index], '1') ? $list[$index]->{'1'} : '',
                    'Feb' => property_exists($list[$index], '2') ? $list[$index]->{'2'} : '',
                    'Mar' => property_exists($list[$index], '3') ? $list[$index]->{'3'} : '',
                    'Abr' => property_exists($list[$index], '4') ? $list[$index]->{'4'} : '',
                    'May' => property_exists($list[$index], '5') ? $list[$index]->{'5'} : '',
                    'Jun' => property_exists($list[$index], '6') ? $list[$index]->{'6'} : '',
                    'Jul' => property_exists($list[$index], '7') ? $list[$index]->{'7'} : '',
                    'Agos' => property_exists($list[$index], '8') ? $list[$index]->{'8'} : '',
                    'Sept' => property_exists($list[$index], '9') ? $list[$index]->{'9'} : '',
                    'Oct' => property_exists($list[$index], '10') ? $list[$index]->{'10'} : '',
                    'Nov' => property_exists($list[$index], '11') ? $list[$index]->{'11'} : '',
                    'Dic' => property_exists($list[$index], '12') ? $list[$index]->{'12'} : '',
                    'ItemId' => property_exists($list[$index], 'ItemCIId') ? $list[$index]->ItemCIId : '',
                    'Descripcion' => property_exists($list[$index], 'DESCRIPCION') ? $list[$index]->DESCRIPCION : '',
                    'Periodicidad' => property_exists($list[$index], 'PERIODICIDAD') ? $list[$index]->PERIODICIDAD : '',
                    'Responsable' => property_exists($list[$index], 'RESPONSABLE') ? $list[$index]->RESPONSABLE : '',
                    'CreatedBy' => $CreatedBy
                ));
            }
        }
        return $list2;
    }

    public function MAPToArray2($list) {
        $list2 = Array();

        for ($index = 0; $index < count($list); $index++) {
            $CIOld = $this->GetCIByCIId($list[$index]->CIId);
            $Historico = json_decode($CIOld->Historico);
            array_push($Historico, $CIOld);
            array_push($list2, Array(
                'MesInicial' => $list[$index]->MesInicial,
                'SedeId' => $list[$index]->SedeId,
                'ServicioId' => $list[$index]->ServicioId,
                'Historico' => json_encode($Historico),
                'FrecuenciaMantenimientoId' => $list[$index]->FrecuenciaMantenimientoId,
                'Observaciones' => $list[$index]->Observaciones,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        }
        return $list2;
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'CIId' => $list[$index]->CIId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d h:i:s');
    }

}
