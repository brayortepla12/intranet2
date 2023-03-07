<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/PacienteDAL.php';
require_once dirname(__FILE__) . '/DetalleRondaVerificacionBLL.php';
require_once dirname(__FILE__) . '/MedicamentoBLL.php';

class PacienteBLL {

    public function GetPacientesBySector($Sector, $TipoMedicamentoId, $Fecha, $TipoRonda) {
        $Helper = new PacienteDAL();
        $drvh = new DetalleRondaVerificacionBLL();
        $hm = new MedicamentoBLL();
        $Pacientes = Array();
        if ($Sector == 'N/A') {
            array_push($Pacientes, (object) array(
                        "NOADMISION" => 'N/A',
                        "IDAFILIADO" => 'N/A',
                        "PAPELLIDO" => '',
                        "SAPELLIDO" => '',
                        "PNOMBRE" => 'N/A',
                        "SNOMBRE" => '',
                        "HABCAMA" => '',
                        "Sector" => 'N/A',
                        "DESCRIPCION" => 'N/A'
            ));
            if ($TipoRonda == "Loteado") {
                $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId_Lite_Loteado($TipoMedicamentoId);
            } else {
                $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
            }
            $Pacientes = $drvh->VerificarPacientesAnteriores($Pacientes, $Medicamentos, $Fecha);
        } else {
            $Pacientes = $Helper->GetPacientesBySector($Sector);
            $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
            $Pacientes = $drvh->VerificarPacientesAnteriores($Pacientes, $Medicamentos, $Fecha);
        }



        return $Pacientes;
    }

    public function GetPacientesBySector_forUpdate($Sector, $TipoMedicamentoId) {
        $Helper = new PacienteDAL();
        $drvh = new DetalleRondaVerificacionBLL();
        $hm = new MedicamentoBLL();
        $Pacientes = $Helper->GetPacientesBySector($Sector);
        $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
        $Pacientes = $drvh->VerificarPacientesAnteriores_forUpdate($Pacientes, $Medicamentos);
        return $Pacientes;
    }

    public function GetPacientesByRondaVerificacionId_forUpdate($RondaVerificacionId, $TipoMedicamentoId, $Sector, $Fecha, $TipoRonda) {
        $Helper = new PacienteDAL();
        $drvh = new DetalleRondaVerificacionBLL();
        $hm = new MedicamentoBLL();
        if ($TipoRonda == "Loteado") {
            $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId_Lite_Loteado($TipoMedicamentoId);
        } else {
            $Medicamentos = $hm->GetMedicamentosByTipoMedicamentoId($TipoMedicamentoId);
        }
        $Pacientes = $drvh->VerificarPacientesAnteriores_forUpdate($RondaVerificacionId, $Medicamentos, $Sector, $Fecha);
        return $Pacientes;
    }

}
