<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/MantenimientoPreventivoDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class MantenimientoPreventivoBLL {

    public function GetAllMantenimientoPreventivosByServicio($Servicios, $Year) {
        $Helper = new MantenimientoPreventivoDAL();
        $listado = array();
        foreach ($Servicios as $value) {
            $Item = $Helper->GetAllMantenimientoPreventivosByServicio($value->ServicioId, $Year);
            if (count($Item) > 0) {
                array_push($listado, $this->LogicaMantPreventivo($Item));
            }
        }
        return $listado;
    }

    public function GetAllMantenimientoPreventivos($UsuarioId) {
        $Helper = new MantenimientoPreventivoDAL();
        $listado = array();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetAllMantenimientoPreventivos($value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        if (count($list) > 0) {
            array_push($listado, $this->LogicaAlerta($list));
        }
        return $listado;
    }

    public function GetAllMantenimientoPreventivosBySedeId($SedeId) {
        $Helper = new MantenimientoPreventivoDAL();
        return $this->LogicaAlerta($Helper->GetAllMantenimientoPreventivos($SedeId));
    }

    public function GetNEquiposByServicio($UsuarioId) {
        $Helper = new MantenimientoPreventivoDAL();
        $hu = new SedeBLL();
//        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
//        $list = array();
//        $sql = "";
//        $cont = 0;
//        foreach ($lsede as $value) {
//            $sql .= "SELECT s.nombre as Servicio, count(s.Nombre) as Cantidad
//            FROM hojavida as h
//            inner join servicio as s on h.ServicioId = s.ServicioId where h.SedeId = $value->SedeId group by s.Nombre ";
//            $cont++;
//            if ($cont < count($lsede)) {
//                $sql .= "UNION ";
//            }else{
//                $sql .= "order by Cantidad DESC limit 7 ";
//            }
//            
//        }
        $list = $Helper->GetNEquiposByServicio($UsuarioId);
        return $list;
    }

    private function LogicaAlerta($array) {
        $FechaHoy = $this->getDatetimeNow();
        $list = array();
        foreach ($array as $key => $value) {
            if ($value->FechaMantenimientoPreventivo2 != NULL) {
                $FechaSiguiente = $this->EvaluarFecha($value->FechaMantenimientoPreventivo2, $value->Frecuencia);
                if ($FechaSiguiente <= $FechaHoy) {
                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
                    $value->FechaSiguienteMantenimiento = $FechaSiguiente;
                    $value->Hoy = $FechaHoy;
                    array_push($list, $value);
                } else {
                    $value->EstadoAlerta = "Activo";
//                    unset($array[$key]);
                }
            } else if ($value->FechaMantenimientoPreventivo1 != NULL) {
                $FechaSiguiente = $this->EvaluarFecha($value->FechaMantenimientoPreventivo1, $value->Frecuencia);
                if ($FechaSiguiente <= $FechaHoy) {
                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
                    $value->FechaSiguienteMantenimiento = $FechaSiguiente;
                    $value->Hoy = $FechaHoy;
                    array_push($list, $value);
                } else {
                    $value->EstadoAlerta = "Activo";
//                    unset($array[$key]);
                }
            } else {
//                unset($array[$key]);
            }
        }
        return $list;
    }

    private function LogicaMantPreventivo($array) {
        $FechaHoy = $this->getDatetimeNow();
        $list = array();
        foreach ($array as $key => $value) {
            $FechaSiguiente = $this->EvaluarFecha($value->FechaMantenimientoPreventivo1, $value->Frecuencia);
            $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
            $value->FechaSiguienteMantenimiento = $FechaSiguiente;
            $value->Hoy = $FechaHoy;
            array_push($list, $value);
        }
        return $list;
    }

    private function EvaluarFecha($Fecha, $Frecuencia) {
        switch ($Frecuencia) {
            case "TRIMESTRAL":
                $Fecha = date('Y-m-d', strtotime("+3 months", strtotime($Fecha)));
                break;
            case "BIMESTRAL":
                $Fecha = date('Y-m-d', strtotime("+2 months", strtotime($Fecha)));
                break;
            case "MENSUAL":
                $Fecha = date('Y-m-d', strtotime("+1 months", strtotime($Fecha)));
                break;
            case "DIARIO":
                $Fecha = date('Y-m-d', strtotime("+1 days", strtotime($Fecha)));
                break;
            case "SEMANAL":
                $Fecha = date('Y-m-d', strtotime("+1 months", strtotime($Fecha)));
                break;
            case "CUATRIMESTRAL":
                $Fecha = date('Y-m-d', strtotime("+4 months", strtotime($Fecha)));
                break;
            case "SEMESTRAL":
                $Fecha = date('Y-m-d', strtotime("+6 months", strtotime($Fecha)));
                break;
            case "ANUAL":
                $Fecha = date('Y-m-d', strtotime("+12 months", strtotime($Fecha)));
                break;
            default:
                break;
        }
        return $Fecha;
    }

    private function GetDiferenciaEntreFechas($f1, $f2) {
        $date1 = new DateTime($f1);
        $date2 = new DateTime($f2);
        return $date2->diff($date1)->format("%a");
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
