<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/almacen/ItemPedidoDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';

class ItemPedidoBLL
{

    public function GetAll()
    {
        $db = new ItemPedidoDAL();
        return $db->getAll();
    }

    public function CreateItemPedido($ItemPedido, $isRepuesto)
    {
        $db = new ItemPedidoDAL();
        $p = $db->CreateItemPedido($this->MAPToArray($ItemPedido, $isRepuesto));
        return $p;
    }

    public function CreateItemPedidoRepuesto($ItemPedido, $isRepuesto, $dirigidoa)
    {
        $db = new ItemPedidoDAL();
        $p = $db->CreateItemPedidoRepuesto($this->MAPToArrayRepuesto($ItemPedido, $isRepuesto, $dirigidoa));
        return $p;
    }

    public function UpdateItemPedido($list)
    {
        $db = new ItemPedidoDAL();
        return $db->UpdateItemPedido($this->MAPToUpdate($list), $list->ItemPedidoId);
    }

    public function MAPToArray($list, $isRepuesto)
    {
        $list2 = array();
        array_push($list2, array(
            'ArticuloId' => $list->ArticuloId,
            'CantidadSolicitada' => $list->CantidadSolicitada,
            'RelacionCostoId' => $list->RelacionCostoId,
            'CantidadEntregada' => $list->CantidadEntregada,
            'Pendiente' => $list->Pendiente,
            'isRepuesto' => $isRepuesto,
            'PedidoAlmacenId' => $list->PedidoAlmacenId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArrayRepuesto($list, $isRepuesto, $dirigidoa)
    {
        $list2 = array();
        array_push($list2, array(
            'ArticuloId' => $list->ArticuloId,
            'CantidadSolicitada' => $list->CantidadSolicitada,
            'RelacionCostoId' => $list->RelacionCostoId,
            'CantidadEntregada' => $list->CantidadEntregada,
            'Pendiente' => $list->Pendiente,
            'isRepuesto' => $isRepuesto,
            'DirigidoA' => $dirigidoa,
            'PedidoAlmacenId' => $list->PedidoAlmacenId,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list)
    {
        $list2 = array();
        array_push($list2, array(
            'CantidadSolicitada' => $list->CantidadSolicitada,
            'CantidadEntregada' => $list->CantidadEntregada,
            'Pendiente' => $list->CantidadSolicitada - $list->CantidadEntregada,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => $this->getDatetimeNow(),
        ));
        return $list2;
    }

    function getDatetimeNow()
    {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ H:i:s');
    }
}
