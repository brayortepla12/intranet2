<?php
/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__). '/../../DAL/seguridad/ModuloDAL.php';
class ModuloBLL {
    
    public function GetModulos() {
        $db = new ModuloDAL();
        return $db->GetModulos();
    }
    
    public function GetById($id) {
        $db = new ModuloDAL();
        return $db->GetById($id);
    }
    
    public function GetModulosByUserId($id) {
        $db = new ModuloDAL();
        return $db->GetModulosByUserId($id);
    }
    
    public function GetModulosByLiderUsuarioId($id) {
        $db = new ModuloDAL();
        return $db->GetModulosByLiderUsuarioId($id);
    }
    
    public function AsignarModuloUsuario($ModuloId, $UsuarioId, $CreatedBy) {
        $db = new ModuloDAL();
        return $db->AsignarModuloUsuario($this->MAPToArray($ModuloId, $UsuarioId, $CreatedBy));
    }
    
    public function MAPToArray($ModuloId, $UsuarioId, $CreatedBy) {
        $list2 = Array();
            array_push($list2, Array(
                'ModuloId' => $ModuloId,
                'UsuarioId' => $UsuarioId,
                'CreatedBy' => $CreatedBy,
            ));
        return $list2;
    }
    
    private function Mapper($Method){
        $m = new ModuloDTO();
        if (isset ( $Method["ModuloId"] )) {
            $m->Nombre = $Method["ModuloId"];
        }
        if (isset ( $Method["Nombre"] )) {
            $m->Nombre = $Method["Nombre"];
        }
        if (isset ( $Method["url"] )) {
            $m->url = $Method["url"];
        }
        return $m;
    }
}
