<?php

require_once dirname(__FILE__) . '/../../DAL/ambulancia/ReporteAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/HojaVidaAmbulanciaBLL.php';
require_once dirname(__FILE__) . '/DetallesAmbulanciaBLL.php';
require_once dirname(__FILE__) . '/SolicitudAmbulanciaBLL.php';

class ReporteAmbulanciaBLL {
    
    public function GetReporteBySolicitudMantenimientoId($SolicitudMantenimientoId) {
        $Helper = new ReporteAmbulanciaDAL();
        $Reporte = $Helper->GetReporteBySolicitudMantenimientoId($SolicitudMantenimientoId);
        if(count($Reporte) > 0){
            $hd = new DetallesAmbulanciaBLL();
            $Reporte[0]->Detalles = $hd->GetDetallesByReporteId($Reporte[0]->ReporteId);
            return $Reporte[0];
        }else{
            return "Error reporte.";
        }
    }

    public function GetNReporteAmbulancia() {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetNReporteAmbulancia();
    }

    public function GetCronogramaMantenimiento() {
        $Helper = new ReporteAmbulanciaDAL();
        $hm = new HojaVidaAmbulanciaBLL();
        $Moviles = $hm->GetALLHojas();
        for ($index = 0; $index < count($Moviles); $index++) {
            $Moviles[$index]->Cronograma = $Helper->GetCronogramaByMovil($Moviles[$index]->HojaVidaId);
        }
        return $Moviles;
    }

    public function GetCronogramaMantenimientoByReporteAmbulanciaId($ReporteAmbulanciaId) {
        $Helper = new ReporteAmbulanciaDAL();
        $listado = array();
        $list = $Helper->GetCronogramaMantenimientoByReporteAmbulanciaId($ReporteAmbulanciaId);
        for ($index = 0; $index < count($list); $index++) {
            array_push($listado, $list[$index]->CronogramaId);
        }
        return $listado;
    }

    public function GetReporteAmbulanciasByRango($From, $To) {
        $Helper = new ReporteAmbulanciaDAL();
        $List = $Helper->GetReporteAmbulanciaByRango($From, $To);
//        print_r($List);
        $he = new ExcelBLL();
        $he->BuildExcel_ReporteAmbulancias($List);
    }

