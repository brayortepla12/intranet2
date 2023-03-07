<?php

require_once dirname(__FILE__) . '/../../DAL/thc/GrupoDAL.php';
/**
 * Description of GrupoBLL
 *
 * @author DESARROLLO2
 */
class GrupoBLL {
    public function GetGrupos() {
        $helper = new GrupoDAL();
        return $helper->GetGrupos();
    }
    public function GetUsuariosByGrupoId($GrupoId) {
        $helper = new GrupoDAL();
        return $helper->GetUsuarioByGrupoId($GrupoId);
    }
}
