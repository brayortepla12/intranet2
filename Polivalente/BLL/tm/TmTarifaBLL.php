<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmTarifaDAL.php';

class TmTarifaBLL {
    
    public function GetTmTarifas() {
        $Helper = new TmTarifaDAL();
        return $Helper->GetTmTarifas();
    }

    public function getTarifasAdmin() {
        $Helper = new TmTarifaDAL();
        $tarifas = $Helper->Search("SELECT t.TarifaId, t.Nombre, 
        dorigen.DepartamentoId AS DepartamentoOrigenId, dorigen.Departamento AS DepartamentoOrigen, co.Ciudad AS CiudadOrigen, 
        dd.DepartamentoId AS DepartamentoDestinoId, dd.Departamento AS DepartamentoDestino, cd.Ciudad AS CiudadDestino, 
        t.OrigenId, t.Precio, t.OtroId, tar.Precio AS PrecioOtro, 
        t.DestinoId 
        FROM tm_tarifa as t
        INNER JOIN tm_ciudad as co on t.OrigenId = co.CiudadId
        INNER JOIN tm_departamento as dorigen on dorigen.DepartamentoId = co.DepartamentoId
        INNER JOIN tm_ciudad as cd on t.DestinoId = cd.CiudadId
        INNER JOIN tm_departamento as dd on dd.DepartamentoId = cd.DepartamentoId
        LEFT JOIN tm_tarifa as tar on tar.TarifaId = t.OtroId
        ORDER BY dorigen.Departamento, co.Ciudad");
        return ['data' => !empty($tarifas) ? $tarifas : []];
    }

    public function GetTmTarifaByTarifaId($TarifaId) {
        $Helper = new TmTarifaDAL();
        return $Helper->GetTmTarifaByTarifaId($TarifaId);
    }
    
    public function GetTarifaBYMaterna($Documento) {
        $Helper = new TmTarifaDAL();
        return $Helper->GetTarifaBYMaterna($Documento);
    }
    
    public function GetTarifaByOrigen($OrigenId) {
        $Helper = new TmTarifaDAL();
        return $Helper->GetTarifaByOrigen($OrigenId);
    }

    public function CreateTmTarifa($list) {
        $Helper = new TmTarifaDAL();
        foreach ($list as $tm) {
            foreach ($tm->RVs as $key => $rv) {
                $r = $Helper->VerificarDiaBySector($rv->Sector, $tm->TipoMedicamentoId);
                if (count($r) === 0) {
                    $Ronda = $Helper->GetRondaByFecha($rv->Fecha);
                    $id = Null;

                    if (count($Ronda) == 0) {
                        $id = $Helper->CreateTmTarifa($this->MAPToArray($rv));
                    } else {
                        $id = [$Ronda[0]->TmTarifaId];
                    }

                    if ($id) {
                        $drvh = new DetalleTmTarifaBLL();
                        $id2 = $drvh->CreateDetalleTmTarifa($rv->Pacientes, $id[0], $rv->CreatedBy, $rv->Sector);
                        if (count($id2) > 0) {
                            unset($tm->RVs[$key]);
                        }
                    }
                }else{
                    $rv->TmTarifaId = $r[0]->TmTarifaId;
                    $rv->Error = "Hubo un error, ya se encuentra registrado este servicio, por favor editelo.";
                }
            }
        }
        return $list;
    }

    public function UpdateTmTarifa(object $tarifa) : array
    {
        $Helper = new TmTarifaDAL();
        $count = $Helper->UpdateBulk('tm_tarifa', 
        ['TarifaId', 'Nombre', 'OrigenId', 'DestinoId', 'Precio', 'OtroId', 'ModifiedBy', 'ModifiedAt'], 
        [
            [$tarifa->TarifaId, $tarifa->Nombre, $tarifa->OrigenId, $tarifa->DestinoId, $tarifa->Precio, $tarifa->OtroId, $tarifa->ModifiedBy, $this->getDatetimeNow()]
        ]);
        return $count > 0 ? ['data' => 'Se han actualizado los datos exitosamente'] : ['error' => 'Ha ocurrido un error al intentar actualizar la tarifa'];
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
