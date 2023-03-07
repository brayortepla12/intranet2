<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/RondaVerificacionDAL.php';
require_once dirname(__FILE__) . '/DetalleRondaVerificacionBLL.php';
require_once dirname(__FILE__) . '/PacienteBLL.php';
require_once dirname(__FILE__) . '/MedicamentoBLL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
include_once dirname(__FILE__) . "/../../DAL/db.php";
require __DIR__ . "/../../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RondaVerificacionBLL {

    private $objPHPExcel;

    public function __construct() {
        $this->objPHPExcel = new Spreadsheet();
    }
    
    public function GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionBLL();
        return $Helper->GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $MedicamentoId);
    }

    public function GetRondaVerificaciones() {
        $Helper = new RondaVerificacionDAL();
        return $Helper->GetRondaVerificaciones();
    }

    public function GetAllRondas_Lite() {
        $Helper = new RondaVerificacionDAL();
        return $Helper->GetAllRondas_Lite();
    }

    public function GetRondaById($RondaVerificacionId) {
        $Helper = new RondaVerificacionDAL();
        return $Helper->GetRondaById($RondaVerificacionId);
    }

    public function GetRondaById_MediamentoId($RondaVerificacionId, $MedicamentoId, $Fecha) {
        $Helper = new RondaVerificacionDAL();
        return $Helper->GetRondaById_MediamentoId($RondaVerificacionId, $MedicamentoId, $Fecha);
    }

    public function RondaVerificacion_preview($RondaVerificacionId, $MedicamentoId, $Fecha) {
        $Helper = new DetalleRondaVerificacionBLL();
        $mh = new MedicamentoBLL();
        $Medicamento = $mh->GetMedicamentoById($MedicamentoId);
        $Ronda = $this->GetRondaById_MediamentoId($RondaVerificacionId, $MedicamentoId, $Fecha);
        $obj = new stdClass();
        if (count($Medicamento) > 0) {
            $obj = $Ronda[0];
            $obj->NombreMedicamento = $Medicamento[0]->Nombre;
            $obj->MedicamentoId = $Medicamento[0]->MedicamentoId;
            $obj->DetallesRondaVerificacion = $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_preview($RondaVerificacionId, $Medicamento[0]->MedicamentoId);
        }
        return $obj;
    }

    public function GetConsecutivos($RondaVerificacionId, $MedicamentoId, $Fecha) {
        $Helper = new RondaVerificacionDAL();
        $obj = new stdClass();
        $obj = $Helper->GetConsecutivos($RondaVerificacionId, $MedicamentoId, $Fecha)[0];
        return $obj;
    }

    public function GetEstadisticasExcel($Mes, $Year, $Estado) {
        $Helper = new RondaVerificacionDAL();
        $mh = new MedicamentoBLL();
        if ($Estado == 'UNIDOSIS') {
            $Estado = 'Unidosis';
            $Datos = $Helper->GetEstadisticasUnidosis($Mes, $Year, $Estado);
            $this->GenerarExcelEstadisticas($Datos);
        } else if ($Estado == 'LOTEADO') {
            $Estado = 'Loteado';
            $Datos = $Helper->GetEstadisticasUnidosis($Mes, $Year, $Estado);
            $this->GenerarExcelEstadisticas($Datos);
        } else {
            echo "Error";
        }
    }

    public function GenerarExcelEstadisticas($Datos) {
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
                ->setCellValue('A' . $cont, 'Fecha')
                ->setCellValue('B' . $cont, 'Nombre')
                ->setCellValue('C' . $cont, 'Concentracion')
                ->setCellValue('D' . $cont, 'Nombre Abreviado')
                ->setCellValue('E' . $cont, 'Cant. A Preparar')
                ->setCellValue('F' . $cont, 'Cant. Utilizada')
                ->setCellValue('G' . $cont, 'Codigo Krystalos')
                ->setCellValue('H' . $cont, 'Precio UND')
                ->setCellValue('I' . $cont, 'Total');

        $this->objPHPExcel->getActiveSheet()->getStyle('A' . $cont . ':I' . $cont)->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($this->objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        // Ancho
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
        $this->objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(7);
//        echo print_r($array);
        foreach ($Datos as $value) {
            $cont++;
            $this->objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $cont, $value->Fecha)
                    ->setCellValue('B' . $cont, $value->Nombre)
                    ->setCellValue('C' . $cont, $value->Concentracion)
                    ->setCellValue('D' . $cont, $value->NombreAbreviado)
                    ->setCellValue('E' . $cont, $value->CantidadAPreparar)
                    ->setCellValue('F' . $cont, $value->CantidadUtilizada)
                    ->setCellValue('G' . $cont, $value->CodigoKrystalos)
                    ->setCellValue('H' . $cont, $value->PrecioCompra)
                    ->setCellValue('I' . $cont, floatval($value->PrecioCompra) * $value->CantidadUtilizada);
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
        header('Content-Disposition: attachment; filename="Estadistica_RV_' . $this->getDatetimeNow() . '.xlsx"');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, 'Xlsx');
// Write file to the browser
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function GetEstadisticas($Mes, $Year, $Estado) {
        $Helper = new RondaVerificacionDAL();
        $mh = new MedicamentoBLL();
        if ($Estado == 'UNIDOSIS') {
            $Estado = 'Unidosis';
            $Datos = $Helper->GetEstadisticasUnidosis($Mes, $Year, $Estado);
            return $Datos;
        } else if ($Estado == 'LOTEADO') {
            $Estado = 'Loteado';
            $Datos = $Helper->GetEstadisticasUnidosis($Mes, $Year, $Estado);
            return $Datos;
        } else {
            return [];
        }
    }

    public function UpdatePrecioMedicamento() {
        $mh = new MedicamentoBLL();
        $Helper = new RondaVerificacionDAL();
        $Medicamentos = $mh->GetMedicamentos();
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->load();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        foreach ($Medicamentos as $m) {
            $p = $Helper->getPrecioByCodigoMedicamento($m->CodigoKrystalos, $db);
            $mh->UpdatePrecioMedicamento([array(
            "PrecioCompra" => is_array($p) ? $p[0]->PCOSTO : 0,
            "FechaCompra" => is_array($p) ? $p[0]->FECHAMOV->format('Y-m-d') : ''
                )], $m->MedicamentoId);
        }
        return $Medicamentos;
    }

    public function RondaVerificacion_preview_productoInicial($RondaVerificacionId, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionBLL();
        return $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_productoInicial($RondaVerificacionId, $MedicamentoId);
    }

    public function RondaVerificacion_excel($RondaVerificacionId, $TipoMedicamentoId) {
        $Helper = new DetalleRondaVerificacionBLL();
        $hr = new RondaVerificacionBLL();
        $mh = new MedicamentoBLL();
        $Preview = array();
        $Medicamentos = $mh->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
        $Ronda = $hr->GetRondaById($TipoMedicamentoId);
        foreach ($Medicamentos as $m) {
            $obj = new stdClass();
            $obj = $Ronda[0];
            $obj->NombreMedicamento = $m->Nombre;
            $obj->MedicamentoId = $m->MedicamentoId;
            $obj->DetallesRondaVerificacion = $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_preview($RondaVerificacionId, $m->MedicamentoId);
            array_push($Preview, $obj);
        }
        return $Preview;
    }

    public function GetRondaVerificacionById_TipoMedicamento($RondaVerificacionId, $TipoMedicamentoId, $Sector, $Fecha, $TipoRonda) {
        $Helper = new RondaVerificacionDAL();
        $ph = new PacienteBLL();
        $RondaVerificacion = $Helper->GetRondaVerificacionById($RondaVerificacionId);
        $Pacientes = $ph->GetPacientesByRondaVerificacionId_forUpdate($RondaVerificacionId, $TipoMedicamentoId, $Sector, $Fecha, $TipoRonda);
        $RondaVerificacion[0]->Pacientes = $Pacientes;
        $RondaVerificacion[0]->TipoMedicamentoId = $TipoMedicamentoId;
        return $RondaVerificacion;
    }

    public function CreateRondaVerificacion($list) {
        $Helper = new RondaVerificacionDAL();
        foreach ($list as $tm) {
            foreach ($tm->RVs as $key => $rv) {
//                $r = $Helper->VerificarDiaBySector($rv->Sector, $tm->TipoMedicamentoId);
//                if (count($r) === 0) {
                $Ronda = $Helper->GetRondaByFecha_Estado($rv->Fecha, $tm->TipoRonda);
                $id = Null;

                if (count($Ronda) == 0) {
                    $id = $Helper->CreateRondaVerificacion($this->MAPToArray($rv, $tm->TipoRonda));
                } else if (($tm->TipoRonda === "Loteado" || $tm->TipoRonda === "Unidosis")) {
                    if ($Ronda[0]->Editable) {
                        $id = [$Ronda[0]->RondaVerificacionId];
                    } else {
                        return "RONDA CERRADA,YA NO PUEDES AÑADIR MAS INFORMACION.";
                    }
                }

                if ($id) {
                    $drvh = new DetalleRondaVerificacionBLL();
                    $id2 = $drvh->CreateDetalleRondaVerificacion($rv->Pacientes, $id[0], $rv->CreatedBy, $rv->Sector);
                    if (count($id2) > 0) {
                        unset($tm->RVs[$key]);
                    }
                }
//                }else{
//                    $rv->RondaVerificacionId = $r[0]->RondaVerificacionId;
//                    $rv->Error = "Hubo un error, ya se encuentra registrado este servicio, por favor editelo.";
//                }
            }
        }
        return $list;
    }

    public function UpdateRondaVerificacion($list) {
        $Helper = new RondaVerificacionDAL();
        $r = $Helper->VerificarDia($list->RondaVerificacionId);
        if (count($r) > 0) {
            if ($r[0]->Editable) {
                $id = $Helper->UpdateRondaVerificacion($this->MAPToUpdate($list), $list->RondaVerificacionId);
                if ($id) {
                    $drvh = new DetalleRondaVerificacionBLL();
                    $drvh->UpdateDetalleRondaVerificacion($list->Pacientes, $list->ModifiedBy);
                }
                return $id;
            } else {
                return "RONDA CERRADA,YA NO PUEDES AÑADIR MAS INFORMACION.";
            }
        } else {
            return "No se puede editar una Ronda del dia anterior";
        }
    }

    public function CreateConsecutivoRondaVerificacion($list) {
        $Helper = new RondaVerificacionDAL();
        $list->Consecutivo = $Helper->GetConsecutivoForRondaByMedicamento($list)[0]->Consecutivo + 1;
        return $Helper->CreateConsecutivoRondaVerificacion($this->MAPToConsecutivoRonda($list));
    }

    public function SelectUsuariosRondaVerificacion($list) {
        $Helper = new RondaVerificacionDAL();
        $hu = new UsuarioBLL();
        $r = $Helper->VerificarDia($list->RondaVerificacionId);
        $u = $hu->GetUsuarioById($list->UsuarioId);
        if ($u->IsLiderGrupoCM == 0) {
            return "Solo el lider de grupo puede seleccionar los usuarios para las etiquetas";
        } else if (count($r) > 0) {
            if ($r[0]->Editable) {
                $id = $Helper->UpdateRondaVerificacion($this->MAPToSelectUsuario($list), $list->RondaVerificacionId);
                return $id;
            } else {
                return "RONDA CERRADA,YA NO PUEDES AÑADIR MAS INFORMACION.";
            }
        } else {
            return "No se puede editar una Ronda del dia anterior";
        }
    }

    public function UpdateRondaVerificacion_DireccionTecnica($list) {
        $Helper = new RondaVerificacionDAL();
        $id = $Helper->UpdateRondaVerificacion($this->MAPToDireccionTecnica($list), $list->RondaVerificacionId);
        return $id;
    }

    public function UpdateRondaVerificacion_ACalidad($list) {
        $Helper = new RondaVerificacionDAL();
        $id = $Helper->UpdateRondaVerificacion($this->MAPToACalidad($list), $list->RondaVerificacionId);
        return $id;
    }

    public function UpdateRondaVerificacion_QFarmaceutico($list) {
        $Helper = new RondaVerificacionDAL();
        $id = $Helper->UpdateRondaVerificacion($this->MAPToQFarmaceutico($list), $list->RondaVerificacionId);
        return $id;
    }

    public function UpdateRondaVerificacion_AFarmacia($list) {
        $Helper = new RondaVerificacionDAL();
        $id = $Helper->UpdateRondaVerificacion($this->MAPToAFarmacia($list), $list->RondaVerificacionId);
        return $id;
    }

    public function MAPToConsecutivoRonda($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Mes' => $list->Mes,
            'Anno' => $list->Anno,
            'MedicamentoId' => $list->MedicamentoId,
            'TipoMedicamentoId' => $list->TipoMedicamentoId,
            'RondaVerificacionId' => $list->RondaVerificacionId,
            'Consecutivo' => $list->Consecutivo,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArray($list, $TipoRonda) {
        $list2 = Array();
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'TipoRonda' => $TipoRonda,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToDireccionTecnica($list) {
        $list2 = Array();
        array_push($list2, Array(
            'DireccionTecnica' => $list->DireccionTecnica,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToACalidad($list) {
        $list2 = Array();
        array_push($list2, Array(
            'ACalidad' => $list->ACalidad,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToQFarmaceutico($list) {
        $list2 = Array();
        array_push($list2, Array(
            'QFarmaceutico' => $list->QFarmaceutico,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToSelectUsuario($list) {
        $list2 = Array();
        array_push($list2, Array(
            'DireccionTecnicaId' => $list->DireccionTecnicaId,
            'ACalidadId' => $list->ACalidadId,
            'QFarmaceuticoId' => $list->QFarmaceuticoId,
            'AFarmaciaId' => $list->AFarmaciaId,
            'DireccionTecnica' => TRUE,
            'ACalidad' => TRUE,
            'QFarmaceutico' => TRUE,
            'AFarmacia' => TRUE,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToAFarmacia($list) {
        $list2 = Array();
        array_push($list2, Array(
            'AFarmacia' => $list->AFarmacia,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
