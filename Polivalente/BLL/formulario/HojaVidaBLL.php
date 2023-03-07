<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/HojaVidaDAL.php';
require_once dirname(__FILE__) . '/../../DAL/ambulancia/HojaVidaAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/../../DAL/Biomedicos/HojaVidaBiomedicoDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class HojaVidaBLL {

    public function GetHojasByServicio($ServicioId) {
        $Helper = new HojaVidaDAL();
        return $Helper->GetHojaVidaByServicio($ServicioId);
    }
    public function GetHojasByServicioWithTA($ServicioId, $TA) {
        $Helper = new HojaVidaDAL();
        $TA2 = strtolower($TA);
        if($TA2 == "polivalente" || $TA2 == "pol"){
            return $Helper->GetHojaVidaByServicioQR($ServicioId);
        } else if ($TA2 == "sistemas"){
            $Helper = new HojaVidaSistemaDAL();
            return $Helper->GetHojaVidaByServicioQR($ServicioId);
        } else if ($TA2 == "ambulancia"){
            $Helper = new HojaVidaAmbulanciaDAL();
            return $Helper->GetHojaVidaByServicio($ServicioId);
        } else if ($TA2 == "biomedicos"){
            $Helper = new HojaVidaBiomedicoDAL();
            return $Helper->GetHojaVidaByServicioBIOQR($ServicioId);
        }
    }

    public function GetHojasByServicioPrint($ServicioId) {
        $Helper = new HojaVidaDAL();
        return $Helper->GetHojaVidaByServicioPrint($ServicioId);
    }

    public function GetHojasBySedeId($SedeId, $ServicioId, $Estado) {
        $Helper = new HojaVidaDAL();
        if ($ServicioId != 'TODO') {
            return $Helper->GetHojaVidaBySedeId_Servicio($SedeId, $ServicioId, $Estado);
        } else {
            return $Helper->GetHojaVidaBySedeId($SedeId, $Estado);
        }
    }

    public function GetALLHojasVida() {
        $Helper = new HojaVidaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAll());
        $list = array();
        foreach ($lsede as $value) {
            $obj = new stdClass();
            $obj->Sede = $value->Nombre;
            $obj->Equipos = $Helper->GetHojaVidaBySedeIdFULL($value->SedeId);
            if (count($obj->Equipos) > 0) {
                array_push($list, $obj);
            }
        }
        return $list;
    }

    public function GetHojaVidas() {
        $Helper = new HojaVidaDAL();
        return $Helper->GetHojaVidas();
    }

    public function GetHojasBySerie($Serie) {
        $Helper = new HojaVidaDAL();
        return $Helper->GetReporteBySerie($Serie);
    }

    public function GetHojasByHojaVidaId($HojaVidaId) {
        $Helper = new HojaVidaDAL();
        return $Helper->GetHojaVidaByHojaVidaId($HojaVidaId);
    }

    public function GetNHojaVida() {
        $Helper = new HojaVidaDAL();
        return $Helper->GetNHojaVida();
    }

    public function CreateHojaVida($list) {
        $Helper = new HojaVidaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        return $Helper->CreateHojaVida($this->MAPToArray($list));
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function UpdateHojaVida($list) {
        $Helper = new HojaVidaDAL();
//        $Hoja = $this->GetHojasBySerie($list->Serie);
//        if ($list->Serie == "N/A" or $Hoja == NULL) {
        return $Helper->UpdateHojaVida($this->MAPToUpdate($list), $list->HojaVidaId);
//        }
//        return "Ya existe esta hoja de vida en la base de datos";
    }

    public function CountHojaVidas() {
        $Helper = new HojaVidaDAL();
        return json_encode($Helper->CountHojaVidas());
    }

    public function CountHojaVidas2($UsuarioId) {
        $Helper = new HojaVidaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountHojaVidasBySede($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function UpdateFechaHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray2($list), $HojaVidaId);
    }

    public function DeleteHojaVida($HojaVida) {
        $Helper = new HojaVidaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida), $HojaVida->HojaVidaId);
    }

    public function BajaHojaVida($HojaVida) {
        $Helper = new HojaVidaDAL();
        return $Helper->UpdateHojaVida($this->MAPToArray4($HojaVida, 'Baja'), $HojaVida->HojaVidaId);
    }

    public function TrasladoHojaVida($list, $HojaVidaId) {
        $Helper = new HojaVidaDAL();
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
            'FrecuenciaMantenimientoId' => $list->FrecuenciaMantenimientoId,
            'FrecuenciaCalibracionId' => $list->FrecuenciaCalibracionId == "NO TIENE" ? NULL : $list->FrecuenciaCalibracionId,
            'Ubicacion' => $list->Ubicacion,
            'Equipo' => $list->Equipo,
            'Marca' => $list->Marca,
            'Modelo' => $list->Modelo,
            'Serie' => $list->Serie,
            'FechaInstalacion' => $list->FechaInstalacion,
            'FechaCalibracion' => $list->FechaCalibracion,
            'Inventario' => $list->Inventario,
            'RegSanitario' => $list->RegSanitario,
            'FechaAdquisicion' => $list->FechaAdquisicion,
            'Garantia' => $list->Garantia,
            'ProveedorId' => $list->ProveedorId,
            'TipoRiesgo' => $list->TipoRiesgo == "NO TIENE" ? NULL : $list->TipoRiesgo,
            'Voltaje' => $list->Voltaje,
            'Amperaje' => $list->Amperaje,
            'Frecuencia' => $list->Frecuencia,
            'Potencia' => $list->Potencia,
            'Presion' => $list->Presion,
            'Peso' => $list->Peso,
            'Temperatura' => $list->Temperatura,
            'Capacidad' => $list->Capacidad,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'Foto' => $list->Foto,
            'Accesorios' => json_encode($list->Accesorios),
            'CreatedBy' => $list->UserId
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
            'Equipo' => $list->Equipo,
            'Marca' => $list->Marca,
            'Modelo' => $list->Modelo,
            'Serie' => $list->Serie,
            'FechaInstalacion' => $list->FechaInstalacion,
            'FechaCalibracion' => $list->FechaCalibracion,
            'Inventario' => $list->Inventario,
            'RegSanitario' => $list->RegSanitario,
            'FechaAdquisicion' => $list->FechaAdquisicion,
            'Garantia' => $list->Garantia,
            'ProveedorId' => $list->ProveedorId,
            'TipoRiesgo' => $list->TipoRiesgo == "NO TIENE" ? NULL : $list->TipoRiesgo,
            'Voltaje' => $list->Voltaje,
            'Amperaje' => $list->Amperaje,
            'Frecuencia' => $list->Frecuencia,
            'Potencia' => $list->Potencia,
            'Presion' => $list->Presion,
            'Peso' => $list->Peso,
            'Temperatura' => $list->Temperatura,
            'Capacidad' => $list->Capacidad,
            'RecomendacioneFabricante' => $list->RecomendacioneFabricante,
            'Foto' => $list->Foto,
            'Accesorios' => json_encode($list->Accesorios),
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
