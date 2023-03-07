<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/facturacion/MesActualDAL.php';
require_once dirname(__FILE__) . '/../../DAL/facturacion/CensosDAL.php';

class MesActualBLL {

    public function GetMesActual($Mes, $Ano) {
        $Helper = new MesActualDAL();
        $hc = new CensosDAL();
        $obj = new stdClass();
        $Helper->Prepare($this->_data_first_month_day($Mes, $Ano), $this->_data_last_month_day($Mes, $Ano));
        $obj->MesActual = $Helper->GetMesActual($this->nombremes($Mes));
        $obj->TotalMesActual = $Helper->GetTotalMesActual($this->nombremes($Mes));
        $obj->MesRefacturado = $Helper->GetMesRefactura($this->nombremes($Mes));
        $obj->TotalMesRefacturado = $Helper->GetTotalMesRefactura($this->nombremes($Mes));
        $obj->SumMesActualRefac = $Helper->GetSumMesActual_Refactura();
        $obj->PendientePorFac = $Helper->GetPendientePorFacturar($this->nombremes($Mes), $Mes, $Ano);
        $obj->SumPendientePorFac = $Helper->GetSumPendientePorFacturar($Mes, $Ano);
        $obj->TotalCensoPorEPS = $hc->TotalCensoPorEPS();
        $Helper->Finish();
        return $obj;
    }
    
    public function GetTotalFacturadoMES($Mes, $Ano) {
        $Helper = new MesActualDAL();
        $obj = new stdClass();
        $Helper->Prepare($this->_data_first_month_day($Mes, $Ano), $this->_data_last_month_day($Mes, $Ano));
        $SumMesActualRefac = $Helper->GetSumMesActual_Refactura();
        $SumPendientePorFac = $Helper->GetSumPendientePorFacturar($Mes, $Ano);
        $Helper->Finish();
        $obj->SumMesActualRefac = $SumMesActualRefac[0]->Valor;
        $obj->SumPendientePorFac = $SumPendientePorFac[0]->Valor;
        $SumMesActualRefac[0]->Valor = str_replace(",","", $SumMesActualRefac[0]->Valor);
        $SumPendientePorFac[0]->Valor = str_replace(",","", $SumPendientePorFac[0]->Valor);
        $fmt = new NumberFormatter( 'es_CO', NumberFormatter::CURRENCY );
        $obj->TotalFacturadoMes = preg_replace( '/[^0-9,"."]/', '', $fmt->formatCurrency(($SumMesActualRefac[0]->Valor + $SumPendientePorFac[0]->Valor), "COP"));
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
