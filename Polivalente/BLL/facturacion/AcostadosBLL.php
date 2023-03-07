<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/facturacion/AcostadosDAL.php';

class AcostadosBLL {

    public function GetAcostados() {
        $Helper = new AcostadosDAL();
        $obj = new stdClass();
        $obj->Acostados = $Helper->GetAcostados();
        $obj->TotalAcostados = $Helper->GetTotalAcostados();
        return $obj;
    }

    private function _data_last_month_day() {
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));

        return date('d/m/Y', mktime(0, 0, 0, $month, $day, $year));
    }

    /** Actual month first day * */
    private function _data_first_month_day() {
        $month = date('m');
        $year = date('Y');
        return date('d/m/Y', mktime(0, 0, 0, $month, 1, $year));
    }

    function nombremes($mes) {
        setlocale(LC_TIME, 'spanish');
        $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
        return $nombre;
    }

}
