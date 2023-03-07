<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmMaternaDAL.php';

class TmMaternaBLL {

    public function GetTmMaternaByDocumento($Documento) {
        $Helper = new TmMaternaDAL();
        return $Helper->GetTmMaternaByDocumento($Documento);
    }
    
    public function GetMaternaRegistradasByMes($Year, $Mes, $MunicipioId) {
        $Helper = new TmMaternaDAL();
        return $Helper->GetMaternaRegistradasByMes($Year, $Mes, $MunicipioId);
    }
    
    public function GetActividadByMes($Year, $Mes, $MunicipioId) {
        $Helper = new TmMaternaDAL();
        return $Helper->GetActividadByMes($Year, $Mes, $MunicipioId);
    }
    
    public function GetTmAgendaMaterna($LiderId, $From, $To) {
        $Helper = new TmMaternaDAL();
        return $Helper->GetTmAgendaMaterna($LiderId, $From, $To);
    }
    
    public function GetTmMaternaByMaternaId($MaternaId) {
        $Helper = new TmMaternaDAL();
        return $Helper->GetTmMaternaByMaternaId($MaternaId);
    }
    
    public function GetTmMaternas() {
        $Helper = new TmMaternaDAL();
        return $Helper->GetTmMaternas();
    }

    public function CreateTmMaterna($list) {
        $Helper = new TmMaternaDAL();
        $m = $this->GetTmMaternaByDocumento($list->Documento);
        if (count($m) == 0) {
            $id = $Helper->CreateTmMaterna($this->MAPToArray($list));
            if (count($id) == 1) {
                return $id;
            } else {
                return "Error al registrar materna";
            }
        }else{
            return "Ya se encuentra registrado en la base de datos";
        }
    }

    public function UpdateTmMaterna($list) {
        $Helper = new TmMaternaDAL();
        $id = $Helper->UpdateTmMaterna($this->MAPToUpdate($list), $list->MaternaId);
        return $id;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombres' => $list->Nombres,
            'Documento' => $list->Documento,
            'Telefono' => $list->Telefono,
            'MunicipioId' => $list->MunicipioId,
            'EdadUltimaEcografia' => $list->EdadUltimaEcografia,
            'FechaUltimaRegla' => $list->FechaUltimaRegla,
            'FechaProbableParto' => $list->FechaProbableParto,
            'FechaPrimeraEcografia' => $list->FechaPrimeraEcografia,
            'LiderId' => $list->LiderId,
            'EPSId' => $list->EPSId->originalObject->EPSId,
            'CreatedBy' => $list->CreatedBy,
            'FechaRegistro' => $this->getDatetimeNow()
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombres' => $list->Nombres,
            'Telefono' => $list->Telefono,
            'MunicipioId' => $list->MunicipioId,
            'EdadUltimaEcografia' => $list->EdadUltimaEcografia,
            'FechaUltimaRegla' => $list->FechaUltimaRegla,
            'FechaProbableParto' => $list->FechaProbableParto,
            'FechaPrimeraEcografia' => $list->FechaPrimeraEcografia,
            'LiderId' => $list->LiderId,
            'EPSId' => $list->EPSId->originalObject->EPSId,
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
        return $datetime->format('Y-m-d h:i:s');
    }

}
