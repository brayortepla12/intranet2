<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/OrdenRRDAL.php';
require_once dirname(__FILE__) . '/DetalleOrdenRRBLL.php';
require_once dirname(__FILE__) . '/PacienteBLL.php';
require_once dirname(__FILE__) . '/MedicamentoBLL.php';

class OrdenRRBLL {

    public function GetOrdenRRByFecha($Fecha, $TipoMedicamento) {
        $Helper = new OrdenRRDAL();
        $dorrh = new DetalleOrdenRRBLL();
        $OrdenRR = $Helper->GetOrdenRRByFecha($Fecha, $TipoMedicamento);
        if (count($OrdenRR) > 0) {
            $OrdenRR[0]->DetalleOrdenRR = $dorrh->GetDetalleOrdenRRByOrdenRRId($OrdenRR[0]->OrdenRRId);
        }
        return $OrdenRR;
    }

    public function OrdenRR_excel($OrdenRRId, $TipoMedicamentoId) {
        $Helper = new DetalleOrdenRRBLL();
        $hr = new OrdenRRBLL();
        $mh = new MedicamentoBLL();
        $Preview = array();
        $Medicamentos = $mh->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
        $Ronda = $hr->GetRondaById($TipoMedicamentoId);
        foreach ($Medicamentos as $m) {
            $obj = new stdClass();
            $obj = $Ronda[0];
            $obj->NombreMedicamento = $m->Nombre;
            $obj->MedicamentoId = $m->MedicamentoId;
            $obj->DetallesOrdenRR = $Helper->GetDetalleOrdenRRByOrdenRRId_preview($OrdenRRId, $m->MedicamentoId);
            array_push($Preview, $obj);
        }
        return $Preview;
    }

    public function CreateOrdenRR($list) {
        $Helper = new OrdenRRDAL();
        foreach ($list as $o) {
            $Orden = $Helper->GetOrdenRRByFecha($o->Fecha, $o->TipoMedicamento);
            $id = Null;
            if (count($Orden) == 0) {
                $id = $Helper->CreateOrdenRR($this->MAPToArray($o));
            } else {
                $id = [$Orden[0]->OrdenRRId];
            }
            if ($id) {
                $doh = new DetalleOrdenRRBLL();
                $id2 = $doh->CreateDetalleOrdenRR($o->DetalleOrdenRR, $id[0], $o->CreatedBy);
            }
        }
        return $list;
    }

    public function UpdateOrdenRR_AP($list) {
        $Helper = new OrdenRRDAL();
        $id = $Helper->UpdateOrdenRR($this->MAPToUpdateAP($list), $list->OrdenRRId);
        return $id;
    }
    
    public function UpdateOrdenRR_AF($list) {
        $Helper = new OrdenRRDAL();
        $id = $Helper->UpdateOrdenRR($this->MAPToUpdateAF($list), $list->OrdenRRId);
        return $id;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'TipoMedicamento' => $list->TipoMedicamento,
            'DireccionTecnicaId' => $list->DireccionTecnicaId,
            'DireccionTecnica' => $list->DireccionTecnica,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdateAP($list) {
        $list2 = Array();
        array_push($list2, Array(
            'AProduccion' => $list->AProduccion,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));
        return $list2;
    }
    
    public function MAPToUpdateAF($list) {
        $list2 = Array();
        array_push($list2, Array(
            'AFarmacia' => $list->AFarmacia,
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
