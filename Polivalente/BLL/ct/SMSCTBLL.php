<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/ct/SMSCTDAL.php';

class SMSCTBLL {

    public function GetSMSByMes($Mes, $Year) {
        $Helper = new SMSCTDAL();
        return $Helper->GetSMSByMes($Mes, $Year);
    }

}
