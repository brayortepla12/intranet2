<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ReporteSistemaDAL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/SolicitudBLL.php';
require_once dirname(__FILE__) . '/HojaVidaSistemaBLL.php';
require_once dirname(__FILE__) . '/EstadisticaLogicaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/ExcelBLL.php';

class ReporteSistemaBLL extends EstadisticaLogicaBLL {

    public function GetNReporte() {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetNReporte();
    }

    public function GetReporteByPersonaRecibeId($PersonaRecibeId) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReporteByRecibeId($PersonaRecibeId);
    }

    public function GetReporteById($ReporteId) {
        $Helper = new ReporteSistemaDAL();
        $r = $Helper->GetReporteById($ReporteId);
        if (count($r) > 0) {
            return $r;
        } else {
            return "No existe este reporte.";
        }
    }

    public function GetReportesPlantasElectricas($UsuarioId) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReportesPlantasElectricas($UsuarioId);
    }

    public function GetAllReportesByUsuarioServicio($UsuarioId, $SedeId, $ServicioId, $TipoServicio, $TipoArticulo) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetAllReportesByUsuarioServicio($UsuarioId, $SedeId, $ServicioId, $TipoServicio, $TipoArticulo);
    }

    public function GetAllReportesByUsuarioServicioExcel($UsuarioId, $SedeId, $ServicioId, $TipoServicio, $TipoArticulo) {
        $Helper = new ReporteSistemaDAL();
        $Reportes = $Helper->GetAllReportesByUsuarioServicio($UsuarioId, $SedeId, $ServicioId, $TipoServicio, $TipoArticulo);
        $eh = new ExcelBLL();
        $Reportes = json_decode($Reportes);
        $eh->BuildExcelInformeSistemas($Reportes);
    }

    public function GetReporteBySolicitudId($SolicitudId) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReporteBySolicitudId($SolicitudId);
    }

    public function GetReporteBySerivicioId($SedeId, $ServicioId, $Year, $Mes) {
        $Helper = new ReporteSistemaDAL();
        if ($Mes != '0' && $ServicioId != '0') {
            return $Helper->GetReporteByServicioId_year_mes($SedeId, $ServicioId, $Year, $Mes);
        } else if ($ServicioId != '0' && $Mes == '0') {
            return $Helper->GetReporteByServicioId_Sede($SedeId, $ServicioId);
        } else if ($ServicioId == '0' && $Mes == '0') {
            return $Helper->GetReporteByServicioIdALL($SedeId);
        } else if ($ServicioId == '0' && $Mes != '0') {
            return $Helper->GetReporteBy_year_mes($SedeId, $Year, $Mes);
        }
    }

    public function GetReporteByEquipoId($EquipoId) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReporteByEquipoId($EquipoId);
    }

    public function GetReporteByEquipoIdByYear($EquipoId, $Year) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReporteByEquipoIdByYear($EquipoId, $Year);
    }

    public function GetAllReportes() {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetAllReportes();
    }

    public function GetReportesBetweenFecha($From, $To) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetReportesBetweenFecha($From, $To);
    }

    public function GetReportesBetweenFechaBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteSistemaDAL();
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
        $Helper = new ReporteSistemaDAL();
        return $this->GenerateEstadistica($Helper->GetReportesBetweenFechaALL($From, $To));
    }

    public function GetReportesBetweenFechaALLBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteSistemaDAL();
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
        $Helper = new ReporteSistemaDAL();
        return $Helper->GetEstadisticas($Year, $Month);
    }

    public function CreateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteSistemaDAL();
        $id = $Helper->CreateReporte($this->MAPToArray($list));
        if (!$id) {
            echo 'insert failed: ';
        } else {
            $Hh = new HojaVidaSistemaBLL();
            $Hh->UpdateFechaUltimoMantenimientoHojaVida($list->EquipoId);
//            if ($list->TipoServicio == "INSTALACION" || $list->TipoServicio == "PREVENTIVO" || $list->TipoServicio == "CORRECTIVO") {
//                $Helper = new HojaVidaBLL();
//                $Helper->UpdateFechaHojaVida($list, $list->EquipoId);
//            }
            // CREAR EVENTO SOLICITUD
            $Helper->CreateEventoSolicitud([
                [
                    "ReporteId" => $id[0],
                    "NombreBreveEvento" => "Reporte " . $list->TipoServicio,
                    "NombreUsuario" => $list->ResponsableNombre,
                    "Descripcion" => $list->FallaDetectada,
                    "SolicitudId" => $list->SolicitudId,
                    "FechaEvento" => $this->getDatetimeNow()
                ]
            ]);
            if ($list->TipoServicio == "PREVENTIVO" || $list->TipoServicio == "CORRECTIVO") {
                // ACTUALIZAMOS EL CRONOGRAMA
                $MesProx = $Helper->GetMesProximoEnCronograma($list->EquipoId);
                if (count($MesProx) > 0) {
                    $Helper->CreateRdc([Array(
                    'DetalleCronogramaId' => $MesProx[0]->DetalleCronogramaId,
                    'ReporteId' => $id[0],
                    'HojaVidaId' => $list->EquipoId,
                    'CreatedAt' => $this->getDatetimeNow()
                    )]);
                }
            }
            if ($list->TipoServicio == "TRASLADO") {
                $Hh->TrasladoHojaVida($list, $list->EquipoId);
            }
            if ($list->TipoArticulo == "Impresora") {
                $Hh->UpdateContadorHojaVida($list, $list->EquipoId);
            }

            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $Email->EnviarEmail_ReporteManual($list->RecibeEmail, $list->RecibeNombre, $list->TipoServicio . " - SISTEMAS", $this->BuildUrlPersonaId($list->RecibeId, $id[0]), $Empresa);
            return $id;
        }
    }

    public function ReenviarCorreo($ReporteId) {
        $Helper = new ReporteSistemaDAL();
        $r = $Helper->GetReporteById($ReporteId)[0];
        if ($r) {
            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $hu = new UsuarioBLL();
            $u = $hu->GetUsuarioByNombre($r->RecibeNombre);
            $Email->EnviarEmail_ReporteManual($u->Email, $r->RecibeNombre, $r->TipoServicio . " - SISTEMAS", $this->BuildUrlPersonaId($u->PersonaId, $r->ReporteId), $Empresa);
            return [$r->ReporteId];
        } else {
            return "No existe este reporte.";
        }
    }

    public function CreateReporteExterno($list, $NombreArchivo) {
        $Helper = new ReporteSistemaDAL();
        $id = $Helper->CreateReporte($this->MAPToArray3($list, $NombreArchivo));
//        if ($list->TipoServicio == "INSTALACION" || $list->TipoServicio == "PREVENTIVO" || $list->TipoServicio == "CORRECTIVO") {
//            $Helper = new HojaVidaBLL();
//            $Helper->UpdateFechaHojaVida($list, $list->EquipoId);
//        }
        if (!$id) {
            echo 'insert failed: ';
        } else {
            if ($list->SolicitudId) {
                $Helper = new SolicitudBLL();
                $Helper->CambiarEstadoSolicitud($list->SolicitudId, "Borrador");
            }
            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $Email->EnviarEmail_ReporteManual($list->RecibeEmail, $list->RecibeNombre, $list->TipoServicio, $this->BuildUrlPersonaId($list->RecibeId, $id[0]), $Empresa);
            return $id;
        }
    }

    public function UpdateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteSistemaDAL();
        $id = $Helper->FirmarReporte($this->MAPToArray4($list), $list->ReporteId);
        if (!$id) {
            echo 'insert failed: ';
        } else {
            if ($list->TipoServicio == "TRASLADO") {
                $Helper = new HojaVidaBLL();
                $Helper->TrasladoHojaVida($list, $list->EquipoId);
            }
            if ($list->SolicitudId) {
                $Helper = new SolicitudBLL();
                $Helper->CambiarEstadoSolicitud($list->SolicitudId, $Estado);
            }

            return $id;
        }
    }

    public function DeleteReporteById($Reporte) {
        $Helper = new ReporteSistemaDAL();
        return $Helper->FirmarReporte($this->MAPToDelete($Reporte), $Reporte->ReporteId); // update
    }

