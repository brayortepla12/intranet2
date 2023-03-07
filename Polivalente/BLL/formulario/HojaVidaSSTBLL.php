<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/HojaVidaSSTDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Classes/PHPExcel.php';

class HojaVidaSSTBLL {
    
    public function __construct(){
       $this->objPHPExcel = new PHPExcel();
    }

    public function GetHojasByServicio($ServicioId) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->GetHojaVidaByServicio($ServicioId);
    }
    
    public function ProximosVencer() {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->ProximosVencer();
    }

    public function GetHojasByServicioPrint($ServicioId) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->GetHojaVidaByServicioPrint($ServicioId);
    }

    public function GetHojasBySedeId($SedeId, $ServicioId, $Estado) {
        $Helper = new HojaVidaSSTDAL();
        if ($ServicioId != 'TODO') {
            return $Helper->GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado);
        } else {
            return $Helper->GetHojaVidaBySedeId($SedeId, $Estado);
        }
    }

    public function GetHojasBySedeId_Cronograma($SedeId, $ServicioId, $Estado) {
        $Helper = new HojaVidaSSTDAL();
        if ($ServicioId != 'TODO') {
            return $Helper->GetHojaVidaBySedeId_Servicio_Cronograma($SedeId, $ServicioId, $Estado);
        } else {
            return $Helper->GetHojaVidaBySedeId_Cronograma($SedeId, $Estado);
        }
    }

    // <editor-fold defaultstate="collapsed" desc="Excel">
    public function GenerarExcel($SedeId, $ServicioId, $Estado) {
        $list = json_decode($this->GetHojasBySedeId_Cronograma($SedeId, $ServicioId, $Estado));

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
                ->setCellValue('A' . $cont, 'N°')
                ->setCellValue('B' . $cont, 'UBICACIÓN')
                ->setCellValue('C' . $cont, 'SERVICIO/AREA')
                ->setCellValue('D' . $cont, 'SECTOR')
                ->setCellValue('E' . $cont, 'CLASE EXTINTOR')
                ->setCellValue('F' . $cont, 'FECHA RECARGA')
                ->setCellValue('G' . $cont, 'FECHA VENCIMIENTO');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':G' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
//        echo print_r($array);

        foreach ($list as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->NumeroExtintor)
                    ->setCellValue('B' . $cont, $value->Ubicacion)
                    ->setCellValue('C' . $cont, $value->Servicio)
                    ->setCellValue('D' . $cont, $value->Sector)
                    ->setCellValue('E' . $cont, $value->ClaseExtintor)
                    ->setCellValue('F' . $cont, $value->FechaRecarga)
                    ->setCellValue('G' . $cont, $value->FechaVencimiento);
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


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'CronogramaExtintores_' . $this->getDatetimeNow() . '.xlsx"');
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

// </editor-fold>

        public function GetHojaVidas() {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->GetHojaVidas();
    }

    public function GetHojasBySerie($Serie) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->GetReporteBySerie($Serie);
    }

    public function GetHojasByHojaVidaId($HojaVidaId) {
        $Helper = new HojaVidaSSTDAL();
        $Hojas = json_decode($Helper->GetHojaVidaByHojaVidaId($HojaVidaId));
        return json_encode($Hojas);
    }

    public function GetNHojaVida() {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->GetNHojaVida();
    }

    public function CreateHojaVida($list) {
        $Helper = new HojaVidaSSTDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        $o = $Helper->CreateHojaVida($this->MAPToArray($list));
        return $o;
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function BajaHojaVida($HojaVida) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida, 'Baja'), $HojaVida->HojaVidaId);
    }

    public function UpdateHojaVida($list) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->UpdateHojaVida($this->MAPToUpdate($list), $list->HojaVidaId);
    }

    public function CountHojaVidas() {
        $Helper = new HojaVidaSSTDAL();
        return json_encode($Helper->CountHojaVidas());
    }

    public function CountHojaVidas2($UsuarioId) {
        $Helper = new HojaVidaSSTDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountHojaVidasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function CountComputadores($UsuarioId) {
        $Helper = new HojaVidaSSTDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountComputadoresBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function CountImpresoras($UsuarioId) {
        $Helper = new HojaVidaSSTDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountImpresorasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function UpdateFechaHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray2($list), $HojaVidaId);
    }

    public function DeleteHojaVida($HojaVida) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida), $HojaVida->HojaVidaId);
    }

    public function TrasladoHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaSSTDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray3($list), $HojaVidaId);
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        array_push($list2, Array(
            "SedeId" => $list->SedeId,
            "ServicioId" => $list->ServicioId,
            "Ubicacion" => $list->Ubicacion,
            "ModifiedBy" => $list->CreatedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray4($list, $Estado = 'Inactivo') {
        $list2 = Array();
        array_push($list2, Array(
            "Estado" => $Estado,
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray2($list) {
        $list2 = Array();
        array_push($list2, Array(
            "ModifiedBy" => $list->CreatedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
            "FechaInstalacion" => $list->Fecha
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Ubicacion' => $list->Ubicacion,
            'Nombre' => $list->Nombre,
            'Fabricante' => $list->Fabricante,
            'Modelo' => $list->Modelo,
            'NSerial' => $list->NSerial,
            'NumeroExtintor' => $list->NumeroExtintor,
            'Sector' => $list->Sector,
            'ClaseExtintor' => $list->ClaseExtintor,
            'FechaRecarga' => $list->FechaRecarga,
            'FechaVencimiento' => $list->FechaVencimiento,
            'TipoArticulo' => $list->TipoArticulo,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'FechaInstalacion' => $list->FechaInstalacion,
            'Foto' => $list->Foto,
            'CreatedBy' => $list->UserId
        ));

        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Ubicacion' => $list->Ubicacion,
            'Nombre' => $list->Nombre,
            'Fabricante' => $list->Fabricante,
            'Modelo' => $list->Modelo,
            'NSerial' => $list->NSerial,
            'NumeroExtintor' => $list->NumeroExtintor,
            'Sector' => $list->Sector,
            'ClaseExtintor' => $list->ClaseExtintor,
            'FechaRecarga' => $list->FechaRecarga,
            'FechaVencimiento' => $list->FechaVencimiento,
            'TipoArticulo' => $list->TipoArticulo,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'FechaInstalacion' => $list->FechaInstalacion,
            'Foto' => $list->Foto,
            "ModifiedBy" => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

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
