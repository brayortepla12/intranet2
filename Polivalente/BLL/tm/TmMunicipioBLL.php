<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmMunicipioDAL.php';

class TmMunicipioBLL {

    public function GetTmMunicipiosByDepartamentoId($DepartamentoId) {
        $Helper = new TmMunicipioDAL();
        return $Helper->GetTmMunicipiosByDepartamentoId($DepartamentoId);
    }

    public function CreateTmMunicipio($list) {
        $Helper = new TmMunicipioDAL();
        foreach ($list as $tm) {
            foreach ($tm->RVs as $key => $rv) {
                $r = $Helper->VerificarDiaBySector($rv->Sector, $tm->TipoMedicamentoId);
                if (count($r) === 0) {
                    $Ronda = $Helper->GetRondaByFecha($rv->Fecha);
                    $id = Null;

                    if (count($Ronda) == 0) {
                        $id = $Helper->CreateTmMunicipio($this->MAPToArray($rv));
                    } else {
                        $id = [$Ronda[0]->TmMunicipioId];
                    }

                    if ($id) {
                        $drvh = new DetalleTmMunicipioBLL();
                        $id2 = $drvh->CreateDetalleTmMunicipio($rv->Pacientes, $id[0], $rv->CreatedBy, $rv->Sector);
                        if (count($id2) > 0) {
                            unset($tm->RVs[$key]);
                        }
                    }
                }else{
                    $rv->TmMunicipioId = $r[0]->TmMunicipioId;
                    $rv->Error = "Hubo un error, ya se encuentra registrado este servicio, por favor editelo.";
                }
            }
        }
        return $list;
    }

    public function UpdateTmMunicipio($list) {
        $Helper = new TmMunicipioDAL();
        $r = $Helper->VerificarDia($list->TmMunicipioId);
        if (count($r) > 0) {
            $id = $Helper->UpdateTmMunicipio($this->MAPToUpdate($list), $list->TmMunicipioId);
            if ($id) {
                $drvh = new DetalleTmMunicipioBLL();
                $drvh->UpdateDetalleTmMunicipio($list->Pacientes, $list->ModifiedBy);
            }
            return $id;
        } else {
            return "No se puede editar una Ronda del dia anterior";
        }
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'CreatedBy' => $list->CreatedBy
        ));
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
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
