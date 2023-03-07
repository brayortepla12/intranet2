<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/FrecuenciaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class FrecuenciaBLL {
    
    public function GetAll() {
        $db = new FrecuenciaDAL();
        return $db->getAll();
    }
    
    public function CreateFrecuencia($Frecuencia) {
        $db = new FrecuenciaDAL();
        $c = $db->GetFrecuencia($Frecuencia[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateFrecuencia($this->MAPToArray($Frecuencia));
    }
    
    public function UpdateFrecuencia($list, $id) {
        $db = new FrecuenciaDAL();
        return $db->UpdateFrecuencia($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'NumeroMeses' => $list[$index]->NumeroMeses,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'NumeroMeses' => $list[$index]->NumeroMeses,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}
