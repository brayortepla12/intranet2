<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/facturacion/CensosDAL.php';

class CensosBLL {
    
    public function GetCensoPorPeriodo($anno, $mes) {
        $Helper = new CensosDAL();
        $obj = new stdClass();
        if ($mes == 0) {
            $obj->CensoPorPeriodo = $Helper->CensoPorPeriodo("01/01/$anno", "01/01/" . ($anno + 1));
        }else{
            $obj->CensoPorPeriodo = $Helper->CensoPorPeriodo("01/$mes/$anno", "01/" . ($mes + 1) . "/" . $anno);
        }
        return $obj;
    }

    public function GetCensoPorSector() {
        $Helper = new CensosDAL();
        $obj = new stdClass();
        $obj->CensoPorSector = $Helper->CensoPorSector();
        $obj->TotalCensoPorSector= $Helper->GetTotalCensoPorSector();
        return $obj;
    }
    
    public function GetCensoPorEPS() {
        $Helper = new CensosDAL();
        $obj = new stdClass();
        $obj->CensoPorEPS = $Helper->CensoPorEPS();
        $obj->TotalCensoPorEPS = $Helper->TotalCensoPorEPS();
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