//    public function AutoFirmarTODO($UsuarioId) {
//        $Helper = new ReporteSistemaDAL();
//        $Reportes = $Helper->GetAllReportesByUsuarioServicio_Autofirmar($UsuarioId);
////        echo print_r($Reportes);
//        foreach ($Reportes as $value) {
//            if ($value->RecibeFirma == NULL) {
////                echo print_r($value);
//                $this->FirmarReporte($value->ReporteId, $value->RecibeEmail);
//            }
//        }
//        return [true];
//    }

    public function AutoFirmarTODOByRecibeId($PersonaRecibeId) {
        $Helper = new ReporteSistemaDAL();
        $Helper->FirmarReporteALL($PersonaRecibeId);
        return [true];
    }

    public function FirmarReporte($ReporteId, $RecibeEmail) {
        $Helper = new UsuarioBLL();
        $Usuario = $Helper->GetUsuarioByEmail($RecibeEmail);
        if ($Usuario) {
            $Helper = new ReporteSistemaDAL();
//            $Reporte = $Helper->GetReporteById($ReporteId);
//            if (count($Reporte) > 0) {
                if ($Reporte[0]->RecibeId == $Usuario->PersonaId) {
//                    if ($Reporte[0]->SolicitudId) {
//                        $SHelper = new SolicitudBLL();
//                        $SHelper->CambiarEstadoSolicitud($Reporte[0]->SolicitudId, "Completado");
//                    }
                    return $Helper->FirmarReporte($this->MAPToArray2($Usuario), $ReporteId);
                } else {
                    return 'El usuario no coincide con este reporte.';
                }
//            } else {
//                return 'Error al firmar.';
//            }
        } else {
            return 'No existe este usuario';
        }
    }

    private function BuildUrl($RecibeEmail, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($RecibeEmail, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente/#/ver_reporte_externo_sistemas/$Id/$token";
        return $Url;
    }
    
    private function BuildUrlPersonaId($PersonaId, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($PersonaId, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente/#/ver_reporte_externo_sistemas/$Id/$token";
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
        $date = new DateTime($list->RecibeHora);
        $date->setTimezone($tz_object);
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'NumeroReporte' => $list->NumeroReporte,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Solicitante' => $list->Solicitante,
            'SolicitudId' => $list->SolicitudId,
            'Ubicacion' => $list->Ubicacion,
            'TipoServicio' => $list->TipoServicio,
            'EquipoId' => $list->EquipoId,
            'Contador' => $list->Contador,
            'Fotos' => $list->Fotos,
            'FallaReportada' => $list->FallaReportada,
            'FallaDetectada' => $list->FallaDetectada,
            'ProcedimientoRealizado' => $list->ProcedimientoRealizado,
            'Observaciones' => $list->Observaciones,
            'EstadoFinal' => $list->EstadoFinal,
            'Responsable' => $list->Responsable,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
            'RecibeFecha' => $list->RecibeFecha,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'RecibeId' => $list->RecibeId,
            'ResponsableId' => $list->ResponsableId,
            'TipoReporte' => $list->TipoReporte,
            'ReporteArchivo' => $list->ReporteArchivo,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        $tz_object = new DateTimeZone('America/Bogota');
        $date = new DateTime($list->RecibeHora);
        $date->setTimezone($tz_object);
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'Fotos' => $list->Fotos,
            'NumeroReporte' => $list->NumeroReporte,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Solicitante' => $list->Solicitante,
            'SolicitudId' => NULL,
            'Ubicacion' => $list->Ubicacion,
            'TipoServicio' => $list->TipoServicio,
            'EquipoId' => $list->EquipoId == '' ? NULL : $list->EquipoId,
            'Contador' => $list->Contador,
            'FallaReportada' => $list->FallaReportada,
            'FallaDetectada' => $list->FallaDetectada,
            'ProcedimientoRealizado' => $list->ProcedimientoRealizado,
            'Observaciones' => $list->Observaciones,
            'EstadoFinal' => $list->EstadoFinal,
            'Responsable' => $list->Responsable,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
            'RecibeFecha' => $list->RecibeFecha,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'RecibeId' => $list->RecibeId,
            'ResponsableId' => $list->ResponsableId,
            'TipoReporte' => $list->TipoReporte,
            'ChangeCT' => 1,
            'ReporteArchivo' => $list->ReporteArchivo,
            'CreatedBy' => $list->CreatedBy
        ));

        return $list2;
    }

    public function MAPToArray3($list, $NombreArchivo) {
        $list2 = Array();
        $tz_object = new DateTimeZone('America/Bogota');
        $date = new DateTime($list->RecibeHora);
        $date->setTimezone($tz_object);
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'Fotos' => $list->Fotos,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'SolicitudId' => $list->SolicitudId,
            'TipoServicio' => $list->TipoServicio,
            'EquipoId' => $list->EquipoId,
            'Observaciones' => $list->Observacion,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
            'RecibeFecha' => $list->RecibeFecha,
            'Contador' => $list->Contador,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'RecibeId' => $list->RecibeId,
            'ResponsableId' => $list->ResponsableId,
            'ReporteArchivo' => $NombreArchivo,
            'EstadoFinal' => $list->EstadoFinal,
            'TipoReporte' => $list->TipoReporte,
            'Responsable' => $list->Responsable,
            'TotalRepuesto' => $list->TotalRepuesto,
            'CreatedBy' => $list->CreatedBy,
            'Ciudad' => $list->Ciudad,
            'HoraInicio' => $list->HoraInicio,
            'HoraFinal' => $list->HoraFinal,
            'NivelCombustible' => $list->NivelCombustible,
            'NivelAguaRefrigerante' => $list->NivelAguaRefrigerante,
            'NivelAceite' => $list->NivelAceite,
            'NivelElectrolitoBateria' => $list->NivelElectrolitoBateria,
            'VoltajeBateria' => $list->VoltajeBateria,
            'FechaUltCambioAceite' => $list->FechaUltCambioAceite,
            'FiltroAire' => $list->FiltroAire,
            'Fugas' => $list->Fugas,
        ));

        return $list2;
    }

    public function MAPToArray2($list, $Estado = "Firmado") {
        $list2 = Array();
        array_push($list2, Array(
//            'RecibeFirma' => $list->Firma,
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
        return $datetime->format('Y-m-d h:i:s');
    }

}
