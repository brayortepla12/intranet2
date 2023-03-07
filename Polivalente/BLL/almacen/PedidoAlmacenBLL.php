<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/almacen/PedidoAlmacenDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';
require_once dirname(__FILE__) . '/../../BLL/seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/ItemPedidoBLL.php';
require_once dirname(__FILE__) . '/../../DAL/formulario/SolicitudDAL.php';

class PedidoAlmacenBLL
{
    /**
     * Esta funcion obtienelas estadisticas
     * http://localhost:8080/Polivalente/api/PedidoAlmacen.php?Desde=2020-10-01%2000:00&Hasta=2021-02-05%2010:00&UsuarioId=205
     *
     * @param string $FF
     * @param string $FT
     * @param string $UsuarioId
     * @return void
     */
    public function getEstadisticasByUsuarioId(string $FF, string $FT, string $UsuarioId): string
    {
        $db = new PedidoAlmacenDAL();
        $Data =  $db->Search("SELECT * FROM polivalente.pedidoalmacen where SolicitanteId = $UsuarioId AND FechaSolicitud >= '$FF' AND FechaSolicitud <= '$FT';");
        $Filas = "";
        foreach ($Data as $d) {
            $Articulos = json_decode($d->Items);
            foreach ($Articulos as $a) {
                if ($a->CantidadEntregada > 0) {
                    $Filas .= "<tr>
            <td>$d->PedidoAlmacenId</td>
            <td>$d->FechaSolicitud</td>
            <td>$d->NombreSolicitante</td>
            <td>$a->Articulo</td>
            <td>$a->CantidadEntregada</td>
          </tr>";
                }
            }
        }
        return "<style>
    td,th {
      border: 1px solid black; 
      border-collapse: collapse;
    }
    </style>
    <table style='border: 1px solid black; border-collapse: collapse;'>
      <tr>
        <th>ID</th>
        <th>Fecha Pedido</th>
        <th>Responsable</th>
        <th>Articulo</th>
        <th>Cantidad Entregada</th>
      </tr>
      $Filas
    </table>";
    }

