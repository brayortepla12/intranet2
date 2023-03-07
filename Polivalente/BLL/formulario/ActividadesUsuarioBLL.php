<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ActividadesUsuarioDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';


class ActividadesUsuarioBLL {

    public function GetActividadesUsuarioesByUsuario($UsuarioId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->GetActividadesUsuarioByUsuario($UsuarioId);
    }
    
    public function GetActividadesPendientes($UsuarioId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->GetActividadesPendientes($UsuarioId);
    }

    public function GetActividadesUsuarioByRondaId($RondaId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->GetActividadesUsuarioByRondaId($RondaId);
    }

    public function GetAllActividadesUsuarios() {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->GetAllActividadesUsuarios();
    }
    
    public function GetAllActividadUsuarioById($ActividadUsuarioId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->GetAllActividadUsuarioById($ActividadUsuarioId);
    }
    
    public function GetActividadesUsuarioByActividadesRondaId($ActividadesRondaId) {
        $Helper = new ActividadesUsuarioDAL();
        $listadoUsuarios = array();
        $ul = $Helper->GetActividadesUsuarioByActividadesRondaId($ActividadesRondaId);
        foreach ($ul as $value) {
            $obj = new stdClass();
            $obj->Nombres = $value->NombreCompleto;
            $obj->Cumplimiento = $value->Cumplimiento;
            $obj->Observacion = $value->Observacion;
            $obj->ActividadUsuarioId = $value->ActividadesUsuarioId;
            $obj->Estado = $value->Estado;
            array_push($listadoUsuarios, $obj);
        }
        return $listadoUsuarios;
    }
    public function GetActividadesUsuarioByActividadesRondaId2($ActividadesRondaId) {
        $Helper = new ActividadesUsuarioDAL();
        $listadoUsuarios = array();
        $ul = $Helper->GetActividadesUsuarioByActividadesRondaId($ActividadesRondaId);
        foreach ($ul as $value) {
            $obj = new stdClass();
            $obj->Nombres = $value->NombreCompleto;
            array_push($listadoUsuarios, $value->NombreCompleto);
        }
        return $listadoUsuarios;
    }
    
    public function DeleteRondaUsuarioByActividadRondaId($ActividadRondaId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->DeleteRondaUsuarioByActividadRondaId($ActividadRondaId);
    }

    public function CreateActividadesUsuario($list) {
        $Helper = new ActividadesUsuarioDAL();
        $id = $Helper->CreateActividadesUsuario($list);
        return $id;
    }
    
    public function CambiarEstadoActividadesUsuario($ActividadesUsuarioId, $Estado = "Leido") {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->UpdateActividadesUsuario($this->GetCambioEstado($Estado), $ActividadesUsuarioId);
    }
    
    public function ActualizarActividadesUsuario($list, $ModifiedBy,$ActividadesUsuarioId) {
        $Helper = new ActividadesUsuarioDAL();
        return $Helper->UpdateActividadesUsuario($this->MapToUpdate($list, $ModifiedBy), $ActividadesUsuarioId);
    }

    public function CountActividadesUsuarioes($UsuarioId) {
        $Helper = new ActividadesUsuarioDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountActividadesUsuarioes($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function MAPToArray($list, $RondaId, $Realizo) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Tipo' => $list[$index]->Tipo,
                'Equipo' => $list[$index]->Equipo,
                'Descripcion' => $list[$index]->Descripcion,
                'RondaId' => $RondaId,
                'CreatedBy' => $Realizo,
                'CreatedAt' => $this->getDatetimeNow()
            ));
        }


        return $list2;
    }

    function GetCambioEstado($Estado) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => $Estado,
            'ModifiedAt' => $this->getDatetimeNow()
        ));

        return $list2;
    }
    
    function MapToUpdate($list, $ModifiedBy) {
        $list2 = Array();
        array_push($list2, Array(
            'Cumplimiento' => $list->Cumplimiento,
            'Observacion' => $list->Observacion,
            'Estado' => $list->Cumplimiento === 'SI' ? 'Finalizado' : 'Activo',
            'ModifiedAt' => $this->getDatetimeNow(),
            'ModifiedBy' => $ModifiedBy,
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
