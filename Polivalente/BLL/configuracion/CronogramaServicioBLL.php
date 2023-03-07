<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CronogramaServicioDAL.php';
require_once dirname(__FILE__) . '/../../DAL/configuracion/ServicioDAL.php';
require_once dirname(__FILE__) . '/../../DAL/formulario/HojaVidaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as PHPExcel_Worksheet_Drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory

class CronogramaServicioBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new Spreadsheet();
    }
    
    private function GetTablas($Prefijo) {
        // contruyo las tablas y columnas para la consulta
        $tablas = new stdClass();
        $tablas->sede = "sede";
        $tablas->servicio = "servicio";
        $tablas->hojavida = "hojavida";
        $tablas->Equipo = "Equipo";
        $tablas->Marca = "Marca";
        $tablas->Serie = "Serie";
        if (strtolower($Prefijo) == 'biomedicos') {
            $tablas->sede = "biomedico.sede";
            $tablas->servicio = "biomedico.servicio";
            $tablas->hojavida = "biomedico.hojavida";
        }elseif (strtolower($Prefijo) == 'sistemas') {
            $tablas->sede = "sede";
            $tablas->servicio = "servicio";
            $tablas->hojavida = "sistemas_hojavida";
            $tablas->Equipo = "Nombre";
            $tablas->Marca = "Fabricante";
            $tablas->Serie = "NSerial";
        }
        return $tablas;
    }

    public function GetAll() {
        $db = new CronogramaServicioDAL();
        return $db->getAll();
    }

    public function GetCronogramaServicioByCronogramaServicioId($CronogramaServicioId) {
        $db = new CronogramaServicioDAL();
        return $db->GetCronogramaServicioByCronogramaServicioId($CronogramaServicioId);
    }

    public function GetAllByUserId($UserId) {
        $db = new CronogramaServicioDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetExcelCronograma($SedeId, $Vigencia, $Prefijo) {
        $sh = new ServicioDAL();
        $hh = new HojaVidaDAL();
        $Servicios = $sh->getAllBySedeWithEquipo($SedeId, $this->GetTablas($Prefijo));
        // Set document properties
        $this->objPHPExcel->getProperties()->setCreator("Franklin Ospino")
                ->setLastModifiedBy("Franklin Ospino")
                ->setTitle("Cronograma de mantenimiento preventivo para polivalente")
                ->setSubject("Cronograma de mantenimiento preventivo para polivalente")
                ->setDescription("Cronograma de mantenimiento preventivo para polivalente.")
                ->setKeywords("office 2013 openxml php")
                ->setCategory("Cronograma de polivalente");
        $Numero_hoja = 0;
        foreach ($Servicios as $value) {
            $cont = 1;
            $Items = $hh->GetHojaVidaByServicioIdWithTA($value->ServicioId, $this->GetTablas($Prefijo));
            $this->objPHPExcel->createSheet($Numero_hoja);
            $this->objPHPExcel->setActiveSheetIndex($Numero_hoja);
            $HOJA = $this->objPHPExcel->getActiveSheet();
            $HOJA->setCellValue('A' . $cont, 'Item')
                    ->setCellValue('B' . $cont, 'Equipo')
                    ->setCellValue('C' . $cont, 'Marca')
                    ->setCellValue('D' . $cont, 'Modelo')
                    ->setCellValue('E' . $cont, 'Serie')
                    ->setCellValue('F' . $cont, 'Ubicacion')
                    ->setCellValue('G' . $cont, 1)
                    ->setCellValue('H' . $cont, 2)
                    ->setCellValue('I' . $cont, 3)
                    ->setCellValue('J' . $cont, 4)
                    ->setCellValue('K' . $cont, 5)
                    ->setCellValue('L' . $cont, 6)
                    ->setCellValue('M' . $cont, 7)
                    ->setCellValue('N' . $cont, 8)
                    ->setCellValue('O' . $cont, 9)
                    ->setCellValue('P' . $cont, 10)
                    ->setCellValue('Q' . $cont, 11)
                    ->setCellValue('R' . $cont, 12)
                    ->setCellValue('S' . $cont, 'HojaVidaId')
                    ->setCellValue('T' . $cont, 'ServicioId')
                    ->setCellValue('U' . $cont, 'Vigencia');
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            );
            $HOJA->getStyle('A' . $cont . ':U' . $cont)->getFont()->setBold(true);
            $HOJA->getStyle('A' . $cont . ':U' . $cont)->applyFromArray($style);
            $HOJA->setAutoFilter($HOJA->calculateWorksheetDimension());
            // Ancho
            $HOJA->getColumnDimension('A')->setWidth(6);
            $HOJA->getColumnDimension('B')->setWidth(60);
            $HOJA->getColumnDimension('C')->setWidth(15);
            $HOJA->getColumnDimension('D')->setWidth(15);
            $HOJA->getColumnDimension('E')->setWidth(15);
            $HOJA->getColumnDimension('F')->setWidth(40);
            $HOJA->getColumnDimension('G')->setWidth(6);
            $HOJA->getColumnDimension('H')->setWidth(6);
            $HOJA->getColumnDimension('I')->setWidth(6);
            $HOJA->getColumnDimension('J')->setWidth(6);
            $HOJA->getColumnDimension('K')->setWidth(6);
            $HOJA->getColumnDimension('L')->setWidth(6);
            $HOJA->getColumnDimension('M')->setWidth(6);
            $HOJA->getColumnDimension('N')->setWidth(6);
            $HOJA->getColumnDimension('O')->setWidth(6);
            $HOJA->getColumnDimension('P')->setWidth(6);
            $HOJA->getColumnDimension('Q')->setWidth(6);
            $HOJA->getColumnDimension('R')->setWidth(6);
            $HOJA->getColumnDimension('S')->setWidth(15);
            $HOJA->getColumnDimension('T')->setWidth(15);
            $HOJA->getColumnDimension('U')->setWidth(10);
//        echo print_r($array);
            $contador_item = 0;
            foreach ($Items as $h) {
                $cont++;
                $contador_item++;
                $HOJA
                        ->setCellValue('A' . $cont, $contador_item)
                        ->setCellValue('B' . $cont, $h->Equipo)
                        ->setCellValue('C' . $cont, $h->Marca)
                        ->setCellValue('D' . $cont, $h->Modelo)
                        ->setCellValue('E' . $cont, $h->Serie)
                        ->setCellValue('F' . $cont, $h->Ubicacion)
                        ->setCellValue('S' . $cont, $h->HojaVidaId)
                        ->setCellValue('T' . $cont, $value->ServicioId)
                        ->setCellValue('U' . $cont, $Vigencia);
            }
            $HOJA->getProtection()->setSheet(true);
            $HOJA->getProtection()->setPassword('123456789.');
            $HOJA->getStyle('G2:R' . $cont)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
// Rename worksheet
            $HOJA->setTitle(substr($value->Nombre, 0, 30));
            $Numero_hoja++;
        }
        $this->objPHPExcel->setActiveSheetIndex(0);
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Cronograma_' . $Prefijo . '_dia_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function CreateCronogramaServicio($CronogramaServicio, $CreatedBy, $Prefijo) {
        $db = new CronogramaServicioDAL();
        return $db->CreateCronogramaServicio($this->MAPToCreateCMP($CronogramaServicio, $CreatedBy), $Prefijo);
    }

    public function CreateCronogramaServicioUsuario($list) {
        $db = new CronogramaServicioDAL();
        $c = $db->GetCronogramaServicioByUsuario($list[0]->CronogramaServicioId, $list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateCronogramaServicioUsuario($this->MAPToArray3($list));
        }
    }

    public function UpdateCronogramaServicio($list, $id) {
        $db = new CronogramaServicioDAL();
        return $db->UpdateCronogramaServicio($this->MAPToArray2($list), $id);
    }

    public function MAPToCreateCMP($list, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, (object)[
                'HojaVidaId' => $list[$index]->HojaVidaId,
                'Nombre' => $list[$index]->Equipo,
                'Marca' => $list[$index]->Marca,
                'Modelo' => $list[$index]->Modelo,
                'Serie' => $list[$index]->Serie,
                'Ubicacion' => $list[$index]->Ubicacion,
                'ServicioId' => $list[$index]->ServicioId,
                'Vigencia' => $list[$index]->Vigencia,
                '_1' => property_exists($list[$index], "1") ? $this->IsEmpty($list[$index]->{"1"}) : 0,
                '_2' => property_exists($list[$index], "2") ? $this->IsEmpty($list[$index]->{"2"}) : 0,
                '_3' => property_exists($list[$index], "3") ? $this->IsEmpty($list[$index]->{"3"}) : 0,
                '_4' => property_exists($list[$index], "4") ? $this->IsEmpty($list[$index]->{"4"}) : 0,
                '_5' => property_exists($list[$index], "5") ? $this->IsEmpty($list[$index]->{"5"}) : 0,
                '_6' => property_exists($list[$index], "6") ? $this->IsEmpty($list[$index]->{"6"}) : 0,
                '_7' => property_exists($list[$index], "7") ? $this->IsEmpty($list[$index]->{"7"}) : 0,
                '_8' => property_exists($list[$index], "8") ? $this->IsEmpty($list[$index]->{"8"}) : 0,
                '_9' => property_exists($list[$index], "9") ? $this->IsEmpty($list[$index]->{"9"}) : 0,
                '_10' => property_exists($list[$index], "10") ? $this->IsEmpty($list[$index]->{"10"}) : 0,
                '_11' => property_exists($list[$index], "11") ? $this->IsEmpty($list[$index]->{"11"}) : 0,
                '_12' => property_exists($list[$index], "12") ? $this->IsEmpty($list[$index]->{"12"}) : 0,
                'CreatedBy' => $CreatedBy
            ]);
        }
        return $list2;
    }
    public function IsEmpty($cadena) {
        if(count_chars($cadena) == 0){
            return "0";
        }
        return $cadena;
    }

    public function MAPToArray2($list) {
        $list2 = Array();

        for ($index = 0; $index < count($list); $index++) {
            $CronogramaServicioOld = $this->GetCronogramaServicioByCronogramaServicioId($list[$index]->CronogramaServicioId);
            $Historico = json_decode($CronogramaServicioOld->Historico);
            array_push($Historico, $CronogramaServicioOld);
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
                'CronogramaServicioId' => $list[$index]->CronogramaServicioId,
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
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
