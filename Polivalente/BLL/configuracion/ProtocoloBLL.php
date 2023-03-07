<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/ProtocoloDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../seguridad/ModuloBLL.php';
class ProtocoloBLL {
    
    public function GetAll() {
        $db = new ProtocoloDAL();
        return $db->getAll();
    }
    public function GetAllByUserId($UserId) {
        $db = new ProtocoloDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function CreateProtocolo($Protocolo) {
        $db = new ProtocoloDAL();
        $c = $db->GetProtocolo($Protocolo[0]->Nombre);
        if ($c) {
            return 'Este protocolo ya se encuentra registrado en la base de datos';
        }
        return $db->CreateProtocolo($this->MAPToArray($Protocolo));
    }
    
    public function AsignarProtocoloUsuario($list) {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new ProtocoloDAL();
        $c = $db->GetUsuarioProtocoloById($list[0]->ProtocoloId,  $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverProtocoloUsuario($list[0]->ProtocoloId,  $list[0]->UsuarioId);
            return 0;
        }else{
            $db->AsignarProtocoloUsuario($this->MAPToArray3($list));
            return 1;
        }
    }
    
    public function UpdateProtocolo($list, $id) {
        $db = new ProtocoloDAL();
        return $db->UpdateProtocolo($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombre' => $list[$index]->Nombre,
                'Formulario' => $list[$index]->Formulario,
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
                'Formulario' => $list[$index]->Formulario,
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
                'ProtocoloId' => $list[$index]->ProtocoloId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
