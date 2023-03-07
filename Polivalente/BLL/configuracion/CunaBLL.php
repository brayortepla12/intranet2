<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/CunaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';


class CunaBLL {
    
    public function GetAll() {
        $db = new CunaDAL();
        return $db->getAll();
    }
    public function GetCunaByCunaId($CunaId) {
        $db = new CunaDAL();
        return $db->GetCunaByCunaId($CunaId);
    }
    public function GetAllByUserId($UserId) {
        $db = new CunaDAL();
        return $db->getAllByUserId($UserId);
    }
    public function GetAllByToken($Token) {
        $db = new CunaDAL();
        return $db->GetAllByToken($Token);
    }
    public function GetAllByNombre($Nombre) {
        $db = new CunaDAL();
        return $db->getAllByNombre($Nombre);
    }
    
    public function CreateCuna($Cuna) {
        $db = new CunaDAL();
        $c = $db->GetCuna($Cuna[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateCuna($this->MAPToArray($Cuna));
    }
    
    public function CreateCunaUsuario($list) {
        $db = new CunaDAL();
        $c = $db->GetCunaByUsuario($list[0]->CunaId,$list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateCunaUsuario($this->MAPToArray3($list));
        }
    }
    
    public function UpdateCuna($list, $id) {
        $db = new CunaDAL();
        return $db->UpdateCuna($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Rtsp' => $list[$index]->Rtsp,
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
                'Rtsp' => $list[$index]->Rtsp,
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
                'CunaId' => $list[$index]->CunaId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
