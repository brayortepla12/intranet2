<?php

/**
 * @author Franklin ospino
 */
require_once dirname(__FILE__) . '/../../DAL/cm/DetalleRondaVerificacionDAL.php';
require_once dirname(__FILE__) . '/../../DAL/cm/PacienteDAL.php';

class DetalleRondaVerificacionBLL {

    public function GetRondaesByUsuario($UsuarioId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetRondaByUsuario($UsuarioId);
    }
    
    public function GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $MedicamentoId);
    }

    public function GetDetalleRondaVerificacionByRondaVerificacionId_preview($RondaVerificacionId, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_preview($RondaVerificacionId, $MedicamentoId);
    }

    public function GetDetalleRondaVerificacionByRondaVerificacionId_productoInicial($RondaVerificacionId, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_productoInicial($RondaVerificacionId, $MedicamentoId);
    }

    public function GetDetalleRondaVerificacionByRondaVerificacionId($RondaVerificacionId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByRondaVerificacionId($RondaVerificacionId);
    }

    public function GetDetalleRondaVerificacionByIdAfiliado($IdAfiliado) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByIdAfiliado($IdAfiliado);
    }

    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId($IdAfiliado, $MedicamentoId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId($IdAfiliado, $MedicamentoId);
    }

    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoIdFecha($IdAfiliado, $MedicamentoId, $Fecha) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByIdAfiliado_MedicamentoIdFecha($IdAfiliado, $MedicamentoId, $Fecha);
    }

    public function GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId_Ronda($IdAfiliado, $MedicamentoId, $RondaVerificacionId) {
        $Helper = new DetalleRondaVerificacionDAL();
        return $Helper->GetDetalleRondaVerificacionByIdAfiliado_MedicamentoId_Ronda($IdAfiliado, $MedicamentoId, $RondaVerificacionId);
    }

    public function VerificarPacientesAnteriores_forUpdate($RondaVerificacionId, $Medicamentos, $Sector, $Fecha) {
        $Helper = new DetalleRondaVerificacionDAL();
        $ph = new PacienteDAL();
        $Rondas = $Helper->GetDetalleRondaVerificacionByRondaVerificacionId_Sector($RondaVerificacionId, $Sector, $Fecha);
        if (count($Rondas) > 0) {
            foreach ($Rondas as $r) {
                $r->EstadoPaciente = $r->EstadoPaciente === "Nuevo" ? "Continuar" : $r->EstadoPaciente;
                $r->ListadoMedicamentos = Array();
                $Cantidad = $Helper->GetCantidad($r->IdAfiliado, $RondaVerificacionId, $Medicamentos[0]->TipoMedicamentoId);
                $r->CT = $Cantidad[0]->Total;
                if ($Cantidad[0]->Total == 0) {
                    $r->NoImprimirVacio = True;
                }
                foreach ($Medicamentos as $m) {
                    $MPaciente = $this->GetDetalleRondaVerificacionByIdAfiliado_MedicamentoIdFecha($r->IdAfiliado, $m->MedicamentoId, $Fecha);

                    if (count($MPaciente) > 0) {
                        if ($m->MedicamentoId == 18 || $m->MedicamentoId == 16 || $m->MedicamentoId == 41) {
                            $MPaciente[0]->NoVolumen = TRUE;
                        }
                        array_push($r->ListadoMedicamentos, $MPaciente[0]);
                    } else {
                        $Obj = new stdClass();
                        if ($m->MedicamentoId == 18 || $m->MedicamentoId == 16 || $m->MedicamentoId == 41) {
                            $Obj->NoVolumen = TRUE;
                        }
                        $Obj->Notiene = True;
                        $Obj->MedicamentoId = $m->MedicamentoId;
                        $Obj->Volumen = "";
                        $Obj->Dosis = "";
                        $Obj->VehiculoId = 1;
                        $Obj->Cantidad = "";
                        array_push($r->ListadoMedicamentos, $Obj);
                    }
                }
            }
        }
        return $Rondas;
    }

    public function IsInDB($c_IDAFILIADO, $Rondas) {
        foreach ($Rondas as $r) {
            if ($r->IdAfiliado) {
                if ($c_IDAFILIADO === $r->IdAfiliado) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function VerificarPacientesAnteriores($Pacientes, $Medicamentos, $Fecha) {
        $listado = array();
        foreach ($Pacientes as $p) {
            $Helper = new DetalleRondaVerificacionDAL();
            $Paciente = $Helper->GetDetalleRondaVerificacionByIdAfiliado($p->IDAFILIADO);
            if (count($Paciente) > 0) {
                if($Paciente[0]->EstadoPaciente !== "Nuevo"){
                    $Paciente[0]->NoAdmision = $p->NOADMISION; // actualizamos la admision del paciente
                }
                $Paciente[0]->EstadoPaciente = $Paciente[0]->EstadoPaciente === "Nuevo" ? "Continuar" : $Paciente[0]->EstadoPaciente;
                $Paciente[0]->ListadoMedicamentos = Array();
                $contador = 1;
                foreach ($Medicamentos as $m) {
                    $MPaciente = $this->GetDetalleRondaVerificacionByIdAfiliado_MedicamentoIdFecha($p->IDAFILIADO, $m->MedicamentoId, $Fecha);
                    if (count($MPaciente) > 0) {
                        if ($m->MedicamentoId == 18 || $m->MedicamentoId == 16 || $m->MedicamentoId == 41) {
                            $MPaciente[0]->NoVolumen = TRUE;
                        }
                        if ($MPaciente[0]->Volumen == "0" && $MPaciente[0]->Dosis == "0" && $MPaciente[0]->Cantidad == "0") {
                            $contador++;
                        }
                        if ($contador == count($Medicamentos)) {
                            $Paciente[0]->EstadoPaciente = "Suspender";
                        }
                        array_push($Paciente[0]->ListadoMedicamentos, $MPaciente[0]);
                    } else {
                        $Obj = new stdClass();
                        if ($m->MedicamentoId == 18 || $m->MedicamentoId == 16 || $m->MedicamentoId == 41) {
                            $Obj->NoVolumen = TRUE;
                        }
                        $Obj->MedicamentoId = $m->MedicamentoId;
                        $Obj->Volumen = "";
                        $Obj->Dosis = "";
                        $Obj->Cantidad = "";
                        $Obj->VehiculoId = 1;
                        array_push($Paciente[0]->ListadoMedicamentos, $Obj);
                    }
                }
                array_push($listado, $Paciente[0]);
            } else {
                $obj = new stdClass();
                $obj->PNombre = $p->PNOMBRE;
                $obj->SNombre = $p->SNOMBRE;
                $obj->PApellido = $p->PAPELLIDO;
                $obj->SApellido = $p->SAPELLIDO;
                $obj->IdAfiliado = $p->IDAFILIADO;
                $obj->NoAdmision = $p->NOADMISION;
                $obj->ListadoMedicamentos = Array();
                foreach ($Medicamentos as $m) {
                    $Obj = new stdClass();
                    if ($m->MedicamentoId == 18 || $m->MedicamentoId == 16 || $m->MedicamentoId == 41) {
                        $Obj->NoVolumen = TRUE;
                    }
                    $Obj->MedicamentoId = $m->MedicamentoId;
                    $Obj->Volumen = "";
                    $Obj->Dosis = "";
                    $Obj->Cantidad = "";
                    $Obj->VehiculoId = 1;
                    array_push($obj->ListadoMedicamentos, $Obj);
                }
                $obj->EstadoPaciente = "Nuevo";
                array_push($listado, $obj);
            }
        }
        return $listado;
    }

    public function CreateDetalleRondaVerificacion($list, $RondaVerificacionId, $CreatedBy, $Sector) {
        $Helper = new DetalleRondaVerificacionDAL();
        $id = $Helper->CreateDetalleRondaVerificacion($this->MAPToArray($list, $RondaVerificacionId, $CreatedBy, $Sector));
        return $id;
    }

    public function UpdateDetalleRondaVerificacion($list, $ModifiedBy) {
        $listado = $this->MAPToUpdate($list, $ModifiedBy);
        foreach ($listado as $l) {
            $Helper = new DetalleRondaVerificacionDAL();
            $Helper->UpdateDetalleRondaVerificacion($l, $l["DetalleRondaVerificacionId"]);
        }
        return $listado;
    }

    public function VerificarDetalleRondaVerificacion($list) {
        $listado = $this->MAPToVerificar($list);
        $Helper = new DetalleRondaVerificacionDAL();
        $Helper->UpdateDetalleRondaVerificacion($listado[0], $list[0]->DetalleRondaVerificacionId);
        return $listado;
    }

    public function EliminarDetalleRondaVerificacion($list) {
        $listado = $this->MAPToEliminar($list);
        $Helper = new DetalleRondaVerificacionDAL();
        $Helper->UpdateDetalleRondaVerificacion($listado[0], $list[0]->DetalleRondaVerificacionId);
        return $listado;
    }

    public function EliminarDMR($list) {
        $listado = $this->MAPToEliminarDMR($list[0]);
        $Helper = new DetalleRondaVerificacionDAL();
        $Helper->UpdateDMR($listado[0], $list[0]->DispositivoMedicoByRondaId);
        return $listado;
    }

    public function MAPToVerificar($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Verificado' => $list[$index]->Verificado,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow()
            ));
        }
        return $list2;
    }

    public function MAPToEliminarDMR($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => 'Inactivo',
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow()
        ));

        return $list2;
    }

    public function MAPToEliminar($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Estado' => $list[$index]->Estado,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow()
            ));
        }
        return $list2;
    }

    public function MAPToArray($list, $RondaVerificacionId, $CreatedBy, $Sector) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            foreach ($list[$index]->ListadoMedicamentos as $m) {
                if ($m->Dosis > 0 && $m->Cantidad > 0 && property_exists($m, 'Crear')) {// !property_exists($m, 'DetalleRondaVerificacionId') &&
                    array_push($list2, Array(
                        'Sector' => $Sector,
                        'RondaVerificacionId' => $RondaVerificacionId,
                        'PNombre' => $list[$index]->PNombre,
                        'SNombre' => $list[$index]->SNombre,
                        'PApellido' => $list[$index]->PApellido,
                        'SApellido' => $list[$index]->SApellido,
                        'IdAfiliado' => $list[$index]->IdAfiliado,
                        'NoAdmision' => $list[$index]->NoAdmision,
                        'MedicamentoId' => $m->MedicamentoId,
                        'Dosis' => $m->Dosis,
                        'Cantidad' => $m->Cantidad,
                        'VehiculoId' => $m->VehiculoId,
                        'Volumen' => $m->Volumen,
                        'EstadoPaciente' => $list[$index]->EstadoPaciente,
                        'CreatedBy' => $CreatedBy
                    ));
                }
            }
        }
        return $list2;
    }

    public function MAPToNuevos($list, $RondaVerificacionId, $CreatedBy, $Sector) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            foreach ($list[$index]->ListadoMedicamentos as $m) {
                if (!property_exists($m, 'DetalleRondaVerificacionId')) {
                    array_push($list2, Array(
                        'Sector' => $Sector,
                        'RondaVerificacionId' => $RondaVerificacionId,
                        'PNombre' => $list[$index]->PNombre,
                        'SNombre' => $list[$index]->SNombre,
                        'PApellido' => $list[$index]->PApellido,
                        'SApellido' => $list[$index]->SApellido,
                        'IdAfiliado' => $list[$index]->IdAfiliado,
                        'NoAdmision' => $list[$index]->NoAdmision,
                        'MedicamentoId' => $m->MedicamentoId,
                        'Dosis' => $m->Dosis,
                        'Cantidad' => $m->Cantidad,
                        'VehiculoId' => $m->VehiculoId,
                        'Volumen' => $m->Volumen,
                        'EstadoPaciente' => $list[$index]->EstadoPaciente,
                        'CreatedBy' => $CreatedBy
                    ));
                }
            }
        }
        return $list2;
    }

    public function MAPToUpdate($list, $ModifiedBy) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            foreach ($list[$index]->ListadoMedicamentos as $m) {
                if (property_exists($m, 'DetalleRondaVerificacionId')) {
                    array_push($list2, Array(
                        'DetalleRondaVerificacionId' => $m->DetalleRondaVerificacionId,
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
        }
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
