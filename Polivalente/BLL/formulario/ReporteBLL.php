<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ReporteDAL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/SolicitudBLL.php';
require_once dirname(__FILE__) . '/HojaVidaBLL.php';
require_once dirname(__FILE__) . '/EstadisticaLogicaBLL.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class ReporteBLL extends EstadisticaLogicaBLL {

    public function GetNReporte() {
        $Helper = new ReporteDAL();
        return $Helper->GetNReporte();
    }

    public function GetReporteByRecibeId($RecibeId) {
        $Helper = new ReporteDAL();
        return $Helper->GetReporteByRecibeId($RecibeId);
    }

    public function GetReporteById($ReporteId) {
        $Helper = new ReporteDAL();
        return $Helper->GetReporteById($ReporteId);
    }

    public function GetReportesPlantasElectricas($UsuarioId) {
        $Helper = new ReporteDAL();
        return $Helper->GetReportesPlantasElectricas($UsuarioId);
    }

    public function GetAllReportesByUsuarioServicio($UsuarioId, $Dia, $Mes, $Year) {
        $Helper = new ReporteDAL();
        return $Helper->GetAllReportesByUsuarioServicio($UsuarioId, $Dia, $Mes, $Year);
    }

    public function GetReporteBySolicitudId($SolicitudId) {
        $Helper = new ReporteDAL();
        return $Helper->GetReporteBySolicitudId($SolicitudId);
    }

    public function GetReporteBySerivicioId($SedeId, $ServicioId, $Year, $Mes) {
        $Helper = new ReporteDAL();
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
        $Helper = new ReporteDAL();
        return $Helper->GetReporteByEquipoId($EquipoId);
    }

    public function GetAllReportes() {
        $Helper = new ReporteDAL();
        return $Helper->GetAllReportes();
    }

    public function GetReportesBetweenFecha($From, $To) {
        $Helper = new ReporteDAL();
        return $Helper->GetReportesBetweenFecha($From, $To);
    }

    public function GetReportesBetweenFechaBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteDAL();
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
        $Helper = new ReporteDAL();
        return $this->GenerateEstadistica($Helper->GetReportesBetweenFechaALL($From, $To));
    }

    public function GetReportesBetweenFechaALLBySede($From, $To, $UsuarioId) {
        $Helper = new ReporteDAL();
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
        $Helper = new ReporteDAL();
        return $Helper->GetEstadisticas($Year, $Month);
    }

    public function CreateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteDAL();
        $id = $Helper->CreateReporte($this->MAPToCreate($list));
        if (empty($id) && !is_array($id)) {
            echo 'insert failed: ';
        } else {
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
//            if ($list->TipoServicio == "INSTALACION" || $list->TipoServicio == "PREVENTIVO" || $list->TipoServicio == "CORRECTIVO") {
//                $Helper = new HojaVidaBLL();
//                $Helper->UpdateFechaHojaVida($list, $list->EquipoId);
//            }
            if ($list->TipoServicio == "TRASLADO") {
                $Helper = new HojaVidaBLL();
                $Helper->TrasladoHojaVida($list, $list->EquipoId);
            }
            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $Email->EnviarEmail_ReporteManual($list->RecibeEmail, $list->RecibeNombre, $list->TipoServicio . " - POLIVALENTE", $this->BuildUrlPersonaId($list->RecibeId, $id[0]), $Empresa);
            return $id;
        }
    }

    public function ReenviarCorreo($ReporteId) {
        $Helper = new ReporteDAL();
        $r = $Helper->GetReporteById($ReporteId)[0];
        if ($r) {
            $Email = new sendMail();
            $Eh = new EmpresaBLL();
            $Empresa = $Eh->GetEmpresa();
            $hu = new UsuarioBLL();
            $Usuario = $hu->GetUsuarioById($r->RecibeUsuarioIntranetId);
            if (!$Usuario) {
                return "No existe usuario para esta persona, por favor actualice la persona que recibe.";
            }
            $mensaje = $Email->EnviarEmail_ReporteManual($Usuario->Email, $r->RecibeNombre, $r->TipoServicio . " - POLIVALENTE", $this->BuildUrlPersonaId($r->RecibeId, $r->ReporteId), $Empresa);
            return [$r->ReporteId];
        } else {
            return "No existe este reporte.";
        }
    }

    public function CreateReporteExterno($list, $NombreArchivo) {
        $Helper = new ReporteDAL();
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
            $Email->EnviarEmail_ReporteManual($list->RecibeEmail, $list->RecibeNombre, $list->TipoServicio . " - POLIVALENTE", $this->BuildUrlPersonaId($list->RecibeId, $id[0]), $Empresa);
            return $id;
        }
    }

    public function UpdateReporte($list, $Estado = "Borrador") {
        $Helper = new ReporteDAL();
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
        $Helper = new ReporteDAL();
        return $Helper->FirmarReporte($this->MAPToDelete($Reporte), $Reporte->ReporteId); // update
    }

    public function AutoFirmarTODO($UsuarioId) {
        $Helper = new ReporteDAL();
        $Reportes = $Helper->GetAllReportesByUsuarioServicio_Autofirmar($UsuarioId);
//        echo print_r($Reportes);
        foreach ($Reportes as $value) {
            if ($value->Estado == 'Borrador') {
                $this->FirmarReporte($value->ReporteId, $value->RecibeEmail);
            }
        }
        return [true];
    }

    public function AutoFirmarTODOByRecibeId($RecibeId) {
        $Helper = new ReporteDAL();
        $Helper->FirmarReporteALL($RecibeId);
        return [true];
    }

    public function FirmarReporte($ReporteId, $RecibeId) {
        $Helper = new ReporteDAL();
        $Reporte = $Helper->GetReporteById($ReporteId);
        if (count($Reporte) > 0) {
            if ($Reporte[0]->RecibeId == $RecibeId) {
                $Helper->FirmarReporte([array(
                    "Estado" => "Firmado"
                )], $ReporteId);
                return [true];
            } else {
                return 'El usuario no coincide con este reporte.';
            }
        } else {
            return 'Error al firmar.';
        }
    }

    private function BuildUrl($RecibeEmail, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($RecibeEmail, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente#/ver_reporte_externo/$Id/$token";
        return $Url;
    }

    private function BuildUrlPersonaId($PersonaId, $Id) {
        $Security = new Security();
        $token = $Security->GenerateToken($PersonaId, "Biomedico_123458", 10, []);
        $Url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/Polivalente#/ver_reporte_externo/$Id/$token";
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
            'FallaReportada' => $list->FallaReportada,
            'FallaDetectada' => $list->FallaDetectada,
            'ProcedimientoRealizado' => $list->ProcedimientoRealizado,
            'MedidasAplicadas' => $list->MedidasAplicadas,
            'TotalRepuesto' => $list->TotalRepuesto,
            'Observaciones' => $list->Observaciones,
            'EstadoFinal' => $list->EstadoFinal,
            'Repuestos' => $list->Repuestos,
            'Responsable' => $list->Responsable,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
            'ResponsableFirma' => $list->ResponsableFirma,
            'Recibefecha' => $list->RecibeFecha,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'TipoReporte' => $list->TipoReporte,
            'ReporteArchivo' => $list->ReporteArchivo,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToCreate($list) {
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
            'SolicitudId' => NULL,
            'Ubicacion' => $list->Ubicacion,
            'TipoServicio' => $list->TipoServicio,
            'EquipoId' => $list->EquipoId == '' ? NULL : $list->EquipoId,
            'FallaReportada' => $list->FallaReportada,
            'FallaDetectada' => $list->FallaDetectada,
            'ProcedimientoRealizado' => $list->ProcedimientoRealizado,
            'MedidasAplicadas' => $list->MedidasAplicadas,
            'TotalRepuesto' => $list->TotalRepuesto,
            'Observaciones' => $list->Observaciones,
            'EstadoFinal' => $list->EstadoFinal,
            'Repuestos' => $list->Repuestos,
            'Responsable' => $list->Responsable,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
            'ChangeCT' => 1, // Nuevos reporte nueva firma de ct_persona
            'ResponsableId' => $list->ResponsableId,
            'Recibefecha' => $list->RecibeFecha,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'RecibeId' => $list->RecibeId,
//            'RecibeFirma' => $list->RecibeFirma,
            'TipoReporte' => $list->TipoReporte,
            'ReporteArchivo' => $list->ReporteArchivo,
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
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'SolicitudId' => $list->SolicitudId,
            'TipoServicio' => $list->TipoServicio,
            'EquipoId' => $list->EquipoId,
            'Observaciones' => $list->Observacion,
            'ResponsableNombre' => $list->ResponsableNombre,
            'ResponsableCargo' => $list->ResponsableCargo,
//            'ResponsableFirma' => $list->ResponsableFirma,
            'ResponsableId' => $list->ResponsableId,
            'Recibefecha' => $list->RecibeFecha,
            'RecibeHora' => $date->format('H:i:s'),
            'RecibeNombre' => $list->RecibeNombre,
            'RecibeCargo' => $list->RecibeCargo,
            'RecibeId' => $list->RecibeId,
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
