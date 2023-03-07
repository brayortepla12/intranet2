<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/ambulancia/ReferenciaDAL.php';
require_once dirname(__FILE__) . '/FacturaBLL.php';
require_once dirname(__FILE__) . '/KristalosBLL.php';
require_once dirname(__FILE__) . '/GenerateExcelBLL.php';

class ReferenciaBLL {

    public function CreateReferencia($list) {
        $db = new ReferenciaDAL();
        $pacientes = array();
        $kh = new KristalosBLL();
        foreach ($list as $value) {
            if (property_exists($value, "FechaInicioTraslado") && property_exists($value, "FechaFinTraslado")) {
                if ($value->FechaInicioTraslado == $value->FechaFinTraslado) {
                    $value->Error = "La fecha inicial no puede ser igual que la fecha final del traslado.";
                    array_push($pacientes, $value);
                } else if ($kh->IsValidAdmision($value->Admision)) {
                    $Referencia = $db->GetReferenciasByObj($value);
//                if ($Referencia == NULL) {
                    if (property_exists($value, "Error")) {
                        unset($value->Error);
                    }
                    $timestamp = strtotime($value->Fecha);
                    $value->Fecha = date("Y-d-m H:i:s", $timestamp);
                    $timestamp = strtotime($value->FechaInicioTraslado);
                    $value->FechaInicioTraslado = date("Y-d-m H:i:s", $timestamp);
                    $timestamp = strtotime($value->FechaFinTraslado);
                    $value->FechaFinTraslado = date("Y-d-m H:i:s", $timestamp);
//                    echo print_r($value->Fecha);
                    $db->CreateReferencia($value);
//                }else{
//                    $value->Error = "Ya se encuentra registrada en la DB.";
//                    array_push($pacientes, $value);
//                }
                } else if ($value->Admision == "Triage" || $value->Admision == "0" || preg_match("/^0+((\.0+)|(-0+)*)$/", $value->Admision)) {
                    $value->Error = "Por Motivos Administrativos. Se ha deshabilitado la opcion TRIAGE.";
                    array_push($pacientes, $value);
                } else {
                    $value->Error = "La admision no se encuentra registrada en kristalos.";
                    array_push($pacientes, $value);
                }
            }else{
                // temporal
                $value->Error = "DEBES ACTUALIZAR A LA VERSION 0.1.96.";
                array_push($pacientes, $value);
            }
        }
        return $pacientes;
//        return "error";
    }
    
    public function GetReferenciaByRango($Year, $Mes) {
        $db = new ReferenciaDAL();
        $array = $db->GetReferenciasBetweenDates($Year, $Mes);
//        $db = new GenerateExcelBLL();
//        $db->Generate($array, $From, $To);
        return $this->utf8ize($array);
    }

    public function GetReferenciaBetween($From, $To) {
        $db = new ReferenciaDAL();
        $array = $db->GetReferenciasBetweenDates($From, $To);
        $db = new GenerateExcelBLL();
        $db->Generate($array, $From, $To);
        return $this->utf8ize($array);
    }
    
    public function GetReferenciaBYMovilBetween($Moviles, $From, $To) {
        $db = new ReferenciaDAL();
        $Array = array();
        for ($index = 0; $index < count($Moviles); $index++) {
            $obj = new stdClass();
            $obj->Movil = $Moviles[$index]->nombre;
            $obj->Datos = $db->GetReferenciasBetweenDatesByMovil($Moviles[$index]->nombre,$From, $To);
            array_push($Array, $obj);
        }
        $db = new GenerateExcelBLL();
        $db->GenerateExcelPresidencia($Array, $From, $To);
        return $Array;
    }

    public function GetReferenciaBetweenHours($From, $To, $HoraInicial, $HoraFinal) {
        $db = new ReferenciaDAL();
        $Auxiliareslst = $db->GetReferenciasBetweenAuxiliar($From, $To, $HoraInicial, $HoraFinal);
        $Conductoreslst = $db->GetReferenciasBetweenConductor($From, $To, $HoraInicial, $HoraFinal);

        $db = new GenerateExcelBLL();
        $db->GenerateExcelEspecifico($Auxiliareslst, $Conductoreslst, $From, $To);
        return $this->utf8ize($Auxiliareslst);
    }

    // <editor-fold defaultstate="collapsed" desc="Historicos">
    public function GetHistorico($Auxiliar) {
        $db = new ReferenciaDAL();
        $array = $db->GetHistorico($Auxiliar);
        return $this->utf8ize($array);
    }

    public function GetHistoricoConductor($Conductor) {
        $db = new ReferenciaDAL();
        $array = $db->GetHistoricoConductor($Conductor);
        return $this->utf8ize($array);
    }

    public function GetHistoricoAdministrativo() {
        $db = new ReferenciaDAL();
        $array = $db->GetHistoricoAdministrativo();
        return $this->utf8ize($array);
    }

// </editor-fold>


    public function GetCvsVByMonthAdministrativo($Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetCvsVByMonthAdministrativo($Mes, $anno);
        return $this->utf8ize($array);
    }

    public function GetCvsVByMonthAdministrativoAux($Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetCvsVByMonthAdministrativoAux($Mes, $anno);
        return $this->utf8ize($array);
    }

    public function GetCvsVByDayAdministrativo($Day, $Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetCvsVByDayAdministrativo($Day, $Mes, $anno);
        return $this->utf8ize($array);
    }

    public function GetCvsVByDayAdministrativoAux($Day, $Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetCvsVByDayAdministrativoAux($Day, $Mes, $anno);
        return $this->utf8ize($array);
    }

    public function GetTotalFactura($Day = NULL, $Mes, $Anno) {
        $db = new ReferenciaDAL();
        $array = array();
        if ($Day != "null") {
            $array = $this->utf8ize($db->GetReferenciasByDay($Day, $Mes, $Anno));
        } else {
            $array = $this->utf8ize($db->GetReferenciasByMonth($Mes, $Anno));
        }
        $db = new FacturaBLL();
        return $db->GetTotalFacturado($array);
//        return $array;
    }
    
    public function GetTotalFacturaInYear($Anno) {
        $db = new ReferenciaDAL();
        $array = array();
        $array = $this->utf8ize($db->GetReferenciasByYear($Anno));
        $db = new FacturaBLL();
        return $db->GetTotalFacturado($array);
//        return $array;
    }
    
    

    public function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    // <editor-fold defaultstate="collapsed" desc="Resumenes">
    public function GetResumenByMonth($Mes, $anno, $nombre, $tipousuario) {
        $db = new ReferenciaDAL();
        $array = $db->GetResumenByMonth($Mes, $anno, $nombre, $tipousuario);
        return $this->utf8ize($array);
    }

    public function GetResumenByDiaAdministrativo($Dia, $Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetResumenByDiaAdministrativo($Dia, $Mes, $anno);
        return $this->utf8ize($array);
    }

    public function GetResumenByMonthAdministrativo($Mes, $anno) {
        $db = new ReferenciaDAL();
        $array = $db->GetResumenByMonthAdministrativo($Mes, $anno);
        return $this->utf8ize($array);
    }

// </editor-fold>
}