    public function GetAll()
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAll();
    }

    public function GetAllByUserId($UserId)
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAllByUserId($UserId);
    }

    public function GetAllByUserIdVer2($SolicitanteId, $Tipo)
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAllByUserIdVer2($SolicitanteId, $Tipo);
    }

    public function GetAllByUserIdVer2Repuesto($SolicitanteId, $Tipo)
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAllByUserIdVer2Repuesto($SolicitanteId, $Tipo);
    }

    public function GetAllBySedeId(string $UserId, string $Estado): array
    {
        $db = new PedidoAlmacenDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeId($s->SedeId, $Estado));
        }
        return $listado;
    }

    public function GetAllBySedeIdVer2($UserId, $Estado, $Tipo)
    {
        $db = new PedidoAlmacenDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeIdVer2($s->SedeId, $Estado, $Tipo));
        }
        return $listado;
    }

    public function GetAllBySedeIdVer2Pedido($UserId, $Estado, $Tipo)
    {
        $db = new PedidoAlmacenDAL();
        $hs = new SedeBLL();
        $list = json_decode($hs->GetAllByUserId($UserId));
        $listado = array();
        foreach ($list as $s) {
            $listado = array_merge($listado, $db->getAllBySedeIdVer2Pedido($s->SedeId, $Estado, $Tipo));
        }
        return $listado;
    }

    public function GetAllById($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAllById($PedidoAlmacenId);
    }

    public function GetPedidoById_sm($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        return $db->GetPedidoById_sm($PedidoAlmacenId);
    }

    public function getAllByIdVer2($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        return $db->getAllByIdVer2($PedidoAlmacenId);
    }

    public function GetAllItemsById($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        return $db->GetAllItemsById($PedidoAlmacenId);
    }

    public function GetAllItemsByIdAdmin($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        $pedido = $db->GetAllItemsById($PedidoAlmacenId);
        if (count($pedido) > 0) {
            $this->SetPedidoAlmacenIsLeido($PedidoAlmacenId);
        }
        return $pedido;
    }

    public function SetPedidoAlmacenIsLeido($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        $db->UpdatePedidoAlmacenVer2([array(
            'IsLeido' => 1,
        )], $PedidoAlmacenId);
    }

    /*------ REPUESTOS ------*/

    public function GetAllItemsByIdAdminRepuesto($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        $pedido = $db->GetAllItemsByIdRepuesto($PedidoAlmacenId);
        if (count($pedido) > 0) {
            $this->SetPedidoAlmacenIsLeidoRepuesto($PedidoAlmacenId);
        }
        return $pedido;
    }

    public function SetPedidoAlmacenIsLeidoRepuesto($PedidoAlmacenId)
    {
        $db = new PedidoAlmacenDAL();
        $db->UpdatePedidoAlmacenVer2Repuesto([array(
            'IsLeido' => 1,
        )], $PedidoAlmacenId);
    }

    public function DeletePedidoAlmacenVer2Repuesto($PedidoAmlacenId)
    {
        $db = new PedidoAlmacenDAL();
        $Pedido = $this->getAllByIdVer2($PedidoAmlacenId);
        return $db->UpdatePedidoAlmacenVer2Repuesto($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function UpdatePedidoAlmacenVer2Repuesto($list)
    {
        $db = new PedidoAlmacenDAL();
        $uh = new UsuarioBLL();
        $ih = new ItemPedidoBLL();
        $Helper = new EmpresaBLL();
        $EmpresaObj = $Helper->GetEmpresa();
        $hs = new sendMail();
        $Contenido = '<p>Hay una actualizacion en la solicitud N° ' . $list->PedidoAlmacenId . ' </p><br>';
        $filas = "";
        $cont = 1;
        foreach ($list->Items as $pa) {
            $ih->UpdateItemPedido($pa);
            if ($pa->CantidadSolicitada > 0) {
                $filas .= '<tr align="CENTER">
                                <th colspan="3">' . $cont . '</th>
                                <th colspan="4">' . $pa->Nombre . '</th>
                                <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                                <th colspan="2">' . $pa->CantidadEntregada . '</th>
                                <th>' . $pa->Pendiente . '</th>
                             </tr>';
                $cont++;
            }
        }
        $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="3">ITEM</th>
                           <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                           <th colspan="2">CANTIDAD SOLICITADA</th>
                           <th colspan="2">CANTIDAD ENTREGADA</th>
                           <th>PENDIENTE</th>
                        </tr>
                        ' . $filas . '
                    </table>';
        if ($list->Estado === 'Recibir') {
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido -- ALMACEN", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
        } else {
            $u = $uh->GetUsuarioById($list->SolicitanteId);
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido -- ALMACEN", $Contenido . $tabla, $u->Email, $u->NombreCompleto);
        }
        $sh = new SolicitudDAL();
        $sol = $sh->GetSolIfExistByPedido2($list->PedidoAlmacenId);
        if (count($sol) > 0) {
            $id = $sh->CreateEventoSolicitudPol([
                array(
                    'NombreUsuario' => $list->ModifiedBy,
                    'NombreBreveEvento' => "{$list->Estado} Pedido",
                    'SolicitudId' => $sol[0]->SolicitudId,
                    'TipoEvento' => "{$list->Estado} Pedido",
                    'Descripcion' => "{$list->Estado} Pedido",
                    'TecnicoResponsable' => $list->ModifiedBy,
                    'FechaEvento' => $this->getDatetimeNow(),
                    'CreatedAt' => $this->getDatetimeNow()
                )
            ], $sol[0]->Tipo);
            if ($list->Estado == "Recibir") {
                $sh->UpdateSolicitudPol([
                    array(
                        'IsFinalizada' => 1,
                        'FechaFinalizacion' => $this->getDatetimeNow(),
                        'NombreUsuarioFinaliza' => $list->ModifiedBy,
                        'ModifiedAt' => $this->getDatetimeNow()
                    )
                ], $sol[0]->SolicitudId, $sol[0]->Tipo);
            }
        }
        return $db->UpdatePedidoAlmacenVer2Repuesto($this->MAPToUpdateVer2Repuesto($list), $list->PedidoAlmacenId);
    }


    /* --------------------------- */


    public function CreatePedidoAlmacen($PedidoAlmacen)
    {
        // sin plantilla
        $db = new PedidoAlmacenDAL();
        if (count($PedidoAlmacen->Items) > 0) {
            $p = $db->CreatePedidoAlmacen($this->MAPToArray($PedidoAlmacen));
            if (is_array($p)) {
                $Helper = new EmpresaBLL();
                $EmpresaObj = $Helper->GetEmpresa();
                $hs = new sendMail();
                $Contenido = '<p>Hay una nueva solicitud N° ' . $p[0] . '</p><br>';
                $filas = "";
                $cont = 1;
                foreach ($PedidoAlmacen->Items as $pa) {
                    if ($pa->CantidadSolicitada > 0) {
                        $filas .= '<tr align="CENTER">
                            <th colspan="3">' . $cont . '</th>
                            <th colspan="4">' . $pa->Articulo . '</th>
                            <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                            <th colspan="2">' . isset($pa->CantidadEntregada) ? $pa->CantidadEntregada : 0 . '</th>
                            <th>' . $pa->Pendiente . '</th>
                         </tr>';
                        $cont++;
                    }
                }
                $tabla = '<table border="1">
                    <tr align="CENTER">
                       <th colspan="3">ITEM</th>
                       <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                       <th colspan="2">CANTIDAD SOLICITADA</th>
                       <th colspan="2">CANTIDAD ENTREGADA</th>
                       <th>PENDIENTE</th>
                    </tr>
                    ' . $filas . '
                </table>';
                $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
            }
            return $p;
        } else {
            return "No puede crear un pedido en blanco";
        }
    }

    public function CreatePedidoAlmacen2($PedidoAlmacen)
    {
        // con plantilla
        $db = new PedidoAlmacenDAL();
        $isRepuesto = 0;
        if (count($PedidoAlmacen->Items) > 0) {
            $Pendientes = $db->getPENDIENTES_2($PedidoAlmacen->SolicitanteId, $PedidoAlmacen->ServicioId, $PedidoAlmacen->TipoPedido);
            if (count($Pendientes) == 0) {
                $p = $db->CreatePedidoAlmacen2($this->MAPToArrayVer2($PedidoAlmacen));
                if (is_array($p)) {
                    $Helper = new EmpresaBLL();
                    $EmpresaObj = $Helper->GetEmpresa();
                    $hs = new sendMail();
                    $Contenido = '<p>Hay una nueva solicitud N° ' . $p[0] . '</p><br>';
                    $filas = "";
                    $cont = 1;
                    $iph = new ItemPedidoBLL();
                    foreach ($PedidoAlmacen->Items as $pa) {
                        //create item articulo
                        $pa->PedidoAlmacenId = $p[0];
                        $pa->CreatedBy = $PedidoAlmacen->CreatedBy;
                        $iph->CreateItemPedido($pa, $isRepuesto);
                        if ($pa->CantidadSolicitada > 0) {
                            $filas .= '<tr align="CENTER">
                                <th colspan="3">' . $cont . '</th>
                                <th colspan="4">' . $pa->Nombre . '</th>
                                <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                                <th colspan="2">' . isset($pa->CantidadEntregada) ? $pa->CantidadEntregada : 0 . '</th>
                                <th>' . $pa->Pendiente . '</th>
                             </tr>';
                            $cont++;
                        }
                    }
                    $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="3">ITEM</th>
                           <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                           <th colspan="2">CANTIDAD SOLICITADA</th>
                           <th colspan="2">CANTIDAD ENTREGADA</th>
                           <th>PENDIENTE</th>
                        </tr>
                        ' . $filas . '
                    </table>';
                    if (property_exists($PedidoAlmacen, "TipoPedido")) {
                        if ($PedidoAlmacen->TipoPedido == "Almacen") {
                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido Almacen", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
                            //                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido Almacen", $Contenido . $tabla, "zlinker89@gmail.com", "frank");
                        } else if ($PedidoAlmacen->TipoPedido == "Central") {
                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido CENTRAL", $Contenido . $tabla, "abolano@clinicaintegral.com.co", "Alexa");
                            //                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido CENTRAL", $Contenido . $tabla, "ospi89@hotmail.com", "frank");
                        }
                    } else {
                        $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
                    }
                }
                return $p;
            } else {
                return "Debes de notificar que recibiste los pedidos que estan en estado Entregar.";
            }
        } else {
            return "No puede crear un pedido en blanco";
        }
    }

    public function CreatePedidoAlmacenRepuesto($PedidoAlmacen)
    {
        // con plantilla
        $db = new PedidoAlmacenDAL();
        $isRepuesto = 1;
        if (count($PedidoAlmacen->Items) > 0) {
            $Pendientes = $db->getPENDIENTES_2($PedidoAlmacen->SolicitanteId, $PedidoAlmacen->ServicioId, $PedidoAlmacen->TipoPedido);
            if (count($Pendientes) == 0) {
                $p = $db->CreatePedidoAlmacenRepuesto($this->MAPToArrayVerRepuesto($PedidoAlmacen));
                if (is_array($p)) {
                    $Helper = new EmpresaBLL();
                    $EmpresaObj = $Helper->GetEmpresa();
                    $hs = new sendMail();
                    $Contenido = '<p>Hay una nueva solicitud N° ' . $p[0] . '</p><br>';
                    $filas = "";
                    $cont = 1;


                    $iph = new ItemPedidoBLL();
                    foreach ($PedidoAlmacen->Items as $pa) {
                        $dirigidoa = 0;
                        //create item articulo
                        $pa->PedidoAlmacenId = $p[0];
                        foreach ($PedidoAlmacen->RalacionArticServicio as $RAS) {
                            if (($pa->Nombre == $RAS->nombreArticulo || $pa->NombreKrystalos == $RAS->nombreArticulo) && $pa->CantidadSolicitada > 0) {
                                $dirigidoa = $RAS->servicioID;
                                break;
                            }
                        }
                        $pa->CreatedBy = $PedidoAlmacen->CreatedBy;
                        $iph->CreateItemPedidoRepuesto($pa, $isRepuesto, $dirigidoa);
                        if ($pa->CantidadSolicitada > 0) {
                            $filas .= '<tr align="CENTER">
                                <th colspan="3">' . $cont . '</th>
                                <th colspan="4">' . $pa->Nombre . '</th>
                                <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                                <th colspan="2">' . isset($pa->CantidadEntregada) ? $pa->CantidadEntregada : 0 . '</th>
                                <th>' . $pa->Pendiente . '</th>
                             </tr>';
                            $cont++;
                        }
                    }
                    $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="3">ITEM</th>
                           <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                           <th colspan="2">CANTIDAD SOLICITADA</th>
                           <th colspan="2">CANTIDAD ENTREGADA</th>
                           <th>PENDIENTE</th>
                        </tr>
                        ' . $filas . '
                    </table>';
                    if (property_exists($PedidoAlmacen, "TipoPedido")) {
                        if ($PedidoAlmacen->TipoPedido == "Almacen") {
                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido Almacen", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
                            //                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido Almacen", $Contenido . $tabla, "zlinker89@gmail.com", "frank");
                        } else if ($PedidoAlmacen->TipoPedido == "Central") {
                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido CENTRAL", $Contenido . $tabla, "abolano@clinicaintegral.com.co", "Alexa");
                            //                            $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido CENTRAL", $Contenido . $tabla, "ospi89@hotmail .com", "frank");
                        }
                    } else {
                        $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud de pedido", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
                    }
                }
                return $p;
            } else {
                return "Debes de notificar que recibiste los pedidos que estan en estado Entregar.";
            }
        } else {
            return "No puede crear un pedido en blanco";
        }
    }

    public function AsignarPedidoAlmacenUsuario($list)
    {
        $db = new ModuloBLL();
        $m = $db->GetById($list[0]->ModuloId);
        if ($m == NULL) {
            $db->AsignarModuloUsuario($list[0]->ModuloId, $list[0]->UsuarioId, $list[0]->CreatedBy);
        }
        $db = new PedidoAlmacenDAL();
        $c = $db->GetUsuarioPedidoAlmacenById($list[0]->PedidoAlmacenId, $list[0]->UsuarioId);
        if ($c != NULL) {
            $db->RemoverPedidoAlmacenUsuario($list[0]->PedidoAlmacenId, $list[0]->UsuarioId);
            return 0;
        } else {
            $db->AsignarPedidoAlmacenUsuario($this->MAPToArray3($list));
            return 1;
        }
    }

    public function DeletePedidoAlmacen($PedidoAmlacenId)
    {
        $db = new PedidoAlmacenDAL();
        $Pedido = $this->GetAllById($PedidoAmlacenId);
        return $db->UpdatePedidoAlmacen($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function DeletePedidoAlmacenVer2($PedidoAmlacenId)
    {
        $db = new PedidoAlmacenDAL();
        $Pedido = $this->getAllByIdVer2($PedidoAmlacenId);
        return $db->UpdatePedidoAlmacenVer2($this->MAPToDelete($Pedido[0]), $PedidoAmlacenId);
    }

    public function UpdatePedidoAlmacen($list)
    {
        $db = new PedidoAlmacenDAL();
        $uh = new UsuarioBLL();
        $Helper = new EmpresaBLL();
        $EmpresaObj = $Helper->GetEmpresa();
        $hs = new sendMail();
        $Contenido = '<p>Hay una actualizacion en la solicitud N° ' . $list->PedidoAlmacenId . ' </p><br>';
        $filas = "";
        $cont = 1;
        foreach ($list->Items as $pa) {
            if ($pa->CantidadSolicitada > 0) {
                $filas .= '<tr align="CENTER">
                                <th colspan="3">' . $cont . '</th>
                                <th colspan="4">' . $pa->Articulo . '</th>
                                <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                                <th colspan="2">' . $pa->CantidadEntregada . '</th>
                                <th>' . $pa->Pendiente . '</th>
                             </tr>';
                $cont++;
            }
        }
        $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="3">ITEM</th>
                           <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                           <th colspan="2">CANTIDAD SOLICITADA</th>
                           <th colspan="2">CANTIDAD ENTREGADA</th>
                           <th>PENDIENTE</th>
                        </tr>
                        ' . $filas . '
                    </table>';
        if ($list->Estado === 'Recibir') {
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
        } else {
            $u = $uh->GetUsuarioById($list->SolicitanteId);
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido", $Contenido . $tabla, $u->Email, $u->NombreCompleto);
        }

        return $db->UpdatePedidoAlmacen($this->MAPToUpdate($list), $list->PedidoAlmacenId);
    }

    public function UpdatePedidoAlmacenVer2($list)
    {
        $db = new PedidoAlmacenDAL();
        $uh = new UsuarioBLL();
        $ih = new ItemPedidoBLL();
        $Helper = new EmpresaBLL();
        $EmpresaObj = $Helper->GetEmpresa();
        $hs = new sendMail();
        $Contenido = '<p>Hay una actualizacion en la solicitud N° ' . $list->PedidoAlmacenId . ' </p><br>';
        $filas = "";
        $cont = 1;
        foreach ($list->Items as $pa) {
            $ih->UpdateItemPedido($pa);
            if ($pa->CantidadSolicitada > 0) {
                $filas .= '<tr align="CENTER">
                                <th colspan="3">' . $cont . '</th>
                                <th colspan="4">' . $pa->Nombre . '</th>
                                <th colspan="2">' . $pa->CantidadSolicitada . '</th>
                                <th colspan="2">' . $pa->CantidadEntregada . '</th>
                                <th>' . $pa->Pendiente . '</th>
                             </tr>';
                $cont++;
            }
        }
        $tabla = '<table border="1">
                        <tr align="CENTER">
                           <th colspan="3">ITEM</th>
                           <th colspan="4">ARTICULO Y/O PRODUCTO:</th>
                           <th colspan="2">CANTIDAD SOLICITADA</th>
                           <th colspan="2">CANTIDAD ENTREGADA</th>
                           <th>PENDIENTE</th>
                        </tr>
                        ' . $filas . '
                    </table>';
        if ($list->Estado === 'Recibir') {
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido -- ALMACEN", $Contenido . $tabla, "almacencield@gmail.com", "Atilio");
        } else {
            $u = $uh->GetUsuarioById($list->SolicitanteId);
            $hs->EnviarEmail_Notificacion($EmpresaObj, "Actualización de la solicitud de pedido -- ALMACEN", $Contenido . $tabla, $u->Email, $u->NombreCompleto);
        }
        $sh = new SolicitudDAL();
        $sol = $sh->GetSolIfExistByPedido2($list->PedidoAlmacenId);
        if (count($sol) > 0) {
            $id = $sh->CreateEventoSolicitudPol([
                array(
                    'NombreUsuario' => $list->ModifiedBy,
                    'NombreBreveEvento' => "{$list->Estado} Pedido",
                    'SolicitudId' => $sol[0]->SolicitudId,
                    'TipoEvento' => "{$list->Estado} Pedido",
                    'Descripcion' => "{$list->Estado} Pedido",
                    'TecnicoResponsable' => $list->ModifiedBy,
                    'FechaEvento' => $this->getDatetimeNow(),
                    'CreatedAt' => $this->getDatetimeNow()
                )
            ], $sol[0]->Tipo);
            if ($list->Estado == "Recibir") {
                $sh->UpdateSolicitudPol([
                    array(
                        'IsFinalizada' => 1,
                        'FechaFinalizacion' => $this->getDatetimeNow(),
                        'NombreUsuarioFinaliza' => $list->ModifiedBy,
                        'ModifiedAt' => $this->getDatetimeNow()
                    )
                ], $sol[0]->SolicitudId, $sol[0]->Tipo);
            }
        }
        return $db->UpdatePedidoAlmacenVer2($this->MAPToUpdateVer2($list), $list->PedidoAlmacenId);
    }

    public function MAPToArray($list)
    {
        $list2 = array();
        array_push($list2, array(
            'FechaSolicitud' => $this->getDatetimeNow(),
            'NombreSolicitante' => $list->NombreSolicitante,
            'CargoSolicitante' => $list->CargoSolicitante,
            'Items' => json_encode($list->Items),
            'FechaEntrega' => $list->FechaEntrega,
            'FechaRecibe' => $list->FechaRecibe,
            'SolicitanteId' => $list->SolicitanteId,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Observacion' => $list->Observacion,
            'NombreRecibe' => $list->NombreRecibe,
            'NombreEntrega' => $list->NombreEntrega,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArrayVer2($list)
    {
        $list2 = array();
        array_push($list2, array(
            'FechaSolicitud' => $this->getDatetimeNow(),
            'NombreSolicitante' => $list->NombreSolicitante,
            'CargoSolicitante' => $list->CargoSolicitante,
            'FechaEntrega' => $list->FechaEntrega,
            'FechaRecibe' => $list->FechaRecibe,
            'SolicitanteId' => $list->SolicitanteId,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Observacion' => $list->Observacion,
            'NombreRecibe' => $list->NombreRecibe,
            'NombreEntrega' => $list->NombreEntrega,
            'Bodega' => $list->TipoPedido,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArrayVerRepuesto($list)
    {
        $list2 = array();
        array_push($list2, array(
            'FechaSolicitud' => $this->getDatetimeNow(),
            'NombreSolicitante' => $list->NombreSolicitante,
            'CargoSolicitante' => $list->CargoSolicitante,
            'FechaEntrega' => $list->FechaEntrega,
            'FechaRecibe' => $list->FechaRecibe,
            'SolicitanteId' => $list->SolicitanteId,
            'SedeId' => $list->SedeId,
            'ServicioId' => $list->ServicioId,
            'Observacion' => $list->Observacion,
            'NombreRecibe' => $list->NombreRecibe,
            'NombreEntrega' => $list->NombreEntrega,
            'Bodega' => $list->TipoPedido,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToUpdate($list)
    {
        $list2 = array();
        if ($list->Estado == 'Entregar') {
            array_push($list2, array(
                'Items' => json_encode($list->Items),
                'Estado' => $list->Estado,
                'FechaEntrega' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'NombreEntrega' => $list->ModifiedBy,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else if ($list->Estado == 'Recibir') {
            array_push($list2, array(
                'Items' => json_encode($list->Items),
                'Estado' => $list->Estado,
                'NombreRecibe' => $list->ModifiedBy,
                'FechaRecibe' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else {
            array_push($list2, array(
                'Items' => json_encode($list->Items),
                'Estado' => $list->Estado,
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        }

        return $list2;
    }

    public function MAPToUpdateVer2($list)
    {
        $list2 = array();
        if ($list->Estado == 'Entregar') {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'FechaEntrega' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'NombreEntrega' => $list->ModifiedBy,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else if ($list->Estado == 'Recibir') {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'NombreRecibe' => $list->ModifiedBy,
                'FechaRecibe' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        }

        return $list2;
    }

    public function MAPToUpdateVer2Repuesto($list)
    {
        $list2 = array();
        if ($list->Estado == 'Entregar') {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'FechaEntrega' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'NombreEntrega' => $list->ModifiedBy,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else if ($list->Estado == 'Recibir') {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'NombreRecibe' => $list->ModifiedBy,
                'FechaRecibe' => $this->getDatetimeNow(),
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        } else {
            array_push($list2, array(
                'Estado' => $list->Estado,
                'Observacion' => $list->Observacion,
                'ModifiedBy' => $list->ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow(),
            ));
        }

        return $list2;
    }

    public function MAPToDelete($list)
    {
        $list2 = array();
        array_push($list2, array(
            'Estado' => 'Inactivo',
            'ModifiedAt' => $list->ModifiedAt,
        ));
        return $list2;
    }

    function getDatetimeNow()
    {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y-m-d H:i:s');
    }
}
