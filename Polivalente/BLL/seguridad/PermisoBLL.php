<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/seguridad/PermisoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class PermisoBLL {
    
    public function GetPermisos($UserId) {
        $db = new PermisoDAL();
        return $db->getPermisoByUser($UserId);
    }
    
    public function GetALLPermisos() {
        $db = new PermisoDAL();
        return $db->getPermisos();
    }
    public function MAPToArray($list, $EnviaId, $RecibeId = NULL, $Estado = True, $Modified = NULL, $AreaId = 1) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {

            array_push($list2, Array(
                'ProcesoId' => $list[$index],
                'EnviaId' => $EnviaId,
                'Estado' => $Estado
            ));
        }
        return $list2;
    }

}
