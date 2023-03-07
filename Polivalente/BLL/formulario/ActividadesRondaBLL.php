<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/ActividadesRondaDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/ActividadesUsuarioBLL.php';

class ActividadesRondaBLL {

    public function GetActividadesRondaesByUsuario($UsuarioId) {
        $Helper = new ActividadesRondaDAL();
        return $Helper->GetActividadesRondaByUsuario($UsuarioId);
    }

    public function GetActividadesRondaByRondaId($RondaId) {
        $Helper = new ActividadesRondaDAL();
        $auh = new ActividadesUsuarioBLL();
        $list = $Helper->GetActividadesRondaByRondaId($RondaId);
        foreach ($list as $value) {
            $value->Usuarios = $auh->GetActividadesUsuarioByActividadesRondaId($value->ActividadesRondaId);
            $value->Usuarios2 = $auh->GetActividadesUsuarioByActividadesRondaId2($value->ActividadesRondaId);
        }
        return $list;
    }

    public function GetAllActividadesRondas() {
        $Helper = new ActividadesRondaDAL();
        return $Helper->GetAllActividadesRondas();
    }

    public function CreateActividadesRonda($list, $RondaId, $Realizo) {
        $Helper = new ActividadesRondaDAL();
        $id = $Helper->CreateActividadesRonda($this->MAPToArray($list, $RondaId, $Realizo));
        return $id;
    }

    public function CambiarEstadoActividadesRonda($ActividadesRondaId, $Estado = "Leido") {
        $Helper = new ActividadesRondaDAL();
        return $Helper->UpdateActividadesRonda($this->GetCambioEstado($Estado), $ActividadesRondaId);
    }

    public function CountActividadesRondaes($UsuarioId) {
        $Helper = new ActividadesRondaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountActividadesRondaes($value->SedeId)[0]->Total;
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

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
