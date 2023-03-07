<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/MedicamentoDAL.php';

class MedicamentoBLL {
    
    public function GetMedicamentos() {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentos();
    }
    
    public function GetMedicamentos_Lite($TipoMedicamento) {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentos_Lite($TipoMedicamento);
    }
    
    public function GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId) {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
    }
    
    public function GetMedicamentosByTipoMedicamentoId_Lite($TipoMedicamentoId) {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentosByTipoMedicamentoId_Lite($TipoMedicamentoId);
    }
    
    public function GetMedicamentosByTipoMedicamentoId_Lite_Loteado($TipoMedicamentoId) {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentosByTipoMedicamentoId_Lite_Loteado($TipoMedicamentoId);
    }
    
    public function GetMedicamentoById($MedicamentoId) {
        $Helper = new MedicamentoDAL();
        return $Helper->GetMedicamentoById($MedicamentoId);
    }
    
    public function CreateMedicamento($list, $RondaVerificacionId, $CreatedBy) {
        $Helper = new MedicamentoDAL();
        $id = $Helper->CreateMedicamento($this->MAPToArray($list, $RondaVerificacionId, $CreatedBy));
        return $id;
    }
    
    public function UpdateMedicamento($list, $ModifiedBy) {
        $Helper = new MedicamentoDAL();
        $listado = $this->MAPToUpdate($list, $ModifiedBy);
        foreach ($listado as $l) {
            $Helper->UpdateMedicamento($l, $l["MedicamentoId"]);
        }
        return "ok";
    }
    
    public function UpdatePrecioMedicamento($list, $MedicamentoId) {
        $Helper = new MedicamentoDAL();
        $Helper->UpdateMedicamento($list, $MedicamentoId);
    }

    public function MAPToArray($list, $RondaVerificacionId, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'RondaVerificacionId' => $RondaVerificacionId,
                'PNombre' => $list[$index]->PNombre,
                'SNombre' => $list[$index]->SNombre,
                'PApellido' => $list[$index]->PApellido,
                'SApellido' => $list[$index]->SApellido,
                'IdAfiliado' => $list[$index]->IdAfiliado,
                'NoAdmision' => $list[$index]->NoAdmision,
                'Cafeina' => $list[$index]->Cafeina,
                'Metilprednisolona' => $list[$index]->Metilprednisolona,
                'Metoclopramida' => $list[$index]->Metoclopramida,
                'Furosemida' => $list[$index]->Furosemida,
                'Dexametasona' => $list[$index]->Dexametasona,
                'Ranitidina' => $list[$index]->Ranitidina,
                'Dipirona' => $list[$index]->Dipirona,
                'Amk' => $list[$index]->Amk,
                'Anfo' => $list[$index]->Anfo,
                'Cfz' => $list[$index]->Cfz,
                'Cfp' => $list[$index]->Cfp,
                'Cfx' => $list[$index]->Cfx,
                'Clr' => $list[$index]->Clr,
                'Cld' => $list[$index]->Cld,
                'Gnt' => $list[$index]->Gnt,
                'Mtz' => $list[$index]->Mtz,
                'Van' => $list[$index]->Van,
                'Flz' => $list[$index]->Flz,
                'Col' => $list[$index]->Col,
                'Mrp' => $list[$index]->Mrp,
                'Ppt' => $list[$index]->Ppt,
                'EstadoPaciente' => $list[$index]->EstadoPaciente,
                'CreatedBy' => $CreatedBy
            ));
        }

        return $list2;
    }

    public function MAPToUpdate($list, $ModifiedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'MedicamentoId' => $list[$index]->MedicamentoId,
                'Cafeina' => $list[$index]->Cafeina,
                'Metilprednisolona' => $list[$index]->Metilprednisolona,
                'Metoclopramida' => $list[$index]->Metoclopramida,
                'Furosemida' => $list[$index]->Furosemida,
                'Dexametasona' => $list[$index]->Dexametasona,
                'Ranitidina' => $list[$index]->Ranitidina,
                'Dipirona' => $list[$index]->Dipirona,
                'Amk' => $list[$index]->Amk,
                'Anfo' => $list[$index]->Anfo,
                'Cfz' => $list[$index]->Cfz,
                'Cfp' => $list[$index]->Cfp,
                'Cfx' => $list[$index]->Cfx,
                'Clr' => $list[$index]->Clr,
                'Cld' => $list[$index]->Cld,
                'Gnt' => $list[$index]->Gnt,
                'Mtz' => $list[$index]->Mtz,
                'Van' => $list[$index]->Van,
                'Flz' => $list[$index]->Flz,
                'Col' => $list[$index]->Col,
                'Mrp' => $list[$index]->Mrp,
                'Ppt' => $list[$index]->Ppt,
                'EstadoPaciente' => $list[$index]->EstadoPaciente,
                'ModifiedBy' => $ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow()
            ));
        }
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d h:i:s');
    }

}
