<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/facturacion/FacturadoEPSMesDAL.php';

class FacturadoEPSMesBLL {

    public function GetFacturadoEPSMes($Mes, $Ano) {
        $Helper = new FacturadoEPSMesDAL();
        $obj = new stdClass();
        $obj->FacturadoEPSMes = $Helper->GetFacturadoEPSMes($this->_data_first_month_day($Mes, $Ano), $this->_data_last_month_day($Mes, $Ano));
        return $obj;
    }

    private function _data_last_month_day($Mes, $Ano) {
        $month = $Mes;
        $year = $Ano;
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));

        return date('d/m/Y', mktime(0, 0, 0, $month, $day, $year));
    }

    /** Actual month first day * */
    private function _data_first_month_day($Mes, $Ano) {
        $month = $Mes;
        $year = $Ano;
        return date('d/m/Y', mktime(0, 0, 0, $month, 1, $year));
    }

    function nombremes($mes) {
        setlocale(LC_TIME, 'spanish');
        $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
        return $nombre;
    }

}
