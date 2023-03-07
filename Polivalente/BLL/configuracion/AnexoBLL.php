<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/AnexoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../seguridad/ModuloBLL.php';
class AnexoBLL {
    
    public function GetAll() {
        $db = new AnexoDAL();
        return $db->getAll();
    }
    public function GetAllByVerificadorId($VerificadorId, $FlujoTrabajoId) {
        $db = new AnexoDAL();
        return $db->GetAllByVerificadorId($VerificadorId, $FlujoTrabajoId);
    }
    
    public function CreateAnexo($Anexo, $ModifiedBy = "") {
        $db = new AnexoDAL();
        $a = $this->GetAllByVerificadorId($Anexo->VerificadorId, $Anexo->FlujoTrabajoId);
        if (count($a) > 0) {
            $Anexo->AnexoId = $a[0]->AnexoId;
            $db->UpdateAnexo($this->MAPToArray2($Anexo, $ModifiedBy),  $Anexo->AnexoId);
        }else{
            $db->CreateAnexo($this->MAPToArray($Anexo, $ModifiedBy));
        }
        return $Anexo;
    }
    
    public function UpdateAnexo($list, $ModifiedBy) {
        $db = new AnexoDAL();
        return $db->UpdateAnexo($this->MAPToArray2($list, $ModifiedBy),  $list->AnexoId);
    }

    public function MAPToArray($list, $ModifiedBy) {
        $list2 = Array();
            array_push($list2, Array(
                'VerificadorId' => $list->VerificadorId,
                'Anexo' => $list->Anexo,
                'FlujoTrabajoId' => $list->FlujoTrabajoId,
                'CreatedBy' => $ModifiedBy
            ));
        return $list2;
    }
    public function MAPToArray2($list, $ModifiedBy) {
        $list2 = Array();
            
            array_push($list2, Array(
                'VerificadorId' => $list->VerificadorId,
                'Anexo' => $list->Anexo,
                'FlujoTrabajoId' => $list->FlujoTrabajoId,
                'ModifiedBy' => $ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        return $list2;
    }
    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'AnexoId' => $list[$index]->AnexoId,
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
