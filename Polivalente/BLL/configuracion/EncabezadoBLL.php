<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/EncabezadoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class EncabezadoBLL {
    
    public function GetEncabezado() {
        $db = new EncabezadoDAL();
        return $db->GetEncabezado();
    }
    
    public function UpdateEncabezado($list, $id) {
        $db = new EncabezadoDAL();
        return $db->UpdateEncabezado($this->MAPToArray($list), $id);
    }
    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'EncabezadoHojaVida' => $list[$index]->EncabezadoHojaVida,
                'EncabezadoReporte' => $list[$index]->EncabezadoReporte,
                'EncabezadoCronograma' => $list[$index]->EncabezadoCronograma,
                'Elaboro' => $list[$index]->Elaboro,
                'CargoElaboro' => $list[$index]->CargoElaboro,
                'FirmaElaboro' => $list[$index]->FirmaElaboro,
                'Reviso' => $list[$index]->Reviso,
                'CargoReviso' => $list[$index]->CargoReviso,
                'FirmaReviso' => $list[$index]->FirmaReviso,
                'Aprobo' => $list[$index]->Aprobo,
                'CargoAprobo' => $list[$index]->CargoAprobo,
                'FirmaAprobo' => $list[$index]->FirmaAprobo,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}
