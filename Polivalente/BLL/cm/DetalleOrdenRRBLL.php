<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/DetalleOrdenRRDAL.php';
require_once dirname(__FILE__) . '/../../DAL/cm/PacienteDAL.php';

class DetalleOrdenRRBLL {

    public function GetDetalleOrdenRRByOrdenRRId($OrdenRRId) {
        $Helper = new DetalleOrdenRRDAL();
        return $Helper->GetDetalleOrdenRRByOrdenRRId($OrdenRRId);
    }

    public function CreateDetalleOrdenRR($list, $OrdenRRId, $CreatedBy) {
        $Helper = new DetalleOrdenRRDAL();
        $id = $Helper->CreateDetalleOrdenRR($this->MAPToArray($list, $OrdenRRId, $CreatedBy));
        return $id;
    }

    public function UpdateDetalleOrdenRR($list, $ModifiedBy) {
        $listado = $this->MAPToUpdate($list, $ModifiedBy);
        foreach ($listado as $l) {
            $Helper = new DetalleOrdenRRDAL();
            if ($l["DetalleOrdenRRId"] != -9) {
                $Helper->UpdateDetalleOrdenRR($l, $l["DetalleOrdenRRId"]);
            }
        }
        $listado_nuevos = $this->MAPToNuevos($list, $list[0]->RondaVerificacionId, $ModifiedBy, $list[0]->Sector);
        if (count($listado_nuevos) > 0) {
            $Helper->CreateDetalleOrdenRR($listado_nuevos);
        }

        return $listado_nuevos;
    }

    public function MAPToArray($list, $OrdenRRId, $CreatedBy) {
        $list2 = Array();
        if (is_array($list)) {
            for ($index = 0; $index < count($list); $index++) {
                array_push($list2, Array(
                    'OrdenRRId' => $OrdenRRId,
                    'MedicamentoId' => $list[$index]->MedicamentoId,
                    'VehiculoId' => $list[$index]->DispositivoMedicoId,
                    'LoteFabricante' => $list[$index]->LoteFabricante,
                    'LotePTerminado' => $list[$index]->LotePTerminado,
                    'FechaVencimientoFabricante' => $list[$index]->FechaVencimientoFabricante,
                    'FechaVencimiento' => $list[$index]->FechaVencimiento,
                    'Cantidad' => $list[$index]->Cantidad,
                    'CreatedBy' => $CreatedBy
                ));
            }
        }
        return $list2;
    }

    public function MAPToUpdate($list, $ModifiedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            foreach ($list[$index]->ListadoMedicamentos as $m) {
                array_push($list2, Array(
                    'DetalleOrdenRRId' => $m->DetalleOrdenRRId,
                    'Dosis' => $m->Dosis,
                    'Cantidad' => $m->Cantidad,
                    'Volumen' => $m->Volumen,
                    'VehiculoId' => $m->VehiculoId,
                    'EstadoPaciente' => $list[$index]->EstadoPaciente,
                    'ModifiedBy' => $ModifiedBy,
                    'ModifiedAt' => $this->getDatetimeNow()
                ));
            }
        }
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
