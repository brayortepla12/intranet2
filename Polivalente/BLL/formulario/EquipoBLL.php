<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/EquipoDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';

class EquipoBLL {

    public function GetAllEquipoes($Servicios, $Year) {
        $Helper = new EquipoDAL();
        $listado = array();
        foreach ($Servicios as $key => $value) {
            $Item = $Helper->GetAllEquipoes($value->ServicioId, $Year);
            if (count($Item) > 0) {
                array_push($listado, $Item);
            }
        }
        return json_encode($listado);
    }

    public function GetAllPlantasBySede($UserId) {
        $tz_object = new DateTimeZone('America/Bogota');
        $date = new DateTime();
        $date->setTimezone($tz_object);
        $Helper = new EquipoDAL();
        $listado = array();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UserId));
        $u = $list = array();
        foreach ($lsede as $value) {
            foreach ($Helper->GetAllPlantasBySedeId($value->SedeId) as $value2) {
                $value2->Sede = $value->Nombre;
                $value2->TipoServicio = 'ReporteDiario';
                $value2->TipoReporte = 'Manual';
                $value2->SolicitudId = NULL;
                $value2->RecibeFecha = NULL;
                $value2->RecibeHora = $date->format('H:i:s');
                $value2->Fecha = $date->format('Y-m-d');
                $value2->Ciudad = "VALLEDUPAR";
                // parametro quien recibe
                $value2->HoraInicio = $date->format('H:i:s');
                $minutes_to_add = 15;
                $date->add(new DateInterval('PT' . $minutes_to_add . 'M'));
                $value2->HoraFinal = $date->format('H:i:s');
                $value2->RecibeNombre = "FRANKLIN OSPINO";
                $value2->RecibeCargo = "N/A";
                $value2->NombreArchivo = "N/A";
                $value2->EstadoFinal = "N/A";
                $value2->Responsable = "N/A";
                $value2->TotalRepuesto = "N/A";
                $value2->NumeroReporte = "N/A";
                $value2->Solicitante = "N/A";
                $value2->FallaReportada = "";
                $value2->FallaDetectada = "";
                $value2->ProcedimientoRealizado = "N/A";
                $value2->MedidasAplicadas = "N/A";
                $value2->Repuestos = "N/A";
                $value2->ResponsableNombre = "N/A";
                $value2->ResponsableCargo = "N/A";
                $value2->ResponsableFirma = "N/A";
                $value2->ReporteArchivo = "N/A";
                
                array_push($list, $value2);
            }
        }
        if (count($list) > 0) {
            array_push($listado, $list);
        }
        return $listado;
    }

}
