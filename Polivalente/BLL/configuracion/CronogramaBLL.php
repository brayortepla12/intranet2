<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CronogramaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class CronogramaBLL {
    
    public function GetAll() {
        $db = new CronogramaDAL();
        return $db->getAll();
    }
    
    public function CreateCronograma($Cronograma) {
        $db = new CronogramaDAL();
//        $c = $db->GetCronograma($Cronograma[0]->Nombre);
//        if ($c) {
//            return 'Este servicio ya se encuentra registrado en la base de datos';
//        }}
        foreach ($Cronograma[0]->HojaVidaId as $value) {
            $db->CreateCronograma($this->MAPToArray($Cronograma, $value));
        }
        return $Cronograma;
    }
    public function DeleteCronograma($CronogramaId) {
        $db = new CronogramaDAL();
        return $db->DeleteCronograma($CronogramaId);
    }
    public function UpdateCronograma($list, $id) {
        $db = new CronogramaDAL();
        return $db->UpdateCronograma($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list, $HojaVidaId) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Inicio' => $list[$index]->Inicio,
                'SedeId' => $list[$index]->SedeId,
                'ServicioId' => $list[$index]->ServicioId,
                'HojaVidaId' => $HojaVidaId,
                'FrecuenciaMantenimientoId' => $list[$index]->FrecuenciaMantenimientoId,
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
                'Inicio' => $list[$index]->Inicio,
                'SedeId' => $list[$index]->SedeId,
                'ServicioId' => $list[$index]->ServicioId,
                'HojaVidaId' => $list[$index]->HojaVidaId,
                'FrecuenciaMantenimientoId' => $list[$index]->FrecuenciaMantenimientoId,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}
