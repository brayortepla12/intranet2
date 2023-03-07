<?php

require_once dirname(__FILE__) . '/../../DAL/Hemodinamia/HistoriaClinicaDAL.php';

class HistoriaClinicaBLL{

    public function GetHistoriaClinicaByPacienteId($PacienteId) {
        $Helper = new HistoriaClinicaDAL();
        return $Helper->GetHistoriaClinicaByPacienteId($PacienteId);
    }
    
    public function GetHistoriaClinicaById($HistoriaClinicaId) {
        $Helper = new HistoriaClinicaDAL();
        return $Helper->GetHistoriaClinicaById($HistoriaClinicaId);
    }
    
    public function CreateHistoria($Historia, $PacienteId) {
        $db = new HistoriaClinicaDAL();
        $p = $db->CreateHistoriaClinica($this->MAPToArray($Historia, $PacienteId));
        return $p;
    }
    
    public function MAPToArray($list, $PacienteId) {
        $list2 = Array();
        array_push($list2, Array(
            'TratamientoFarmacologicoActual' => $list->TratamientoFarmacologicoActual,
            'AntecedentesFamiliares' => $list->AntecedentesFamiliares,
            'FactoresRiesgoCardiovasculares' => $list->FactoresRiesgoCardiovasculares,
            'ManejoActual' => $list->ManejoActual,
            'Patologicos' => $list->Patologicos,
            'EnfermedadesInfancia' => $list->EnfermedadesInfancia,
            'EnfermedadesAdultez' => $list->EnfermedadesAdultez,
            'Quirurgicos' => $list->Quirurgicos,
            'Hospitalizaciones' => $list->Hospitalizaciones,
            'Transfusiones' => $list->Transfusiones,
            'Toxicos' => $list->Toxicos,
            'Alergicos' => $list->Alergicos,
            'Alimenticios' => $list->Alimenticios,
            'Cigarrillo' => $list->Cigarrillo,
            'Alcohol' => $list->Alcohol,
            'Drogas' => $list->Drogas,
            'PacienteId' => $PacienteId,
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
