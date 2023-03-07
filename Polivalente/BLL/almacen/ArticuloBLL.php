<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/almacen/ArticuloDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

class ArticuloBLL {

    public function GetAll() {
        $db = new ArticuloDAL();
        return $db->getAll();
    }
    
    public function GetAllByTipo($Tipo) {
        $db = new ArticuloDAL();
        return $db->GetAllByTipo($Tipo);
    }

    public function GetAllByUserId($UserId) {
        $db = new ArticuloDAL();
        return $db->getAllByUserId($UserId);
    }
    
    public function GetAllByPlantilla($ServicioId, $UserId, $Tipo) {
        $db = new ArticuloDAL();
        return $db->GetAllByPlantilla($ServicioId, $UserId, $Tipo);
    }
    /**
     * 
     * @param type $Codigo ejemplo: Pol_704, Bio_704 ó Sis_704
     * @return $HojaVida_Lite
     */
    public function GetHojaDeVidaByCod($Codigo) {
        $db = new ArticuloDAL();
        $items = explode("_", $Codigo); // 0 es Codigo y 1 Id
        if($items[0] == "Pol"){
            return $db->GetHojaVidaPolivalente($items[1]);
        }else if($items[0] == "Sis"){
            return $db->GetHojaVidaSistemas($items[1]);
        }else if($items[0] == "Bio"){
            return $db->GetHojaVidaBiomedico($items[1]);
        }else{
            return [];
        }
    }
    

    public function GetAllBySedeId($UserId) {
        $db = new ArticuloDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeId($s->SedeId));
        }
        return $listado;
    }

    public function GetAllById($ArticuloId) {
        $db = new ArticuloDAL();
        return $db->getAllById($ArticuloId);
    }

    public function CreateArticulo($Articulo) {
        $db = new ArticuloDAL();
        $p = $db->CreateArticulo($this->MAPToArray($Articulo));
        return $p;
    }

    public function AsignarArticuloUsuario($list) {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new ArticuloDAL();
        $c = $db->GetUsuarioArticuloById($list[0]->ArticuloId, $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverArticuloUsuario($list[0]->ArticuloId, $list[0]->UsuarioId);
            return 0;
        } else {
            $db->AsignarArticuloUsuario($this->MAPToArray3($list));
            return 1;
        }
    }

    public function DeleteArticulo($PedidoAmlacenId) {
        $db = new ArticuloDAL();
        $Pedido = $this->GetAllById($PedidoAmlacenId);
        return $db->UpdateArticulo($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function UpdateArticulo($list) {
        $db = new ArticuloDAL();
        return $db->UpdateArticulo($this->MAPToUpdate($list), $list->ArticuloId);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'NombreKrystalos' => $list->NombreKrystalos,
            'CodigoKrystalos' => $list->CodigoKrystalos,
            'Nombre' => $list->Nombre,
            'GrupoId' => $list->GrupoId,
            'ArticuloPara' => $list->ArticuloPara,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list) {
        $list2 = Array();
        array_push($list2, Array(
            'NombreKrystalos' => $list->NombreKrystalos,
            'CodigoKrystalos' => $list->CodigoKrystalos,
            'Nombre' => $list->Nombre,
            'GrupoId' => $list->GrupoId,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));

        return $list2;
    }

    public function MAPToDelete($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => 'Inactivo',
            'ModifiedAt' => $list->ModifiedAt,
        ));
        return $list2;
    }

    function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }

}
