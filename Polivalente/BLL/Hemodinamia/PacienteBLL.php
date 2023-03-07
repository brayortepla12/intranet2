<?php

require_once dirname(__FILE__) . '/../../DAL/Hemodinamia/PacienteDAL.php';
require_once dirname(__FILE__) . '/HistoriaClinicaBLL.php';
class PacienteBLL{

    public function GetPacienteByCodigoQR($CodigoQR) {
        $Helper = new PacienteDAL();
        return $Helper->GetPacienteByCodigoQR($CodigoQR);
    }
    
    public function CreatePaciente($Paciente) {
        $db = new PacienteDAL();
        $id = $db->CreatePaciente($this->MAPToArray($Paciente));
        if (count($id)) {
            $hc = new HistoriaClinicaBLL();
            $Paciente->HistoricaClinica->CreatedBy = $Paciente->CreatedBy;
            $hc->CreateHistoria($Paciente->HistoricaClinica, $id[0]);
        }
        return $id;
    }
    
    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombres' => $list->Nombres,
            'Documento' => $list->Documento,
            'Edad' => $list->Edad,
            'EstadoCivil' => $list->EstadoCivil,
            'Religion' => $list->Religion,
            'Procedencia' => $list->Procedencia,
            'DireccionActual' => $list->DireccionActual,
            'Telefono' => $list->Telefono,
            'Ocupacion' => $list->Ocupacion,
            'RegimenSeguridadSocial' => $list->RegimenSeguridadSocial,
            'Entidad' => $list->Entidad,
            'TEsposo_a' => $list->TEsposo_a,
            'TMadre_Padre' => $list->TMadre_Padre,
            'THermano_a' => $list->THermano_a,
            'TAmigo_a' => $list->TAmigo_a,
            'THijo_a' => $list->THijo_a,
            'CodigoQR' => $list->CodigoQR,
            'CreatedBy' => $list->CreatedBy
        ));
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
