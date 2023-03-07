<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/ambulancia/KristalosDAL.php';

//require_once dirname(__FILE__) . '\ReferenciaBLL.php';



class FacturaBLL {

// 0100183092
//    public function GetTotalFacturadoRango($From, $To) {
//        $db = new ReferenciaBLL();
//        $array = $db->GetReferenciaBetween($From, $To);
//        
//        $db = new KristalosDAL();
//        $Lista = array();
//        foreach ($array as $value) {
//            if ($value["Admision"] != NULL) {
//                array_push($Lista, $db->GetTotalFacturado($value["Admision"]));
//            }
//        }
//        return $Lista;
////        return $this->utf8ize($array);
//        
//    }
    public function GetTotalFacturado($Admisiones) {
        $db = new KristalosDAL();
        $Lista = array();
        if ($Admisiones != NULL && $Admisiones != "null" && $Admisiones != "Triage") {
            foreach ($Admisiones as $value) {
                if ($value["Admision"] != NULL && $value["Variable"] != 'INTERNO' && $value["Admision"] != 'TRIAGE') {
                    $obj = $db->GetTotalFacturado($value["Admision"]);
                    if ($obj != NULL) {
//                        $obj[0]['Variable'] = $value["Variable"];
                        $obj->Variable = $value["Variable"];
                        array_push($Lista, $obj);
                    }
                }
            }
        }
        return $Lista;
//        return $this->utf8ize($array);
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
