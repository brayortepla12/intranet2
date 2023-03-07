<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CamaKristalosDAL.php';
require_once dirname(__FILE__) . '/CunaBLL.php';

class CamaKristalosBLL {
    public function GetCamas() {
        $db = new CamaKristalosDAL();
        return $db->GetCamas();
    }
    
    public function GetCunaByAdmision($Admision) {
        $db = new CamaKristalosDAL();
        $c = $db->IsValidAdmision($Admision);
        if ($c != NULL) {
            $db = new CunaBLL();
            return $db->GetAllByNombre($c->HABCAMA);
        }else{
            return "Numero de Admision no valido.";
        }
    }
}
