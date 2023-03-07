<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/MantenimientoPreventivoSistemaDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class MantenimientoPreventivoSistemaBLL {

    public function GetAllMantenimientoPreventivoSistemasByServicio($ServicioId, $Year) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        $listado = array();
        $Item = $Helper->GetAllMantenimientoPreventivoSistemasByServicio($ServicioId, $Year);
        if (count($Item) > 0) {
            array_push($listado, $this->LogicaMantPreventivo($Item));
        }
        return $listado;
    }

    public function GetAllMantenimientoPreventivoSistemasByServicio2($ServicioId, $Vigencia) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        $listado = array();
        $Item = $Helper->GetAllMantenimientoPreventivoSistemassByServicioVersion2($ServicioId, $Vigencia);
        if (count($Item) > 0) {
            array_push($listado, $this->LogicaMantPreventivoVersion2($Item));
        }
        return $listado;
    }
    
    public function GetAllMantenimientoPreventivoSistemasBySede_2($SedeId,$ServicioId, $Vigencia, $Mes) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        $listado = array();
        $Item = $Helper->GetAllMantenimientoPreventivoSistemassBySedeVersion2($SedeId, $ServicioId, $Vigencia);
        if (count($Item) > 0) {
            array_push($listado, $Item);
        }
        return $listado;
    }
    
    public function GetAllMantenimientoPreventivoSistemas($UsuarioId) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        $listado = array();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            $Equipos_sede = $Helper->GetAllMantenimientoPreventivoSistemas($value->SedeId);
//            echo print_r($Equipos_sede);
            for ($i = 0; $i < count($Equipos_sede); $i++) {
                array_push($list, $Equipos_sede[$i]);
            }
        }
        array_push($listado, $list);
