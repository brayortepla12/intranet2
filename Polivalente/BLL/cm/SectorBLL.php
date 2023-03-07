<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/SectorDAL.php';

class SectorBLL {

    public function GetSectores() {
        $Helper = new SectorDAL();
        return $Helper->GetSectores();
    }

}
