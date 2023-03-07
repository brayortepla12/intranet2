<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/PermisoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../seguridad/ModuloBLL.php';
class PermisoBLL {
    
    public function GetAll() {
        $db = new PermisoDAL();
        return $db->getAll();
    }
    public function GetAllByUserId($UserId) {
        $db = new PermisoDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function GetAllByLiderUsuarioId($UserId) {
        $db = new PermisoDAL();
        return $db->GetAllByLiderUsuarioId($UserId);
    }
    
    public function CreatePermiso($Permiso) {
        $db = new PermisoDAL();
        $c = $db->GetPermiso($Permiso[0]->State);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreatePermiso($this->MAPToArray($Permiso));
    }
    
    public function AsignarPermisoUsuario($list) {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new PermisoDAL();
        $c = $db->GetUsuarioPermisoById($list[0]->PermisoId,  $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverPermisoUsuario($list[0]->PermisoId,  $list[0]->UsuarioId);
            return 0;
        }else{
            $db->AsignarPermisoUsuario($this->MAPToArray3($list));
            return 1;
        }
    }
    
    public function AsignarPermisoUsuarioH($list) {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new PermisoDAL();
        $c = $db->GetUsuarioPermisoById($list[0]->PermisoId,  $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverPermisoUsuario($list[0]->PermisoId,  $list[0]->UsuarioId);
            return 0;
        }else{
            $db->AsignarPermisoUsuario([array(
                'PermisoId' => $list[0]->PermisoId,
                'UsuarioId' => $list[0]->UsuarioId,
                'UOId' => $list[0]->UOId,
                'IsPH' => 1,
            )]);
            return 1;
        }
    }
    
    public function UpdatePermiso($list, $id) {
        $db = new PermisoDAL();
        return $db->UpdatePermiso($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Tipo' => $list[$index]->Tipo,
                'State' => $list[$index]->State,
                'label' => $list[$index]->label,
                'ModuloId' => $list[$index]->ModuloId,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Tipo' => $list[$index]->Tipo,
                'State' => $list[$index]->State,
                'label' => $list[$index]->label,
                'ModuloId' => $list[$index]->ModuloId,
                'ModifiedBy' => $list[$index]->ModifiedBy,
                'ModifiedAt' => date("Y-m-d"),
            ));
        }
        return $list2;
    }
    public function MAPToArray3($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'PermisoId' => $list[$index]->PermisoId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
