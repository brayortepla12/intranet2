<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/FlujoTrabajoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../seguridad/ModuloBLL.php';
require_once dirname(__FILE__) . '/VerificadorBLL.php';

class FlujoTrabajoBLL {

    public function GetAll() {
        $db = new FlujoTrabajoDAL();
        return $db->getAll();
    }

    public function GetAllByUserId($UserId) {
        $db = new FlujoTrabajoDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetAllByProtocoloId($ProtocoloId) {
        $db = new FlujoTrabajoDAL();
        $vh = new VerificadorBLL();
        $FlujoTrabajo = $db->getAllByProtocoloId($ProtocoloId);
        foreach ($FlujoTrabajo as $f) {
            $f->Verificadores = $vh->getAllByFlujoTrabajoId($f->FlujoTrabajoId);
        }
        return $FlujoTrabajo;
    }

    public function CreateFlujoTrabajo($FlujoTrabajo) {
        $db = new FlujoTrabajoDAL();
        $hv = new VerificadorBLL();
        foreach ($FlujoTrabajo as $value) {
            if (property_exists($value, "FlujoTrabajoId")) {
//            $db->DeleteByProtocoloId($FlujoTrabajo[0]->ProtocoloId);
                $this->UpdateFlujoTrabajo($value);
            } else {
                $id = $db->CreateFlujoTrabajo($this->MAPToArray($value));
                if (count($id) > 0) {

                    $hv->CreateVerificador($value->Verificadores, $id[0]);
                }
            }
        }
        return $FlujoTrabajo;
    }

    public function UpdateFlujoTrabajo($list) {
        $db = new FlujoTrabajoDAL();
        $hv = new VerificadorBLL();
        $o = $db->UpdateFlujoTrabajo($this->MAPToArray2($list), $list->FlujoTrabajoId);
        foreach ($list->Verificadores as $value) {
            if (property_exists($value, "VerificadorId")) {
                $hv->UpdateVerificador($value, $list->ModifiedBy);
            } else {
                $value->CreatedBy = $list->ModifiedBy;
                $hv->CreateVerificador([$value], $list->FlujoTrabajoId);
            }
        }
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'ProtocoloId' => $list->ProtocoloId,
            'Orden' => $list->Orden,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArray2($list) {
        $list2 = Array();

        array_push($list2, Array(
            'ProtocoloId' => $list->ProtocoloId,
            'Orden' => $list->Orden,
            'Estado' => $list->Estado,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'FlujoTrabajoId' => $list[$index]->FlujoTrabajoId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
