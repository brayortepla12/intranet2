<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/VerificadorDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../seguridad/ModuloBLL.php';
require_once dirname(__FILE__) . '/../configuracion/AnexoBLL.php';

class VerificadorBLL {

    public function GetAll() {
        $db = new VerificadorDAL();
        return $db->getAll();
    }

    public function GetAllByUserId($UserId) {
        $db = new VerificadorDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetUsuarioByFlujoTrabajoId($FlujoTrabajoId) {
        $db = new VerificadorDAL();
        $ah = new AnexoBLL();
        $Verificadores = $db->GetUsuarioByFlujoTrabajoId($FlujoTrabajoId);
        foreach ($Verificadores as $v) {
            $anexos = $ah->GetAllByVerificadorId($v->UsuarioId, $FlujoTrabajoId);
            $v->Anexo = count($anexos) > 0 ? json_decode($anexos[0]->Anexo) : [];
        }
        return $Verificadores;
    }

    public function GetUsuarioByFirstFlujoTrabajo($ProtocoloId) {
        $db = new VerificadorDAL();
        return $db->GetUsuarioByFirstFlujoTrabajo($ProtocoloId);
    }

    public function getAllByFlujoTrabajoId($FlujoTrabajoId) {
        $db = new VerificadorDAL();
        $ah = new AnexoBLL();
        $Verificadores = $db->getAllByFlujoTrabajoId($FlujoTrabajoId);
        
        return $Verificadores;
    }

    public function CreateVerificador($Verificador, $FlujoTrabajoId) {
        $db = new VerificadorDAL();
        $ah = new AnexoBLL();
        foreach ($Verificador as $value) {
            $id = $db->CreateVerificador($this->MAPToArray($value, $FlujoTrabajoId));

            if (count($id) > 0 && isset($value->UsuarioId) && $value->Estado == 'Activo') {
                $Anexo = new stdClass();
                $Anexo->VerificadorId = $value->UsuarioId;
                $Anexo->FlujoTrabajoId = $FlujoTrabajoId;
                if (isset($value->Anexo)) {
                    $Anexo->Anexo = $value->Anexo;
                } else {
                    $Anexo->Anexo = "";
                }
                $Anexo->CreatedBy = $value->CreatedBy;
                $ah->CreateAnexo($Anexo);
            }
        }
        return $Verificador;
    }

    public function UpdateVerificador($list, $ModifiedBy) {
        $db = new VerificadorDAL();
        $ah = new AnexoBLL();
        $db->UpdateVerificador($this->MAPToArray2($list, $ModifiedBy), $list->VerificadorId);
        
        if ($list->UsuarioId && $list->Estado == 'Activo') {
            $Anexo = new stdClass();
            $Anexo->VerificadorId = $list->UsuarioId;
            $Anexo->FlujoTrabajoId = $list->FlujoTrabajoId;
            if (isset($list->Anexo)) {
                $Anexo->Anexo = $list->Anexo;
            } else {
                $Anexo->Anexo = "";
            }
            $ah->CreateAnexo($Anexo, $ModifiedBy);
        }
    }

    public function MAPToArray($list, $FlujoTrabajoId) {
        $list2 = Array();
        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'UsuarioId' => $list->UsuarioId,
            'FlujoTrabajoId' => $FlujoTrabajoId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArray2($list, $ModifiedBy) {
        $list2 = Array();

        array_push($list2, Array(
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'UsuarioId' => $list->UsuarioId,
            'Estado' => $list->Estado,
            'ModifiedBy' => $ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'VerificadorId' => $list[$index]->VerificadorId,
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
