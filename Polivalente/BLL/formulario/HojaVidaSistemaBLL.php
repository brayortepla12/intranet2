<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/HojaVidaSistemaDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class HojaVidaSistemaBLL {

    public function GetHojasByServicio($ServicioId) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->GetHojaVidaByServicio($ServicioId);
    }

    public function GetHojasByServicioPrint($ServicioId) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->GetHojaVidaByServicioPrint($ServicioId);
    }

    public function GetHojasBySedeId($SedeId, $ServicioId, $Estado) {
        $Helper = new HojaVidaSistemaDAL();
        if ($ServicioId != 'TODO') {
            return $Helper->GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado);
        } else {
            return $Helper->GetHojaVidaBySedeId($SedeId, $Estado);
        }
    }

    public function UpdateContadorHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToUpdateContadorHojaVida($list), $HojaVidaId);
    }

    public function GetHojaVidas() {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->GetHojaVidas();
    }

    public function GetHojasBySerie($Serie) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->GetReporteBySerie($Serie);
    }

    public function GetHojasByHojaVidaId($HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
        $Hojas = json_decode($Helper->GetHojaVidaByHojaVidaId($HojaVidaId));
        $Hojas[0]->Accesorios = $Helper->GetAccesoriosByHojaVidaId($HojaVidaId);
        return json_encode($Hojas);
    }

    public function GetNHojaVida() {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->GetNHojaVida();
    }

    public function CreateHojaVida($list) {
        $Helper = new HojaVidaSistemaDAL();
        $Hoja = $Helper->IsInDB($list->Nombre)[0];
        if ($Hoja->Existencia == 0) {
            $o = $Helper->CreateHojaVida($this->MAPToArray($list));
            if (count($o) > 0) {
                $this->CreateAccesorios($list, $o[0]);
                return $o;
            }
        } else {
            return "Ya existe esta hoja de vida con este nombre en la base de datos";
        }
    }

    public function BajaHojaVida($HojaVida) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida, 'Baja'), $HojaVida->HojaVidaId);
    }
    
    public function MantHojaVida($HojaVida) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida, 'Mantenimiento'), $HojaVida->HojaVidaId);
    }
    
    public function ActHojaVida($HojaVida) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida, 'Activo'), $HojaVida->HojaVidaId);
    }

    public function CreateAccesorios($list, $HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        if (count($list->Accesorios) > 0) {
            $o = $Helper->CreateAccesorios($this->MAPToCreateAccesorios($list->Accesorios, $HojaVidaId, $list->UserId));
            return $o;
        }
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function UpdateHojaVida($list) {
        $Helper = new HojaVidaSistemaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        foreach ($list->Accesorios as $a) {
            if (isset($a->AccesorioId)) {
                $Helper->UpdateAccesorios($this->MAPToUpdateAccesorios($a, $list->ModifiedBy), $a->AccesorioId);
            } else {
                $Helper->CreateAccesorios($this->MAPToOnlyAccesorios($a, $list->HojaVidaId, $list->ModifiedBy));
            }
        }
        return $Helper->UpdateHojaVida($this->MAPToUpdate($list), $list->HojaVidaId);
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }
    
    public function UpdateFechaUltimoMantenimientoHojaVida($HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida([Array(
            "FechaUltimoMantenimiento" => $this->getDatetimeNow()
        )], $HojaVidaId);
    }
    

    public function CountHojaVidas() {
        $Helper = new HojaVidaSistemaDAL();
        return json_encode($Helper->CountHojaVidas());
    }

    public function CountHojaVidas2($UsuarioId) {
        $Helper = new HojaVidaSistemaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountHojaVidasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function CountComputadores($UsuarioId) {
        $Helper = new HojaVidaSistemaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountComputadoresBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function CountImpresoras($UsuarioId) {
        $Helper = new HojaVidaSistemaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountImpresorasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function UpdateFechaHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray2($list), $HojaVidaId);
    }

    public function DeleteHojaVida($HojaVida) {
        $Helper = new HojaVidaSistemaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida), $HojaVida->HojaVidaId);
    }

    public function TrasladoHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaSistemaDAL();
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

    public function MAPToUpdateContadorHojaVida($list) {
        $list2 = Array();
        array_push($list2, Array(
            "Contador" => $list->Contador,
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
            'FrecuenciaMantenimientoId' => $list->FrecuenciaMantenimientoId,
            'FrecuenciaCalibracionId' => $list->FrecuenciaCalibracionId == "NO TIENE" ? NULL : $list->FrecuenciaCalibracionId,
            'Ubicacion' => $list->Ubicacion,
            'Nombre' => $list->Nombre,
            'Fabricante' => $list->Fabricante,
            'Modelo' => $list->Modelo,
            'NSerial' => $list->NSerial,
            'SO' => $list->SO,
            'SerieSO' => $list->SerieSO,
            'FechaInstalacion' => $list->FechaInstalacion,
            'FechaUltimoMantenimiento' => $list->FechaUltimoMantenimiento,
            'FechaCalibracion' => $list->FechaCalibracion,
            'Ram' => $list->Ram,
            'Procesador' => $list->Procesador,
            'IP' => $list->IP,
            'Contador' => $list->Contador,
            'Puerto' => $list->Puerto,
            'DiscoDuro' => $list->DiscoDuro,
            'TipoArticulo' => $list->TipoArticulo,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'Foto' => $list->Foto,
            'LicenciaOffice' => $list->LicenciaOffice,
            'LicenciaWindows' => $list->LicenciaWindows,
            'LicenciaAntivirus' => $list->LicenciaAntivirus,
//                'Accesorios' => json_encode($list->Accesorios),
            'CreatedBy' => $list->UserId
        ));

        return $list2;
    }

    public function MAPToCreateAccesorios($list, $HojaVidaId, $CreatedBy) {
        $list2 = Array();
        foreach ($list as $k) {
            array_push($list2, Array(
                'HojaVidaId' => $HojaVidaId,
                'Nombre' => $k->Nombre,
                'Marca' => $k->Marca,
                'Modelo' => $k->Modelo,
                'NSerial' => $k->NSerial,
                'FechaInstalacion' => $k->FechaInstalacion,
                'Cantidad' => $k->Cantidad,
                'CreatedBy' => $CreatedBy
            ));
        }
        return $list2;
    }

    public function MAPToOnlyAccesorios($k, $HojaVidaId, $CreatedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'HojaVidaId' => $HojaVidaId,
            'Nombre' => $k->Nombre,
            'Marca' => $k->Marca,
            'Modelo' => $k->Modelo,
            'NSerial' => $k->NSerial,
            'FechaInstalacion' => $k->FechaInstalacion,
            'Cantidad' => $k->Cantidad,
            'CreatedBy' => $CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdateAccesorios($list, $ModifiedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombre' => $list->Nombre,
            'Marca' => $list->Marca,
            'Modelo' => $list->Modelo,
            'NSerial' => $list->NSerial,
            'FechaInstalacion' => $list->FechaInstalacion,
            'Cantidad' => $list->Cantidad,
            'Estado' => $list->Estado,
            'ModifiedBy' => $ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'FrecuenciaMantenimientoId' => $list->FrecuenciaMantenimientoId,
            'FrecuenciaCalibracionId' => $list->FrecuenciaCalibracionId == "NO TIENE" ? NULL : $list->FrecuenciaCalibracionId,
            'Ubicacion' => $list->Ubicacion,
            'Nombre' => $list->Nombre,
            'Fabricante' => $list->Fabricante,
            'Modelo' => $list->Modelo,
            'NSerial' => $list->NSerial,
            'SO' => $list->SO,
            'SerieSO' => $list->SerieSO,
            'FechaInstalacion' => $list->FechaInstalacion,
            'FechaUltimoMantenimiento' => $list->FechaUltimoMantenimiento,
            'FechaCalibracion' => $list->FechaCalibracion,
            'Ram' => $list->Ram,
            'IP' => $list->IP,
            'Contador' => $list->Contador,
            'Puerto' => $list->Puerto,
            'Procesador' => $list->Procesador,
            'DiscoDuro' => $list->DiscoDuro,
            'TipoArticulo' => $list->TipoArticulo,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'Foto' => $list->Foto,
            'LicenciaOffice' => $list->LicenciaOffice,
            'LicenciaWindows' => $list->LicenciaWindows,
            'LicenciaAntivirus' => $list->LicenciaAntivirus,
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
