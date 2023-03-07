<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/ambulancia/KristalosDAL.php';

class KristalosBLL {

    public function IsValidAdmision($Admision) {
        $db = new KristalosDAL();
        if ($Admision == "Triage" || $Admision == "0" || preg_match("/^0+((\.0+)|(-0+)*)$/", $Admision)) {
            return FALSE;
        }else if($Admision == "Externo"){
           return TRUE; 
        }
        $obj = $db->GetAdmision($Admision);
        if ($obj != NULL) {
            return TRUE;
        }else{
            return FALSE;
        }
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

}
