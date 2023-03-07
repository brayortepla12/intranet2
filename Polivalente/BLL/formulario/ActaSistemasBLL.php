<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ActaSistemasDAL.php';

class ActaSistemasBLL {

    public function CreateActaSistemas($list) {
        $Helper = new ActaSistemasDAL();
        $consecutivo = $Helper->GetConsecurivoActual($list->TipoActa);
        $id  = "ERROR";
        if (count($consecutivo) > 0) {
            $id = $Helper->CreateActaSistemas($this->MapToCreate($list, $consecutivo[0]->Consecutivo));
            if (count($id) > 0 && count($list->Detalles) > 0) {
                $Helper->UpdateConsecutivo([Array(
                'Consecutivo' => $consecutivo[0]->Consecutivo + 1,
                    )], $consecutivo[0]->ConsecutivoId);
                $Helper->CreateDetalleActaSistemas($this->MapToCreateDetalleActa($list->Detalles, $id[0]));
            }
        }

        return $id;
    }

    public function GetDetalleByActaId($ActaId) {
        $Helper = new ActaSistemasDAL();
        return $Helper->GetDetalleByActaId($ActaId);
    }

    public function GetAll() {
        $Helper = new ActaSistemasDAL();
        return $Helper->GetAll();
    }

    public function MapToCreateDetalleActa($list, $ActaId) {
        $list2 = Array();
        for ($i = 0; $i < count($list); $i++) {
            array_push($list2, Array(
                'ActaId' => $ActaId,
                'Cantidad' => $list[$i]->Cantidad,
                'HojaVidaId' => $list[$i]->HojaVidaId,
                'Elemento' => $list[$i]->Elemento,
                'Marca' => $list[$i]->Marca,
                'Modelo' => $list[$i]->Modelo,
                'Serial' => $list[$i]->Serial,
            ));
        }

        return $list2;
    }

    public function MapToCreate($list, $Consecutivo) {
        $list2 = Array();
        array_push($list2, Array(
            'Fecha' => $list->Fecha,
            'ServicioId' => $list->ServicioId,
            'RecibeId' => $list->RecibeId,
            'NumeroActa' => $Consecutivo + 1,
            'ResponsableId' => $list->ResponsableId,
            'Descripcion' => $list->Descripcion,
            'Nota' => $list->Nota,
            'Destino' => $list->Destino,
            'Area' => $list->Area,
            'RecibeN' => $list->RecibeN,
            'RecibeC' => $list->RecibeC,
            'MensajeIntroductorio' => $list->MensajeIntroductorio,
            'Motivo' => $list->Motivo,
            'TipoActa' => $list->TipoActa,
            'CreatedBy' => $list->CreatedBy
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
