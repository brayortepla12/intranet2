<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/GrupoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';


class GrupoBLL {
    
    public function GetAll() {
        $db = new GrupoDAL();
        return $db->getAll();
    }
    public function GetGrupoByGrupoId($GrupoId) {
        $db = new GrupoDAL();
        return $db->GetGrupoByGrupoId($GrupoId);
    }
    public function GetAllByUserId($UserId) {
        $db = new GrupoDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function IsInEnfermeria($UserId) {
        $db = new GrupoDAL();
        $gu = $db->IsInEnfermeria($UserId);
        if(count($gu) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function CreateGrupo($Grupo) {
        $db = new GrupoDAL();
        $c = $db->GetGrupo($Grupo[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateGrupo($this->MAPToArray($Grupo));
    }
    
    public function CreateGrupoUsuario($list) {
        $db = new GrupoDAL();
        $c = $db->GetGrupoByUsuario($list[0]->GrupoId,$list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateGrupoUsuario($this->MAPToArray3($list));
        }
    }
    
    public function UpdateGrupo($list, $id) {
        $db = new GrupoDAL();
        return $db->UpdateGrupo($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
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
                'GrupoId' => $list[$index]->GrupoId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
