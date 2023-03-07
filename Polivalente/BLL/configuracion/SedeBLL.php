<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/SedeDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';


class SedeBLL {
    
    public function GetAll() {
        $db = new SedeDAL();
        return $db->getAll();
    }
    public function GetSedeBySedeId($SedeId) {
        $db = new SedeDAL();
        return $db->GetSedeBySedeId($SedeId);
    }
    public function GetAllByUserId($UserId) {
        $db = new SedeDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetAllByUserIdRepuesto($UserId)
    {
        $db = new SedeDAL();
        return $db->GetAllByUserIdRepuesto($UserId);
    }
    
    public function GetAllByLiderUsuarioId($UserId) {
        $db = new SedeDAL();
        return $db->GetAllByLiderUsuarioId($UserId);
    }
    
    public function GetAllByUserId_ta($UserId, $TA) {
        $db = new SedeDAL();
        if(strtolower($TA) === "biomedicos"){
            return $db->getAllByUserId_Biomedicos($UserId);
        }
        return $db->getAllByUserId($UserId);
    }
    
    public function CreateSede($Sede) {
        $db = new SedeDAL();
        $c = $db->GetSede($Sede[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateSede($this->MAPToArray($Sede));
    }
    
    public function CreateSedeUsuario($list) {
        $db = new SedeDAL();
        $c = $db->GetSedeByUsuario($list[0]->SedeId,$list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateSedeUsuario($this->MAPToArray3($list));
        }
    }
    
    public function UpdateSede($list, $id) {
        $db = new SedeDAL();
        return $db->UpdateSede($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Correo' => $list[$index]->Correo,
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
                'Correo' => $list[$index]->Correo,
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
                'SedeId' => $list[$index]->SedeId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
