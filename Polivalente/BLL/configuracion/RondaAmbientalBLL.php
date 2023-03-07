<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/RondaAmbientalDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/SedeBLL.php';

class RondaAmbientalBLL {

    public function GetAllFormularios() {
        $db = new RondaAmbientalDAL();
        $list = $db->GetAllFormularios();
        foreach ($list as $k) {
            $k->Items = $db->getItemsByFormulario($k->FormatoId);
        }
        return $list;
    }

    public function GetFormatosByServicioId($ServicioId) {
        $db = new RondaAmbientalDAL();
        $list = $db->getAllFormulariosByServicioId($ServicioId);
        foreach ($list as $k) {
            $k->Items = $db->getItemsByFormulario($k->FormatoId);
        }
        return $list;
    }

    public function GetRondaAmbientalByServicioId($ServicioId) {
        $db = new RondaAmbientalDAL();
        $list = $db->getRondaAmbientalByServicioId($ServicioId);
        foreach ($list as $k) {
            $k->Items = $db->getItemsByFormularioCompleto($k->RondaAmbientalId);
        }
        return $list;
    }

    public function CreateRondaAmbiental($Rondas) {
        $db = new RondaAmbientalDAL();
        foreach ($Rondas as $k) {
            $id = $db->CreateRondaAmbiental($this->MAPToArray($k));
            if (count($id) > 0) {
                $db->CreateDetalleRondaAmbiental($this->MapToCreateDetalle($k->Items, $id[0]));
            }
        }
        return $Rondas[0];
    }

    public function UpdateRondaAmbiental($Rondas) {
//        echo print_r($Rondas);
        $db = new RondaAmbientalDAL();
//        return $db->UpdateRondaAmbiental($this->MAPToArray2($list), $id);
        foreach ($Rondas as $k) {
//            echo print_r($k);
            $db->UpdateRondaAmbiental($this->MAPToUpdate($k), $k->RondaAmbientalId);
            foreach ($k->Items as $o) {
                $db->UpdateDetalleRondaAmbiental($this->MapToUpdateDetalle($o), $o->DetalleId);
            }
        }
        return $Rondas;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'startsAt' => $list->startsAt,
            'endsAt' => $list->endsAt,
            'ServicioId' => $list->ServicioId,
            'FormatoId' => $list->FormatoId,
            'Observacion' => $list->Observacion
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Observacion' => $list->Observacion,
            'ModifiedAt' => date("Y-m-d"),
        ));
        return $list2;
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'RondaAmbientalId' => $list[$index]->RondaAmbientalId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

    function MapToCreateDetalle($list, $RondaAmbientalId) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'RondaAmbientalId' => $RondaAmbientalId,
                'ItemFormatoId' => $list[$index]->ItemFormatoId,
                'Po' => $list[$index]->Po,
                'Observacion' => $list[$index]->Observacion,
            ));
        }
        return $list2;
    }

    function MapToUpdateDetalle($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Po' => $list->Po,
                'Observacion' => $list->Observacion,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }

}
