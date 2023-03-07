<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/ServicioDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/SedeBLL.php';

class ServicioBLL {
    
    public function GetAll() {
        $db = new ServicioDAL();
        return $db->getAll();
    }
    public function GetAllBySedeAndUserId($SedeId, $UserId) {
        $db = new ServicioDAL();
        return $db->GetAllBySedeAndUserId($SedeId, $UserId);
    }
    public function GetServicioByPersonaId($UsuarioId, $ServicioId) {
        $db = new ServicioDAL();
        return $db->GetServicioByPersonaId($UsuarioId, $ServicioId);
    }
    public function GetAllBySede($SedeId) {
        $db = new ServicioDAL();
        return $db->getAllBySede($SedeId);
    }
    public function GetAllBySedeByTA($SedeId, $UsuarioId, $TA) {
        $db = new ServicioDAL();
        if(strtolower($TA) == "biomedicos"){
            return $db->getAllBySedeBIOMEDICOS($SedeId, $UsuarioId);
        }else{
            return $db->GetAllBySedeAndUserId($SedeId, $UsuarioId);
        }
    }
    public function GetAllByUserId($UserId) {
        $db = new ServicioDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetAllByUserIdRepuesto($UserId)
    {
        $db = new ServicioDAL();
        return $db->GetAllByUserIdRepuesto($UserId);
    }

    public function GetAllByLiderUsuarioId($UserId) {
        $db = new ServicioDAL();
        return $db->GetAllByLiderUsuarioId($UserId);
    }
    public function getAllByFormatoId($FormatoId) {
        $db = new ServicioDAL();
        return $db->getAllByFormatoId($FormatoId);
    }
    public function CreateServicio($Servicio) {
        $db = new ServicioDAL();
        $c = $db->GetServicio($Servicio[0]->Nombre);
        if ($c) {
            return 'Este servicio ya se encuentra registrado en la base de datos';
        }
        return $db->CreateServicio($this->MAPToArray($Servicio));
    }
    
    public function AsignarServicioUsuariolite($list) {
        $db = new SedeBLL();
        $db = new ServicioDAL();
        $db->CreateServicioUsuario($this->MAPToArray3($list));
        return 1;
    }
    public function AsignarServicioUsuario($list) {
        $db = new SedeBLL();
        $db->CreateSedeUsuario($list);
        $db = new ServicioDAL();
        $c = $db->getServicioUsuario($list[0]->ServicioId,  $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverServicioUsuario($list[0]->ServicioId,  $list[0]->UsuarioId);
            return 0;
        }else{
            $db->CreateServicioUsuario($this->MAPToArray3($list));
            return 1;
        }
    }
    
    public function AsignarFormatoServicio($list) {
        $db = new SedeBLL();
        $db->CreateSedeUsuario($list);
        $db = new ServicioDAL();
        $c = $db->getFormatoServicio($list[0]->ServicioId,  $list[0]->FormatoId);
        if ($c != NULL) {
            $db->RemoverFormatoServicio($list[0]->ServicioId,  $list[0]->FormatoId);
            return 0;
        }else{
            $db->CreateFormatoServicio($this->MAPToArray4($list));
            return 1;
        }
    }
    
    public function UpdateServicio($list, $id) {
        $db = new ServicioDAL();
        return $db->UpdateServicio($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Piso' => $list[$index]->Piso,
                'SedeId' => $list[$index]->SedeId,
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
                'Piso' => $list[$index]->Piso,
                'SedeId' => $list[$index]->SedeId,
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
                'ServicioId' => $list[$index]->ServicioId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }
    
    public function MAPToArray4($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'ServicioId' => $list[$index]->ServicioId,
                'FormatoId' => $list[$index]->FormatoId,
                'CreatedBy' => $list[$index]->CreatedBy,
            ));
        }
        return $list2;
    }
}
