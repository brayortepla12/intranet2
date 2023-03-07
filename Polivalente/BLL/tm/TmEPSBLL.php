<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmEPSDAL.php';
require_once dirname(__FILE__) . '/TmMaternaBLL.php';

class TmEPSBLL {

    public function GetTmEPSs() {
        $Helper = new TmEPSDAL();
        return $Helper->GetTmEPSs();
    }
    
    
    public function GetTmEPSByEPSId($EPSId) {
        $Helper = new TmEPSDAL();
        $EPS = $Helper->GetTmEPSByEPSId($EPSId);
        $EPS = count($EPS) > 0 ? $EPS[0] : new stdClass();
        return $EPS;
    }

    public function CreateTmEPS($list) {
        $Helper = new TmEPSDAL();
        $id = $Helper->CreateEPS($this->MAPToArray($list));
        return $id;
    }

    public function UpdateTmEPS($list) {
        $Helper = new TmEPSDAL();
//        $r = $Helper->VerificarDia($list->TmEPSId);
//        if (count($r) > 0) {
//            $id = $Helper->UpdateTmEPS($this->MAPToUpdate($list), $list->TmEPSId);
//            if ($id) {
//                $drvh = new DetalleTmEPSBLL();
//                $drvh->UpdateDetalleTmEPS($list->Pacientes, $list->ModifiedBy);
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

    public function MAPToDetalleEPS($list, $EPSId, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'EPSId' => $EPSId,
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
