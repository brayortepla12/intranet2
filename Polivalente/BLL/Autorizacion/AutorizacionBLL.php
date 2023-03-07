<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/Autorizacion/AutorizacionDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

class AutorizacionBLL {

    public function GetAutorizaciones($Dia, $Mes, $Year) {
        $db = new AutorizacionDAL();
        return $db->GetAutorizaciones($Dia, $Mes, $Year);
    }

    public function GetItemProtocoloById($ProtocoloId) {
        $db = new AutorizacionDAL();
        return $db->GetItemProtocoloById($ProtocoloId);
    }
    
    public function GetAllByPlantilla($ServicioId, $UserId) {
        $db = new AutorizacionDAL();
        return $db->GetAllByPlantilla($ServicioId, $UserId);
    }
    
    

    public function GetAllBySedeId($UserId) {
        $db = new AutorizacionDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeId($s->SedeId));
        }
        return $listado;
    }

    public function GetAllById($AutorizacionId) {
        $db = new AutorizacionDAL();
        return $db->getAllById($AutorizacionId);
    }

    public function CreateProtocoloAutorizacion($Protocolo) {
        $db = new AutorizacionDAL();
        $id = $db->CreateProtocoloAutorizacion($this->MAPToProtocoloAutorizacion($Protocolo));
        if (count($id) > 0) {
            $this->CreateItemProtocoloAutorizacion($Protocolo->Items, $id[0]);
        }
        return $id;
    }
    
    public function CreateItemProtocoloAutorizacion($Items,$ProtocoloId) {
        $db = new AutorizacionDAL();
        $id = $db->CreateItemProtocoloAutorizacion($this->MAPToItemProtocoloAutorizacion($Items,$ProtocoloId));
        
        return $id;
    }

    public function AsignarAutorizacionUsuario($list) {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new AutorizacionDAL();
        $c = $db->GetUsuarioAutorizacionById($list[0]->AutorizacionId, $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverAutorizacionUsuario($list[0]->AutorizacionId, $list[0]->UsuarioId);
            return 0;
        } else {
            $db->AsignarAutorizacionUsuario($this->MAPToArray3($list));
            return 1;
        }
    }

    public function DeleteAutorizacion($PedidoAmlacenId) {
        $db = new AutorizacionDAL();
        $Pedido = $this->GetAllById($PedidoAmlacenId);
        return $db->UpdateAutorizacion($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function UpdateAutorizacion($list) {
        $db = new AutorizacionDAL();
        return $db->UpdateAutorizacion($this->MAPToUpdate($list), $list->AutorizacionId);
    }
    
    public function MAPToProtocoloAutorizacion($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombre' => $list->Nombre,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }
    
    public function MAPToItemProtocoloAutorizacion($list, $ProtocoloId) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Nombre' => $list[$index]->Destinatario,
                'Email' => $list[$index]->Email,
                'Tiempo' => $list[$index]->Tiempo,
                'Orden' => $index,
                'Estado' => $list[$index]->Estado,
                'ProtocoloId' => $ProtocoloId,
                'CreatedBy' => $list[$index]->CreatedBy
            ));
        }
        
        return $list2;
    }
    
    

    public function MAPToArray($list) {
        $list2 = Array();
        array_push($list2, Array(
            'NombreKrystalos' => $list->NombreKrystalos,
            'CodigoKrystalos' => $list->CodigoKrystalos,
            'Nombre' => $list->Nombre,
            'GrupoId' => $list->GrupoId,
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
