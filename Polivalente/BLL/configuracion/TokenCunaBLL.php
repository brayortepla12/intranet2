<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/configuracion/TokenCunaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';


class TokenCunaBLL {
    
    public function GetAll() {
        $db = new TokenCunaDAL();
        return $db->getAll();
    }
    public function GetTokenCunaByTokenCunaId($TokenCunaId) {
        $db = new TokenCunaDAL();
        return $db->GetTokenCunaByTokenCunaId($TokenCunaId);
    }
    public function GetAllByUserId($UserId) {
        $db = new TokenCunaDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function CreateTokenCuna($TokenCuna) {
        $db = new TokenCunaDAL();
        $hs = new Security();
        $TokenCuna[0]->Token = $hs->GenerateToken($TokenCuna[0]->Email, "Neonatos_123456789", $TokenCuna[0]->Dias, []);
        return $db->CreateTokenCuna($this->MAPToArray($TokenCuna));
    }
    
    public function CreateTokenCunaUsuario($list) {
        $db = new TokenCunaDAL();
        $c = $db->GetTokenCunaByUsuario($list[0]->TokenCunaId,$list[0]->UsuarioId);
        if ($c == NULL) {
            return $db->CreateTokenCunaUsuario($this->MAPToArray3($list));
        }
    }
    
    public function UpdateTokenCuna($list, $id) {
        $db = new TokenCunaDAL();
        return $db->UpdateTokenCuna($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'Nombres' => $list[$index]->Nombres,
                'Email' => $list[$index]->Email,
                'Dias' => $list[$index]->Dias,
                'Token' => $list[$index]->Token,
                'CunaId' => $list[$index]->CunaId,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            
            array_push($list2, Array(
                'Nombres' => $list[$index]->Nombres,
                'Email' => $list[$index]->Email,
                'Dias' => $list[$index]->Dias,
                'Token' => $list[$index]->Token,
                'CunaId' => $list[$index]->CunaId,
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
                'TokenCunaId' => $list[$index]->TokenCunaId,
                'UsuarioId' => $list[$index]->UsuarioId,
            ));
        }
        return $list2;
    }

}
