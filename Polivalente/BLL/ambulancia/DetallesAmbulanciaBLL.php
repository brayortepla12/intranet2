<?php

require_once dirname(__FILE__) . '/../../DAL/ambulancia/DetallesAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/HojaVidaAmbulanciaBLL.php';

class DetallesAmbulanciaBLL {

    public function GetAll() {
        $Helper = new DetallesAmbulanciaDAL();
        return $Helper->GetAll();
    }
    
    public function GetDetallesByReporteId($ReporteAmbulanciaId) {
        $Helper = new DetallesAmbulanciaDAL();
        return $Helper->GetDetallesByReporteId($ReporteAmbulanciaId);
    }

    public function CreateDetalleReporte($list, $ReporteId) {
        $Helper = new DetallesAmbulanciaDAL();
        return $Helper->CreateDetalleReporte($this->MAPToArray($list, $ReporteId));
    }
    
    public function DeleteDetalleReporteByReporteId($ReporteId) {
        $Helper = new DetallesAmbulanciaDAL();
        return $Helper->DeleteDetalleReporteByReporteId($ReporteId);
    }

    public function MAPToArray($list, $ReporteId) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'DetalleId' => $list[$index]->DetalleId,
                'ReporteId' => $ReporteId,
            ));
        }
//        echo print_r($list2);

        return $list2;
    }

}
