<?php

require_once dirname(__FILE__) . '/../../DAL/thc/NotasDAL.php';
require_once dirname(__FILE__) . '/../../DAL/thc/GrupoDAL.php';
/**
 * Description of NotasBLL
 *
 * @author DESARROLLO2
 */
class NotasBLL {
    public function GetNotasByHistoriaId($HistoriaId) {
        $helper = new NotasDAL();
        return $helper->GetNotasByHistoriaId($HistoriaId);
    }
    public function CreateNota($list) {
        $helper = new NotasDAL();
        $hg = new GrupoDAL();
        $Grupo = $hg->GetGrupoByUsuario($list->UsuarioId);
        $ids = $helper->CreateNota($this->MAPToCreateNota($list, $Grupo[0]->GrupoId));
        return $ids;
    }
    public function MAPToCreateNota($list, $GrupoId) {
        $list2 = Array();
        array_push($list2, Array(
            'UsuarioId' => $list->UsuarioId,
            'HistoriaId' => $list->HistoriaId,
            'GrupoId' => $GrupoId,
            'Observacion' => $list->Observacion,
            'Fecha' => $this->getDatetimeNow(),
            'CreatedBy' => $list->CreatedBy,
        ));
        return $list2;
    }
    
    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
}
