<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ReporteSSTDAL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/SolicitudBLL.php';
require_once dirname(__FILE__) . '/HojaVidaSSTBLL.php';
require_once dirname(__FILE__) . '/EstadisticaLogicaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class ReporteSSTBLL extends EstadisticaLogicaBLL {

    public function GetNReporte() {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetNReporte();
    }
    
    public function GetReporteByEmail($Email) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetReporteByEmail($Email);
    }

    public function GetReporteById($ReporteId) {
        $Helper = new ReporteSSTDAL();
        $r = $Helper->GetReporteById($ReporteId);
        if (count($r) > 0) {
            return $r;
        }else{
            return "No existe este reporte.";
        }
        
    }

    public function GetReportesPlantasElectricas($UsuarioId) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetReportesPlantasElectricas($UsuarioId);
    }

    public function GetAllReportesByUsuarioServicio($UsuarioId) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetAllReportesByUsuarioServicio($UsuarioId);
    }

    public function GetReporteBySerivicioId($SedeId, $ServicioId, $Year, $Mes) {
        $Helper = new ReporteSSTDAL();
        if ($Mes != '0' && $ServicioId != '0') {
            return $Helper->GetReporteByServicioId_year_mes($SedeId, $ServicioId,$Year, $Mes);
        }else if ($ServicioId != '0' && $Mes == '0') {
            return $Helper->GetReporteByServicioId_Sede($SedeId, $ServicioId);
        }else if ($ServicioId == '0' && $Mes == '0') {
            return $Helper->GetReporteByServicioIdALL($SedeId);
        }else if ($ServicioId == '0' && $Mes != '0') {
            return $Helper->GetReporteBy_year_mes($SedeId, $Year, $Mes);
        }
    }

    public function GetReporteByEquipoId($EquipoId) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetReporteByEquipoId($EquipoId);
    }

    public function GetAllReportes() {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetAllReportes();
    }

    public function GetReportesBetweenFecha($From, $To) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetReportesBetweenFecha($From, $To);
    }

    public function GetReportesBetweenFechaBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteSSTDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetReportesBetweenFechaBySede($From, $To, $value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        return $list;
    }

    public function GetReportesBetweenFechaALL($From, $To) {
        $Helper = new ReporteSSTDAL();
        return $this->GenerateEstadistica($Helper->GetReportesBetweenFechaALL($From, $To));
    }

    public function GetReportesBetweenFechaALLBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteSSTDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetReportesBetweenFechaALLBySedeId($From, $To, $value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        return $this->GenerateEstadistica($list, $UsuarioId);
    }

    public function GetEstadisticas($Year, $Month) {
        $Helper = new ReporteSSTDAL();
        return $Helper->GetEstadisticas($Year, $Month);
    }

    public function CreateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteSSTDAL();
        $id = $Helper->CreateReporte($this->MAPToArray($list));
        if (!$id) {
            echo 'insert failed: ';
        } else {
            $hh = new HojaVidaSSTBLL();
            $h = json_decode($hh->GetHojasByHojaVidaId($list->EquipoId))[0];
            $h->FechaRecarga = $list->FechaRecarga;
            $h->FechaVencimiento = $list->FechaVencimiento;
            $h->ModifiedBy = $list->CreatedBy;
            $hh->UpdateHojaVida($h);
            return $id;
        }
    }

    public function ReenviarCorreo($ReporteId) {
        $Helper = new ReporteSSTDAL();
        $r = $Helper->GetReporteById($ReporteId)[0];
        if ($r) {
            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $hu = new UsuarioBLL();
            $u = $hu->GetUsuarioByNombre($r->RecibeNombre);
            $Email->EnviarEmail_ReporteManual($u->Email, $r->RecibeNombre, $r->TipoServicio . " - SISTEMAS", $this->BuildUrl($u->Email, $r->ReporteId), $Empresa);
            return [$r->ReporteId];
        } else {
            return "No existe este reporte.";
        }
    }
    public function UpdateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteSSTDAL();
        $id = $Helper->FirmarReporte($this->MAPToArray4($list), $list->ReporteId);
        if (!$id) {
            echo 'insert failed: ';
        } else {
            $hh = new HojaVidaSSTBLL();
            $h = json_decode($hh->GetHojasByHojaVidaId($list->EquipoId))[0];
            $h->FechaRecarga = $list->FechaRecarga;
            $h->FechaVencimiento = $list->FechaVencimiento;
            $h->ModifiedBy = $list->ModifiedBy;
            $hh->UpdateHojaVida($h);
            return $id;
        }
    }

    public function DeleteReporteById($Reporte) {
        $Helper = new ReporteSSTDAL();
        return $Helper->FirmarReporte($this->MAPToDelete($Reporte), $Reporte->ReporteId); // update
    }

    private function BuildUrl($RecibeEmail, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($RecibeEmail, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente/#/ver_reporte_externo_sst/$Id/$token";
        return $Url;
    }

    public function MAPToDelete($list) {
        $list2 = Array();
        array_push($list2, Array(
            'EstadoReporte' => 'Inactivo',
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToArray4($list) {
        $list2 = Array();
        $tz_object = new DateTimeZone('America/Bogota');
        array_push($list2, Array(
            'NumeroReporte' => $list->NumeroReporte,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Ubicacion' => $list->Ubicacion,
            'EquipoId' => $list->EquipoId == '' ? NULL : $list->EquipoId,
            'FechaInspeccion' => $list->FechaInspeccion,
            'FechaRecarga' => $list->FechaRecarga,
            'FechaVencimiento' => $list->FechaVencimiento,
            'Acceso' => $list->Acceso,
            'Demarcacion' => $list->Demarcacion,
            'Senalizacion' => $list->Senalizacion,
            'InstalacionSitioAsignado' => $list->InstalacionSitioAsignado,
            'InstruccionesUso' => $list->InstruccionesUso,
            'AlturaAdecuada' => $list->AlturaAdecuada,
            'Corneta' => $list->Corneta,
            'Manguera' => $list->Manguera,
            'CargaExtintor' => $list->CargaExtintor,
            'ManijaTransporte' => $list->ManijaTransporte,
            'ManijaDescarga' => $list->ManijaDescarga,
            'Pasador' => $list->Pasador,
            'SelloSeguridad' => $list->SelloSeguridad,
            'Responsable' => $list->Responsable,
            'Observacion' => $list->Observacion,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        $tz_object = new DateTimeZone('America/Bogota');
        array_push($list2, Array(
            'NumeroReporte' => $list->NumeroReporte,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Ubicacion' => $list->Ubicacion,
            'EquipoId' => $list->EquipoId == '' ? NULL : $list->EquipoId,
            'FechaInspeccion' => $list->FechaInspeccion,
            'FechaRecarga' => $list->FechaRecarga,
            'FechaVencimiento' => $list->FechaVencimiento,
            'Acceso' => $list->Acceso,
            'Demarcacion' => $list->Demarcacion,
            'Senalizacion' => $list->Senalizacion,
            'InstalacionSitioAsignado' => $list->InstalacionSitioAsignado,
            'InstruccionesUso' => $list->InstruccionesUso,
            'AlturaAdecuada' => $list->AlturaAdecuada,
            'Corneta' => $list->Corneta,
            'Manguera' => $list->Manguera,
            'CargaExtintor' => $list->CargaExtintor,
            'ManijaTransporte' => $list->ManijaTransporte,
            'ManijaDescarga' => $list->ManijaDescarga,
            'Pasador' => $list->Pasador,
            'SelloSeguridad' => $list->SelloSeguridad,
            'Responsable' => $list->Responsable,
            'Observacion' => $list->Observacion,
            'CreatedBy' => $list->CreatedBy
        ));

        return $list2;
    }

    public function MAPToArray3($list, $NombreArchivo) {
        $list2 = Array();
        $tz_object = new DateTimeZone('America/Bogota');
        array_push($list2, Array(
            'NumeroReporte' => $list->NumeroReporte,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Ubicacion' => $list->Ubicacion,
            'EquipoId' => $list->EquipoId == '' ? NULL : $list->EquipoId,
            'FechaInspeccion' => $list->FechaInspeccion,
            'FechaRecarga' => $list->FechaRecarga,
            'FechaVencimiento' => $list->FechaVencimiento,
            'Acceso' => $list->Acceso,
            'Demarcacion' => $list->Demarcacion,
            'Senalizacion' => $list->Senalizacion,
            'InstalacionSitioAsignado' => $list->InstalacionSitioAsignado,
            'InstruccionesUso' => $list->InstruccionesUso,
            'AlturaAdecuada' => $list->AlturaAdecuada,
            'Corneta' => $list->Corneta,
            'Manguera' => $list->Manguera,
            'CargaExtintor' => $list->CargaExtintor,
            'ManijaTransporte' => $list->ManijaTransporte,
            'ManijaDescarga' => $list->ManijaDescarga,
            'Pasador' => $list->Pasador,
            'SelloSeguridad' => $list->SelloSeguridad,
            'Responsable' => $list->Responsable,
            'Observacion' => $list->Observacion,
        ));

        return $list2;
    }

    public function MAPToArray2($list, $Estado = "Firmado") {
        $list2 = Array();
        array_push($list2, Array(
            'RecibeFirma' => $list->Firma,
            'Estado' => $Estado,
            'ModifiedBy' => $list->NombreUsuario,
            'ModifiedAt' => $this->getDatetimeNow()
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
