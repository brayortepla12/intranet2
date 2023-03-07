<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/RondaSistemaServicioDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';


class RondaSistemaServicioBLL {
    
    public function GetAll() {
        $db = new RondaSistemaServicioDAL();
        return $db->getAll();
    }
    
    public function GetRondaSistemaServicioByRondaSistemaServicioId($RondaSistemaServicioId) {
        $db = new RondaSistemaServicioDAL();
        return $db->GetRondaSistemaServicioByRondaSistemaServicioId($RondaSistemaServicioId);
    }
    
    public function GetRondaSistema_Detalle($DetalleRondaId) {
        $db = new RondaSistemaServicioDAL();
        return $db->GetRondaSistema_Detalle($DetalleRondaId);
    }
    
    public function GetRondaSistemaServicioByUsuario($UsuarioId) {
        $db = new RondaSistemaServicioDAL();
        return $db->GetRondaSistemaServicioByUsuario($UsuarioId);
    }
    
    public function GetRondaSistemaServicioByUsuario_Ronda($UsuarioId, $RondaId) {
        $db = new RondaSistemaServicioDAL();
        return $db->GetRondaSistemaServicioByUsuario_Ronda($UsuarioId, $RondaId);
    }
    
    public function GetRondaSistemaServicioByUsuario_RondaFecha($UsuarioId, $RondaId, $Fecha) {
        $db = new RondaSistemaServicioDAL();
        return $db->GetRondaSistemaServicioByUsuario_RondaFecha($UsuarioId, $RondaId, $Fecha);
    }
    
    public function GetByRondaId($RondaId) {
        $db = new RondaSistemaServicioDAL();
        $Ronda = $db->GetByRondaId($RondaId);
        $Ronda[0]->ServiciosAsignados = $db->GetServiciosByRondaId($RondaId);
        $Ronda[0]->UsuariosAsignados = $db->GetUsuariosByRondaId($RondaId);
        return $Ronda;
    }
    
    public function CreateRondaSistema_Detalle($RondaSistema_Detalle, $CreatedBy) {
        $db = new RondaSistemaServicioDAL();
        // DetalleRondaId
        $obj = NULL;
        for ($index = 0; $index < count($RondaSistema_Detalle); $index++) {
            if ($RondaSistema_Detalle[$index]->DetalleRondaId != NULL) {
                $obj = $db->GetRondaSistema_Detalle($RondaSistema_Detalle[$index]->DetalleRondaId);
            }else{
                $obj = Array();
            }
            if (count($obj) > 0) {
                $list3 = Array();
                 array_push($list3, Array(
                    'Fecha' => $RondaSistema_Detalle[$index]->Fecha,
                    'Observacion1' => $RondaSistema_Detalle[$index]->Observacion1,
                    'Observacion2' => $RondaSistema_Detalle[$index]->Observacion2,
                    'Estado' => $RondaSistema_Detalle[$index]->Estado,
                    'ModifiedBy' => $CreatedBy,
                    'ModifiedAt' => $this->getDatetimeNow(),
                ));
                $db->UpdateRondaSistema_Detalle($list3, $RondaSistema_Detalle[$index]->DetalleRondaId);
                $RondaSistema_Detalle[$index] = NULL;
            }
        }
        $ParaRegistrar = $this->MAPToRondaSistema_Detalle($RondaSistema_Detalle, $CreatedBy);
        if (count($ParaRegistrar) > 0) {
            return $db->CreateRondaSistema_Detalle($ParaRegistrar);
        }else{
            return $RondaSistema_Detalle;
        }
        
    }
    
    public function CreateRondaSistemaServicio($RondaSistemaServicio) {
        $db = new RondaSistemaServicioDAL();
        $obj = $db->CreateRondaSistema($this->MAPToRondaSistema($RondaSistemaServicio));
        if (count($obj) > 0) {
            $db = new RondaSistemaServicioDAL();
            return $db->CreateRondaSistemaServicioUsuario($this->MAPToRondaSistemaServicioUsuario($RondaSistemaServicio, $obj[0], $RondaSistemaServicio[0]->CreatedBy));
        }else{
            return "No se pudo registrar correctamente";
        }
    }
    
    public function UpdateRondaSistemaServicio($list, $id) {
        $db = new RondaSistemaServicioDAL();
        $db->DeleteRonda($id);
        $obj = $db->UpdateRonda($this->MAPToUpdateRondaSistema($list), $id);
        $db->CreateRondaSistemaServicioUsuario($this->MAPToRondaSistemaServicioUsuario($list, $id, $list[0]->CreatedBy));
        return  $obj;
    }
    
    public function MAPToRondaSistema_Detalle($list, $CreatedBy) {
        $list2 = Array();
        $db = new RondaSistemaServicioDAL();
        for ($index = 0; $index < count($list); $index++) {
            if ($list[$index] != NULL) {
                array_push($list2, Array(
                    'RondaServicioUsuarioId' => $list[$index]->RondaServicioUsuarioId,
                    'Fecha' => $list[$index]->Fecha,
                    'Observacion1' => $list[$index]->Observacion1,
                    'Observacion2' => $list[$index]->Observacion2,
                    'Estado' => $list[$index]->Estado,
                    'CreatedBy' => $CreatedBy
                ));
            }
        }
        return $list2;
    }
    
    public function MAPToRondaSistema($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Tipo' => $list[$index]->Tipo,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    
    public function MAPToUpdateRondaSistema($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Tipo' => $list[$index]->Tipo,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow()
            ));
        }
        return $list2;
    }
    
    public function MAPToRondaSistemaServicioUsuario($list, $RondaId, $CreatedBy) {
        $list2 = Array();
            for ($k = 0; $k < count($list[0]->ServiciosAsignados); $k++) {
                for ($j = 0; $j < count($list[0]->UsuariosAsignados); $j++) {
                    array_push($list2, Array(
                        'ServicioId' => $list[0]->ServiciosAsignados[$k]->ServicioId,
                        'UsuarioId' => $list[0]->UsuariosAsignados[$j]->UsuarioId,
                        'RondaId' => $RondaId,
                        'CreatedBy' => $CreatedBy
                    ));
                }
            }
        return $list2;
    }
    
    
    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'RondaSistemaServicioId' => $list[$index]->RondaSistemaServicioId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
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
