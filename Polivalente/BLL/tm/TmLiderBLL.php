<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmLiderDAL.php';
require_once dirname(__FILE__) . '/TmMaternaBLL.php';

class TmLiderBLL {

    public function GetTmLiders() {
        $Helper = new TmLiderDAL();
        return $Helper->GetTmLiders();
    }
    
    public function GetTmLiderByLiderId($LiderId) {
        $Helper = new TmLiderDAL();
        $Lider = $Helper->GetTmLiderByLiderId($LiderId);
        $Lider = count($Lider) > 0 ? $Lider[0] : new stdClass();
        return $Lider;
    }
    
    public function GetTmLiderByDocumento($Documento) {
        $Helper = new TmLiderDAL();
        return $Helper->GetTmLiderByDocumento($Documento);
    }

    public function GetTmLiderByMunicipioId($MunicipioId) {
        $Helper = new TmLiderDAL();
        $Lider = $Helper->GetTmLiderByMunicipioId($MunicipioId);
        return $Lider;
    }

    public function CreateTmLider($list) {
        $Helper = new TmLiderDAL();
        $Lider = $this->GetTmLiderByDocumento($list->Documento);
        if (count($Lider) == 0) {
            $id = $Helper->CreateLider($this->MAPToArray($list));
            return $id;
        }else{
            return "Ya existe este lider en la base de datos";
        }
    }

    public function UpdateTmLider($list) {
        $Helper = new TmLiderDAL();
//        $r = $Helper->VerificarDia($list->TmLiderId);
//        if (count($r) > 0) {
//            $id = $Helper->UpdateTmLider($this->MAPToUpdate($list), $list->TmLiderId);
//            if ($id) {
//                $drvh = new DetalleTmLiderBLL();
//                $drvh->UpdateDetalleTmLider($list->Pacientes, $list->ModifiedBy);
//            }
//            return $id;
//        } else {
//            return "No se puede editar una Ronda del dia anterior";
//        }
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombres' => $list->Nombres,
            'Documento' => $list->Documento,
            'Telefono' => $list->Telefono,
            'MunicipioId' => $list->MunicipioId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToDetalleLider($list, $LiderId, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'LiderId' => $LiderId,
                'Color' => $list[$index]->Color,
                'TarifaId' => $list[$index]->TarifaId,
                'Precio' => $list[$index]->Precio,
                'CreatedBy' => $CreatedBy
            ));
        }

        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }

}
