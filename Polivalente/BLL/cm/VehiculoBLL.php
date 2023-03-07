<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/VehiculoDAL.php';

class VehiculoBLL {
    
    public function GetALLVehiculos() {
        $Helper = new VehiculoDAL();
        return $Helper->GetALLVehiculos();
    }

    public function GetVehiculos() {
        $Helper = new VehiculoDAL();
        return $Helper->GetVehiculos();
    }

    public function CreateDMByRonda($DMByRonda) {
        $Helper = new VehiculoDAL();
        return $Helper->CreateDMByRonda($this->MAPToCreate($DMByRonda));
    }

    public function MAPToCreate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'MedicamentoId' => $list->MedicamentoId,
            'DispositivoMedicoId' => $list->DispositivoMedicoId,
            'RondaVerificacionId' => $list->RondaVerificacionId,
            'Cantidad' => $list->Cantidad,
            'CreatedBy' => $list->CreatedBy,
        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
