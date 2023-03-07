<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/almacen/RelacionDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/ServicioBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RelacionBLL
{

    private $objPHPExcel;

    public function __construct()
    {
        $this->objPHPExcel = new Spreadsheet();
    }

    public function ClonarPlantilla($lst)
    {
        $db = new RelacionDAL();
        $hs = new ServicioBLL();
        $servicio = $hs->GetServicioByPersonaId($lst->UsuarioOrigenId, $lst->ServicioId);
        if (count($servicio) == 0) {
            $hs->AsignarServicioUsuariolite(array(
                'ServicioId' => $lst->ServicioId,
                'UsuarioId' => $lst->UsuarioDestinoId,
            ));
        }
        $db->DeletePlantillaByUsuarioIdAndServicioId($lst->ServicioId, $lst->UsuarioDestinoId);
        $db->ClonarPlantilla($lst->UsuarioOrigenId, $lst->ServicioId, $lst->UsuarioDestinoId);
        return $lst;
    }

    public function GetAll()
    {
        $db = new RelacionDAL();
        return $db->getAll();
    }

    public function GetPlantilla($UsuarioId, $Estado)
    {
        $db = new RelacionDAL();
        return $db->GetPlantilla($UsuarioId, $Estado);
    }

    public function getEstadisticasPedidos_data($From, $To, $SedeId, $ServicioId, $Tipo)
    {
        $db = new RelacionDAL();
        if ($SedeId == 'TODO' && $ServicioId == 'TODO') {
            return $db->getEstadisticasPedidos_data($From, $To, $Tipo);
        } else if ($SedeId != 'TODO' && $ServicioId == 'TODO') {
            return $db->getEstadisticasPedidos_dataSede($From, $To, $SedeId, $Tipo);
        } else if ($SedeId != 'TODO' && $ServicioId != 'TODO') {
            return $db->getEstadisticasPedidos_dataServicio($From, $To, $ServicioId, $Tipo);
        }
    }

    public function getEstadisticasPedidos_dataRepuesto($From, $To, $SedeId, $ServicioId, $Tipo)
    {
        $db = new RelacionDAL();
        if ($SedeId == 'TODO' && $ServicioId == 'TODO') {
            return $db->getEstadisticasPedidos_dataRepuesto($From, $To, $Tipo);
        } else if ($SedeId != 'TODO' && $ServicioId == 'TODO') {
            return $db->getEstadisticasPedidos_dataSedeRepuesto($From, $To, $SedeId, $Tipo);
        } else if ($SedeId != 'TODO' && $ServicioId != 'TODO') {
            return $db->getEstadisticasPedidos_dataServicioRepuesto($From, $To, $ServicioId, $Tipo);
        }
    }

    // <editor-fold defaultstate="collapsed" desc="EXCEL">
    public function getEstadisticasPedidos($From, $To, $SedeId, $ServicioId, $Tipo, $TipoSolicitud)
    {
        $db = new RelacionDAL();
        if ($TipoSolicitud == 0) {
            if ($SedeId == 'TODO' && $ServicioId == 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidos($From, $To, $Tipo, $TipoSolicitud);
            } else if ($SedeId != 'TODO' && $ServicioId == 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidosSede($From, $To, $SedeId, $Tipo, $TipoSolicitud);
            } else if ($SedeId != 'TODO' && $ServicioId != 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidosServicio($From, $To, $ServicioId, $Tipo, $TipoSolicitud);
            }
        } else {
            if ($SedeId == 'TODO' && $ServicioId == 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidosRepuesto($From, $To, $Tipo, $TipoSolicitud);
            } else if ($SedeId != 'TODO' && $ServicioId == 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidosSedeRepuesto($From, $To, $SedeId, $Tipo, $TipoSolicitud);
            } else if ($SedeId != 'TODO' && $ServicioId != 'TODO') {
                $Estadisticas = $db->getEstadisticasPedidosServicioRepuesto($From, $To, $ServicioId, $Tipo, $TipoSolicitud);
            }
        }
        
        foreach ($Estadisticas as $p) {
            $Costo = $db->getTODO($p->CodigoKrystalos);
            $p->Costo = "";
            if ($Costo != NULL) {
                $p->Costo = $Costo->PCOSTO;
            } else {
                $p->Costo = 'ERROR';
            }
        }

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
         $this->objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Reporte desde '.$From .' hasta ' . $To);
        $this->objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $cont, 'Sede')
            ->setCellValue('B' . $cont, 'Servicio')
            ->setCellValue('C' . $cont, 'Nombre Solicitante')
            ->setCellValue('D' . $cont, 'Nombre Articulo')
            ->setCellValue('E' . $cont, 'Dirigido A')
            ->setCellValue('F' . $cont, 'Fecha Entrega')
            ->setCellValue('G' . $cont, 'Total Entregado')
            ->setCellValue('H' . $cont, 'Costo (Unidad)')
            ->setCellValue('I' . $cont, 'Costo Total');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':H' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        //        echo print_r($array);
        foreach ($Estadisticas as $value) {
            $cont++;
            //            echo $value->Nombre . " " . $value->Costo . "\n";
            $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, $value->Sede)
                ->setCellValue('B' . $cont, $value->Servicio)
                ->setCellValue('C' . $cont, $value->NombreSolicitante)
                ->setCellValue('D' . $cont, $value->Nombre)
                ->setCellValue('E' . $cont, $value->DirigidoA)
                ->setCellValue('F' . $cont, $value->FechaEntrega)
                ->setCellValue('G' . $cont, $value->TotalEntregado)
                ->setCellValue('H' . $cont, $value->Costo)
                ->setCellValue('I' . $cont, $value->TotalEntregado * (int) $value->Costo);
        }
        //        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        //// Miscellaneous glyphs, UTF-8
        //        $this->objPHPExcel->setActiveSheetIndex(0)
        //                ->setCellValue('A4', 'Miscellaneous glyphs')
        //                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
        // Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('Informe Estadistico');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="EstadisticasAlmacen_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
        // Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function getTODO()
    {
        $db = new RelacionDAL();
        $Plantillas = $db->getAllData();
        foreach ($Plantillas as $p) {
            $p->Costo = $db->getTODO($p->CodigoKrystalos)->PCOSTO;
        }

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
            ->setCellValue('A' . $cont, 'Codigo Krystalos')
            ->setCellValue('B' . $cont, 'Nombre Krystalos')
            ->setCellValue('C' . $cont, 'Nombre')
            ->setCellValue('D' . $cont, 'Cantidad')
            ->setCellValue('E' . $cont, 'Dias de Consumo')
            ->setCellValue('F' . $cont, 'Sede')
            ->setCellValue('G' . $cont, 'Servicio')
            ->setCellValue('H' . $cont, 'Costo');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':H' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(34);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(4);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(34);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        //        echo print_r($array);
        foreach ($Plantillas as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cont, $value->CodigoKrystalos)
                ->setCellValue('B' . $cont, $value->NombreKrystalos)
                ->setCellValue('C' . $cont, $value->Nombre)
                ->setCellValue('D' . $cont, $value->Cantidad)
                ->setCellValue('E' . $cont, $value->DiasConsumo)
                ->setCellValue('F' . $cont, $value->Sede)
                ->setCellValue('G' . $cont, $value->Servicio)
                ->setCellValue('H' . $cont, $value->Costo);
        }
        //        $this->objPHPExcel->getActiveSheet()->getStyle('H1:H'.$cont)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
        //// Miscellaneous glyphs, UTF-8
        //        $this->objPHPExcel->setActiveSheetIndex(0)
        //                ->setCellValue('A4', 'Miscellaneous glyphs')
        //                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
        // Rename worksheet
        $this->objPHPExcel->getActiveSheet()->setTitle('informe');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . 'InformeAlmacen_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
        // Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');

        //        return $Plantillas;
    }

    // </editor-fold>

    public function getAllByUsuarioId($ServicioId, $UsuarioId, $Tipo)
    {
        $db = new RelacionDAL();
        return $db->getAllByUsuarioId($ServicioId, $UsuarioId, $Tipo);
    }

    public function IsInDB($ServicioId, $UsuarioId, $ArticuloId)
    {
        $db = new RelacionDAL();
        $r = $db->IsInDB($ServicioId, $UsuarioId, $ArticuloId);
        if (count($r) > 0) {
            return TRUE;
        }
        return False;
    }

    public function CreateRelacion($Relacion)
    {
        $db = new RelacionDAL();
        if (count($Relacion) > 0) {
            $listado = $this->MAPToArray($Relacion);
            if (count($listado) > 0) {
                $r = $db->CreateRelacion($listado);
                return $r;
            } else {
                return "Ya se encuentras registrados estos articulos";
            }
        } else {
            return "No puede crear el PLANTILLA en blanco, Debe añadir un articulo.";
        }
    }

    public function DeleteRelacion($PedidoAmlacenId)
    {
        $db = new RelacionDAL();
        $Pedido = $this->GetAllById($PedidoAmlacenId);
        return $db->UpdateRelacion($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function UpdateRelacion($list)
    {
        $db = new RelacionDAL();
        return $db->UpdateRelacion($this->MAPToUpdate($list), $list->RelacionCostoId);
    }

    public function EditarLimite($list)
    {
        $db = new RelacionDAL();
        return $db->UpdateRelacion($this->MAPToUpdateLimite($list), $list->RelacionCostoId);
    }

    public function MAPToArray($list)
    {
        $list2 = array();
        $dbi = new RelacionBLL();
        for ($index = 0; $index < count($list); $index++) {
            if (!$dbi->IsInDB($list[$index]->ServicioId, $list[$index]->UsuarioId, $list[$index]->Articulo->ArticuloId)) {
                array_push($list2, array(
                    'ArticuloId' => $list[$index]->Articulo->ArticuloId,
                    'Cantidad' => $list[$index]->Cantidad,
                    'DiasConsumo' => $list[$index]->DiasConsumo,
                    'SedeId' => $list[$index]->SedeId,
                    'Limite' => 0,
                    'ServicioId' => $list[$index]->ServicioId,
                    'UsuarioId' => $list[$index]->UsuarioId,
                    'CreatedBy' => $list[$index]->CreatedBy
                ));
            }
        }

        return $list2;
    }

    public function MAPToUpdate($list)
    {
        $list2 = array();
        array_push($list2, array(
            'ArticuloId' => $list->Articulo->originalObject->ArticuloId,
            'Cantidad' => $list->Cantidad,
            'DiasConsumo' => $list->DiasConsumo,
            'Estado' => $list->Estado,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToUpdateLimite($list)
    {
        $list2 = array();
        array_push($list2, array(
            'Limite' => $list->Limite,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToDelete($list)
    {
        $list2 = array();
        array_push($list2, array(
            'Estado' => 'Inactivo',
            'ModifiedAt' => $list->ModifiedAt,
        ));
        return $list2;
    }

    function getDatetimeNow()
    {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
}
