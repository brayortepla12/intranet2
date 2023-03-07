<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/CalibracionDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class CalibracionBLL {

    public function GetAllCalibracionesByServicio($Servicios, $Year) {
        $Helper = new CalibracionDAL();
        $listado = array();
        foreach ($Servicios as $key => $value) {
            $Item = $Helper->GetAllCalibracionesByServicio($value->ServicioId, $Year);
            if (count($Item) > 0) {
                array_push($listado, $this->LogicaAlertaEspecial($Item));
            }
        }
        return json_encode($listado);
    }
    
    public function GetAllCalibracionesBySede($SedeId) {
        $Helper = new CalibracionDAL();
        return $this->LogicaAlerta($Helper->GetAllCalibraciones($SedeId));
    }

    public function GetAllCalibraciones($UsuarioId) {
        $Helper = new CalibracionDAL();
        $listado = array();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetAllCalibraciones($value->SedeId) as $value2) {
                array_push($list, $value2);
            }
        }
        if (count($list) > 0) {
            array_push($listado, $this->LogicaAlerta($list));
        }
        return $listado;
    }

    private function LogicaAlerta($array) {
        $FechaHoy = $this->getDatetimeNow();
        $list = array();
        foreach ($array as $key => $value) {
            if ($value->FechaCalibracion2 != NULL) {
                $FechaSiguiente = $this->EvaluarFecha($value->FechaCalibracion2, $value->Frecuencia);
                if ($FechaSiguiente <= $FechaHoy) {
                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
                    $value->FechaSiguienteCalibracion = $FechaSiguiente;
                    $value->Hoy = $FechaHoy;
                    array_push($list, $value);
                } else {
                    $value->EstadoAlerta = "Activo";
                }
            } else if ($value->FechaCalibracion1 != NULL) {
                $FechaSiguiente = $this->EvaluarFecha($value->FechaCalibracion1, $value->Frecuencia);
                if ($FechaSiguiente <= $FechaHoy) {
                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
                    $value->FechaSiguienteCalibracion = $FechaSiguiente;
                    $value->Hoy = $FechaHoy;
                    array_push($list, $value);
                } else {
                    $value->EstadoAlerta = "Activo";
                }
            } 
        }
        return $list;
    }
    
    private function LogicaAlertaEspecial($array) {
        $FechaHoy = $this->getDatetimeNow();
        $list = array();
        foreach ($array as $key => $value) {
            if ($value->FechaCalibracion1 != NULL) {
                $FechaSiguiente = $this->EvaluarFecha($value->FechaCalibracion1, $value->Frecuencia);
//                if ($FechaSiguiente <= $FechaHoy) {
                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
                    $value->FechaSiguienteCalibracion = $FechaSiguiente;
                    $value->Hoy = $FechaHoy;
                    array_push($list, $value);
//                } else {
//                    $value->EstadoAlerta = "Activo";
//                }
            } 
        }
        return $list;
    }

    private function EvaluarFecha($Fecha, $Frecuencia) {
        switch ($Frecuencia) {
            case "TRIMESTRAL":
                $Fecha = date('Y-m-d', strtotime("+3 months", strtotime($Fecha)));
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
