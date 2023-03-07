<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/tm/TmEventoDAL.php';
require_once dirname(__FILE__) . '/TmMaternaBLL.php';

class TmEventoBLL {

    public function GetTmEventos($TipoEvento) {
        $Helper = new TmEventoDAL();
        if($TipoEvento == 'Solicitado'){
            $TipoEvento = 'Activo';
        }
        return $Helper->GetTmEventos($TipoEvento);
    }

    public function GetTmEventoByEventoId($EventoId) {
        $Helper = new TmEventoDAL();
        $Evento = $Helper->GetTmEventoByEventoId($EventoId);
        $Evento = count($Evento) > 0 ? $Evento[0] : new stdClass();
        $Evento->Detalles = $Helper->GetDetalleEventos($EventoId);
        return $Evento;
    }
    
    public function GestionarEvento($EventoId, $ModifiedBy) {
        $Helper = new TmEventoDAL();
        $Evento = $Helper->UpdateEvento( [Array(
            'Estado' => 'Gestionado',
            'ModifiedBy' => $ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        )],$EventoId);
        return $Evento;
    }
    
    public function DeleteTmEvento($EventoId) {
        $Helper = new TmEventoDAL();
        $Evento = $Helper->DeleteTmEvento($EventoId);
        return $Evento;
    }
    
    public function GetTmEventoByMaternaIdMenosEste($MaternaId, $EventoId) {
        $Helper = new TmEventoDAL();
        $Evento = $Helper->GetTmEventoByMaternaIdMenosEste($MaternaId, $EventoId);
        return $Evento;
    }
    
    public function GetTmEventoByMaternaId($MaternaId) {
        $Helper = new TmEventoDAL();
        $Evento = $Helper->GetTmEventoByMaternaId($MaternaId);
        return $Evento;
    }


    public function CreateTmEvento($list) {
        $Helper = new TmEventoDAL();
        $id = $Helper->CreateEvento($this->MAPToArray($list));
        if($list->TipoEvento == "Parto Externo" && count($id) > 0){
            $o = new stdClass();
            $o->TarifaId = 30;
            $o->PrecioMaterna = 0;
            $o->PrecioAcompanante = 0;
            $o->Precio = 0;
            // No se entraga dinero
            $Helper->CreateDetalleEvento($this->MAPToDetalleEvento([$o], $id[0], $list->CreatedBy));
        }else if (count($id) > 0 && count($list->Detalles) > 0) {
            $Helper->CreateDetalleEvento($this->MAPToDetalleEvento($list->Detalles, $id[0], $list->CreatedBy));
        }
        return $id;
    }

    public function UpdateTmEvento($list) {
        $Helper = new TmEventoDAL();
//        $r = $Helper->VerificarDia($list->TmEventoId);
//        if (count($r) > 0) {
//            $id = $Helper->UpdateTmEvento($this->MAPToUpdate($list), $list->TmEventoId);
//            if ($id) {
//                $drvh = new DetalleTmEventoBLL();
//                $drvh->UpdateDetalleTmEvento($list->Pacientes, $list->ModifiedBy);
//            }
//            return $id;
//        } else {
//            return "No se puede editar una Ronda del dia anterior";
//        }
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'MaternaId' => $list->MaternaId,
            'Acompanante' => $list->Acompanante,
            'FechaRegistro' => $this->getDatetimeNow(),
            'Procedimiento' => $list->Procedimiento,
            'TipoEvento' => $list->TipoEvento,
            'TipoTransporte' => $list->TipoTransporte,
            'Comentario' => $list->Comentario,
            'FechaParto' => $list->FechaParto,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToDetalleEvento($list, $EventoId, $CreatedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'EventoId' => $EventoId,
                'TarifaId' => $list[$index]->TarifaId,
                'PrecioMaterna' => $list[$index]->PrecioMaterna,
                'PrecioAcompanante' => $list[$index]->PrecioAcompanante,
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
            'Acompanante' => $list->Acompanante,
            'Procedimiento' => $list->Procedimiento,
            'Comentario' => $list->Comentario,
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