    public function DeleteReporteAmbulanciaById($ReporteAmbulancia) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->UpdateReporteAmbulancia($this->MAPToDelete($ReporteAmbulancia), $ReporteAmbulancia->ReporteAmbulanciaId); // update
    }

    public function GetReporteAmbulanciaById($ReporteAmbulanciaId) {
        $Helper = new ReporteAmbulanciaDAL();
        $Reporte = $Helper->GetReporteAmbulanciaById($ReporteAmbulanciaId);
        $hd = new DetallesAmbulanciaBLL();
        $Reporte[0]->Detalles = $hd->GetDetallesByReporteId($ReporteAmbulanciaId);
        return $Reporte;
    }

    public function GetLastReporteAmbulanciaByHojaVida($HojaVidaId) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetLastReporteAmbulanciaByHojaVidaId($HojaVidaId);
    }

    public function GetAllReporteAmbulanciasBySede($SedeId) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetAllReporteAmbulanciasBySede($SedeId);
    }

    public function GetReporteAmbulanciaBySerivicioId($ServicioId) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetReporteAmbulanciaByServicioId($ServicioId);
    }

    public function GetReporteAmbulanciaByEquipoId($EquipoId) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetReporteByEquipoId($EquipoId);
    }
    
    public function GetCronogramaReporteAmbulanciaByEquipoId($EquipoId) {
        $Helper = new ReporteAmbulanciaDAL();
        $hd = new DetallesAmbulanciaBLL();
        $Detalles = $hd->GetAll();
        foreach ($Detalles as $d) {
            $d->Reportes = $Helper->GetCronogramaReporteAmbulanciaByEquipoId($EquipoId, $d->DetalleId);
        }
        return $Detalles; 
    }

    public function GetAllReporteAmbulancias() {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetAllReporteAmbulancias();
    }

    public function GetReporteAmbulanciasBetweenFecha($From, $To) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetReporteAmbulanciasBetweenFecha($From, $To);
    }

    public function GetReporteAmbulanciasBetweenFechaBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteAmbulanciaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetReporteAmbulanciasBetweenFechaBySede($From, $To, $value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        return $list;
    }

    public function GetReporteAmbulanciasBetweenFechaALL($From, $To) {
        $Helper = new ReporteAmbulanciaDAL();
        return $this->GenerateEstadistica($Helper->GetReporteAmbulanciasBetweenFechaALL($From, $To));
    }

    public function GetReporteAmbulanciasBetweenFechaALLBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteAmbulanciaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetReporteAmbulanciasBetweenFechaALLBySedeId($From, $To, $value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        return $this->GenerateEstadistica($list, $UsuarioId);
    }

    public function GetEstadisticas($Year, $Month) {
        $Helper = new ReporteAmbulanciaDAL();
        return $Helper->GetEstadisticas($Year, $Month);
    }

    public function CreateReporteAmbulancia($list) {
        $Helper = new ReporteAmbulanciaDAL();
        $id = $Helper->CreateReporte($this->MAPToArray($list));
        if ($list->SolicitudMantenimientoId != NULL && count($id) > 0) {
            $hs = new SolicitudAmbulanciaBLL();
            $hs->UpdateEstadoSolicitud("Vinculado", $list->SolicitudMantenimientoId);
        }
        if (!$id) {
            return 'insert failed: ';
        } else if (count($list->Detalles) > 0) {

            $hd = new DetallesAmbulanciaBLL();
            $hd->CreateDetalleReporte($list->Detalles, $id[0]);
        }
        return $id;
    }

    public function CreateReporteAmbulanciaCronograma($Cronogramalist, $ReporteAmbulanciaId) {
        $Helper = new ReporteAmbulanciaDAL();
        $Helper->CreateReporteAmbulanciaCronograma($this->MAPToReporteAmbulanciaCronograma($Cronogramalist, $ReporteAmbulanciaId));
    }

    // <editor-fold defaultstate="collapsed" desc="Logicas">
    private function LogicaKiloMetraje($list, $ReporteAmbulanciaId) {
        $Hkm = new KMBLL();
        $km = $Hkm->GetLastKMByHojaVidaId($list->HojaVidaId);
        if ($km != NULL) {
            if ($km[0]->KM < $list->KM) {
                $KmId = $Hkm->CreateKM2($this->MAPToKM($list, $ReporteAmbulanciaId));
                if ($KmId != NULL) {
                    return $KmId;
                } else {
                    return "Se ha creado el reporte, pero no se ha podido actualizar el Kilometraje, debido a un error.";
                }
            } else {
                return "Se ha creado el reporte, ERROR al actualizar el Kilometraje, El kilometraje ingresado NO puede ser menor que el kilometraje actual.";
            }
        } else {
            $Hkm = new KMBLL();
            $KmId = $Hkm->CreateKM2($this->MAPToKM($list, $ReporteAmbulanciaId));
            if ($KmId != NULL) {
                return $KmId;
            } else {
                return "Se ha creado el reporte, pero no se ha podido actualizar el Kilometraje, debido a un error.";
            }
        }
    }

// </editor-fold>
    public function UpdateReporteAmbulancia($list) {
        $Helper = new ReporteAmbulanciaDAL();
        $obj = $Helper->UpdateReporte($this->MAPToUpdate($list), $list->ReporteId);
        if (!is_string($obj)) {
            $hd = new DetallesAmbulanciaBLL();
            $hd->DeleteDetalleReporteByReporteId($list->ReporteId);
            $hd->CreateDetalleReporte($list->Detalles, $list->ReporteId);
            #return $this->LogicaKiloMetraje($list, $list->ReporteAmbulanciaId);
        }
        return $obj;
    }

    public function MAPToDelete($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => 'Inactivo',
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    private function BuildUrl($RecibeEmail, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($RecibeEmail, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente#/ver_reporte_externo/$Id/$token";
        return $Url;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'HojaVidaId' => $list->HojaVidaId,
            'SolicitudMantenimientoId' => $list->SolicitudMantenimientoId,
            'TipoMantenimiento' => $list->TipoMantenimiento,
            'Fecha' => $list->Fecha,
            'LastKm' => $list->LastKm,
            'Km' => $list->Km,
            'Descripcion' => $list->Descripcion,
            'Notas' => $list->Notas,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToKM($list, $ReporteAmbulanciaId) {
        $list2 = Array();
        array_push($list2, array(
            'HojaVidaId' => $list->HojaVidaId,
            'KM' => $list->KM,
            'ReporteAmbulanciaId' => $ReporteAmbulanciaId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'HojaVidaId' => $list->HojaVidaId,
            'TipoMantenimiento' => $list->TipoMantenimiento,
            'Fecha' => $list->Fecha,
            'LastKm' => $list->LastKm,
            'Km' => $list->Km,
            'Descripcion' => $list->Descripcion,
            'Notas' => $list->Notas,
            'ModifiedBy' => $list->ModifiedBy,
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