//        if (count($list) > 0) {
//            array_push($listado, $this->LogicaMantPreventivoVersionLite($list));
//        }
        return $listado;
    }

    public function GetAllListaTabulada($UsuarioId) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        $listado = array();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $list = array();
        foreach ($lsede as $value) {
            $Equipos_sede = $Helper->GetAllMantenimientoPreventivoSistemasTabulador($value->SedeId);
//            echo print_r($Equipos_sede);
            for ($i = 0; $i < count($Equipos_sede); $i++) {
                array_push($list, $Equipos_sede[$i]);
            }
        }
        if (count($list) > 0) {
            array_push($listado, $this->LogicaAlertaListadoEspecial($list));
        }
        return $listado;
    }

    public function GetAllMantenimientoPreventivoSistemasBySedeId($SedeId) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
        return $this->LogicaMantPreventivoVersion2($Helper->GetAllMantenimientoPreventivoSistemas($SedeId));
    }

    public function GetNEquiposByServicio($UsuarioId) {
        $Helper = new MantenimientoPreventivoSistemaDAL();
//        $hu = new SedeBLL();
//        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
//        $sql = "";
//        $cont = 0;
//        foreach ($lsede as $value) {
//            $sql .= "SELECT s.nombre as Servicio, count(s.Nombre) as Cantidad
//            FROM sistemas_hojavida as h
//            inner join servicio as s on h.ServicioId = s.ServicioId where h.SedeId = $value->SedeId group by s.Nombre ";
//            $cont++;
//            if ($cont < count($lsede)) {
//                $sql .= "UNION ";
//            }else{
//                $sql .= "order by Cantidad DESC limit 7 ";
//            }
//        }
        $list = $Helper->GetNEquiposByServicio($UsuarioId);
        return $list;
    }

    private function LogicaAlerta($array) {
//        $FechaHoy = $this->getDatetimeNow();
//        $list = array();
//        foreach ($array as $key => $value) {
//            if ($value->FechaMantenimientoPreventivoSistema2 != NULL) {
//                $FechaSiguiente = $this->EvaluarFecha($value->FechaMantenimientoPreventivoSistema2, $value->Frecuencia);
//                if ($FechaSiguiente <= $FechaHoy) {
//                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
//                    $value->FechaSiguienteMantenimiento = $FechaSiguiente;
//                    $value->Hoy = $FechaHoy;
//                    array_push($list, $value);
//                } else {
//                    $value->EstadoAlerta = "Activo";
////                    unset($array[$key]);
//                }
//            } else if ($value->FechaMantenimientoPreventivoSistema1 != NULL) {
//                $FechaSiguiente = $this->EvaluarFecha($value->FechaMantenimientoPreventivoSistema1, $value->Frecuencia);
//                if ($FechaSiguiente <= $FechaHoy) {
//                    $value->DiferenciaFecha = $this->GetDiferenciaEntreFechas($FechaSiguiente, $FechaHoy);
//                    $value->FechaSiguienteMantenimiento = $FechaSiguiente;
//                    $value->Hoy = $FechaHoy;
//                    array_push($list, $value);
//                } else {
//                    $value->EstadoAlerta = "Activo";
////                    unset($array[$key]);
//                }
//            } else {
////                unset($array[$key]);
//            }
//        }
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $mes_hoy = $datetime->format('m');

        $list = array();
        foreach ($array as $key => $o) {
//            $o->Cronograma = array();
            if ($o->Fecha !== NULL) {
                $date = date_create($o->Fecha);
                $mes_reporte = date_format($date, "m");
                $ano_reporte = date_format($date, "Y");
                $frecuencia = $o->MesInicial;

                for ($index = 1; $index <= 12; $index++) {
                    if ($index >= $frecuencia) {
                        if ((int) $mes_reporte < (int) $frecuencia && (int) $mes_hoy >= (int) $frecuencia) {
                            $o->ProximoMantenimiento = $frecuencia;
                            array_push($list, $o);
                            break;
                        }

                        switch ($o->Frecuencia) {
                            case "TRIMESTRAL":
                                $frecuencia += 3;
                                break;
                            case "CUATRIMESTRAL":
                                $frecuencia += 4;
                                break;
                            case "SEMESTRAL":
                                $frecuencia += 6;
                                break;
                            case "ANUAL":
                                $frecuencia += 12;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $list;
    }

    private function LogicaAlertaListadoEspecial($array) {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $mes_hoy = $datetime->format('m');
        $list = array();
        foreach ($array as $key => $o) {
//            $o->Cronograma = array();
            if ($o->Fecha !== NULL) {
                $date = date_create($o->Fecha);
                $mes_reporte = date_format($date, "m");
                $ano_reporte = date_format($date, "Y");
                $frecuencia = $o->MesInicial;
                for ($index = 1; $index <= 12; $index++) {
                    if ($index >= $frecuencia) {
                        if ((int) $mes_reporte < (int) $frecuencia && (int) $mes_hoy >= (int) $frecuencia) {
                            $o->ProximoMantenimiento = $frecuencia;
//                            array_push($list, $o);
                            break;
                        }
                        switch ($o->Frecuencia) {
                            case "TRIMESTRAL":
                                $frecuencia += 3;
                                break;
                            case "CUATRIMESTRAL":
                                $frecuencia += 4;
                                break;
                            case "SEMESTRAL":
                                $frecuencia += 6;
                                break;
                            case "ANUAL":
                                $frecuencia += 12;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        return $array;
    }

    private function LogicaMantPreventivoVersion2($array, $Mes) {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $mes_hoy = $datetime->format('m');
        $list = array();
        foreach ($array as $o) {
            $o->Cronograma = array();
            $date = NULL;
            $mes_reporte = NULL;
            $ano_reporte = NULL;
            if ($o->Fecha !== NULL) {
                $date = date_create($o->Fecha);
                $mes_reporte = date_format($date, "m");
                $ano_reporte = date_format($date, "Y");
            }
            $frecuencia = $o->MesInicial;
            for ($index = 1; $index <= 12; $index++) {
                if ($index >= $frecuencia) {
                    if ((int) $mes_reporte >= (int) $frecuencia && (int) $mes_hoy >= (int) $frecuencia) {
                        array_push($o->Cronograma, "Verde");
                    } else if ((int) $mes_reporte < (int) $frecuencia && (int) $mes_hoy >= (int) $frecuencia) {
                        array_push($o->Cronograma, "Rojo");
                    } else if ($o->Fecha === NULL) {
                        array_push($o->Cronograma, "Rojo");
                    }  else {
                        array_push($o->Cronograma, "Gris");
                    }

                    switch ($o->Frecuencia) {
                        case "TRIMESTRAL":
                            $frecuencia += 3;
                            break;
                        case "CUATRIMESTRAL":
                            $frecuencia += 4;
                            break;
                        case "SEMESTRAL":
                            $frecuencia += 6;
                            break;
                        case "ANUAL":
                            $frecuencia += 12;
                            break;
                        default:
                            break;
                    }
                } else {
                    array_push($o->Cronograma, "");
                }
            }
            if ($Mes != "Todos") {
                $obj = $o->Cronograma[$Mes];
                $o->Cronograma = Array();
                if ($obj != "") {
                    array_push($o->Cronograma, $obj);
                }
            }
            array_push($list, $o);
        }
        return $list;
    }
    
    private function LogicaMantPreventivoVersionLite($array) {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $mes_hoy = $datetime->format('m');

        $list = array();
        foreach ($array as $key => $o) {
//            $o->Cronograma = array();
            $date = NULL;
            $mes_reporte = NULL;
            $ano_reporte = NULL;
            if ($o->Fecha !== NULL) {
                $date = date_create($o->Fecha);
                $mes_reporte = date_format($date, "m");
                $ano_reporte = date_format($date, "Y");
            }else{
                $date = date_create("2018-01-01");
            }
            
            $frecuencia = $o->MesInicial;
            for ($index = 1; $index <= 12; $index++) {
                if ($index >= $frecuencia) {
                    if ((int) $mes_reporte < (int) $frecuencia && (int) $mes_hoy >= (int) $frecuencia) {
                        $o->ProximoMantenimiento = $frecuencia;
                        array_push($list, $o);
                        break;
                    }

                    switch ($o->Frecuencia) {
                        case "TRIMESTRAL":
                            $frecuencia += 3;
                            break;
                        case "CUATRIMESTRAL":
                            $frecuencia += 4;
                            break;
                        case "SEMESTRAL":
                            $frecuencia += 6;
                            break;
                        case "ANUAL":
                            $frecuencia += 12;
                            break;
                        default:
                            break;
                    }
                }
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
