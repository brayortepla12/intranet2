<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/bs/STDAL.php';

class STBLL {

    private $objPHPExcel;
    
    
    public function GetSTs($Anterior, $Actual) {
        $Helper = new STDAL();
        $STs = new stdClass();
        $STs->Sensor0 = $Helper->GetSTs($Actual, $Anterior, 0);
        $STs->Sensor1 = $Helper->GetSTs($Actual, $Anterior, 1);
        $STs->Sensor2 = $Helper->GetSTs($Actual, $Anterior, 2);
        return $STs;
    }

    public function CrearST($temperatura, $NSensor) {
        $Helper = new STDAL();
        $id = $Helper->CreateST($this->MAPToCreate($temperatura, $NSensor));
        if(count($id)>0){
            return $id;
        }
        return false;
    }
    
    public function MAPToCreate($temperatura, $NSensor) {
        $list2 = Array();
        array_push($list2, Array(
            'Temperatura' => $temperatura, 
            'NSensor' => $NSensor,            
            'Fecha' => $this->getDatetimeNow(),

        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }

}
