<?php

require_once dirname(__FILE__) . '/../../DAL/ambulancia/SolicitudAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/HojaVidaAmbulanciaBLL.php';
require_once dirname(__FILE__) . '/DetallesAmbulanciaBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class SolicitudAmbulanciaBLL {
    
    public function UpdateEstadoSolicitudAmbulancia($SolicitudMantenimientoId, $Estado) {
        $Helper = new SolicitudAmbulanciaDAL();
        return $Helper->UpdateSolicitud($this->MapToUSolicitudAmbulancia($Estado), $SolicitudMantenimientoId);
    }

    public function GetFacturaBySolicitudMantenimientoId($SolicitudMantenimientoId) {
        $Helper = new SolicitudAmbulanciaDAL();
        $Factura = $Helper->GetFacturaBySolicitudMantenimientoId($SolicitudMantenimientoId);
        if (count($Factura) > 0) {
            $Factura[0]->Detalles = $Helper->GetDetallesByFacturaId($Factura[0]->FacturaId);
            return $Factura[0];
        } else {
            return "Error factura.";
        }
    }

    public function GetSolicitudAmbulancia($Year, $Mes, $Estado, $Placa) {
        $Helper = new SolicitudAmbulanciaDAL();
        return $Helper->GetSolicitudesMantenimiento($Year, $Mes, $Estado, $Placa);
    }

    public function GetItem() {
        $Helper = new SolicitudAmbulanciaDAL();
        return $Helper->GetItem();
    }

    public function GetProveedores() {
        $Helper = new SolicitudAmbulanciaDAL();
        return $Helper->GetProveedores();
    }

    public function CreateDetalleFactura($Detalles, $FacturaId) {
        $Helper = new SolicitudAmbulanciaDAL();
        $Helper->CreateDetalleFactura($this->MapToDetalleFactura($Detalles, $FacturaId));
    }
    
    public function UpdateFactura($list, $Detalles) {
        $Helper = new SolicitudAmbulanciaDAL();
        if (count($Detalles) > 0) {
            // archivo
//            if ($list->UrlArchivo != "" && is_array($list->UrlArchivo)) {
//                if (substr($list->UrlArchivo[0]->data, 0, 4) === "data") {
//                    $cadena = $list->UrlArchivo[0]->name;
//                    if (!file_exists(dirname(__FILE__) . "/../..//facturas//" . $cadena)) {
//                        $data = $list->UrlArchivo[0]->data;
//                        list($type, $data) = explode(';', $data);
//                        list(, $data) = explode(',', $data);
//                        list(, $type) = explode('/', $type);
//                        $data = base64_decode($data);
//                        if ($data === false) {
//                            return 'Archivo incorrecto.';
//                        }
//                        file_put_contents(dirname(__FILE__) . "/../..//facturas//" . $cadena, $data);
//                    } else {
//                        return "Un archivo ya existe con este mismo nombre.";
//                    }
//                }
//
//                $list->UrlArchivo = "/Polivalente/facturas/$cadena";
//            } else {
//                $list->UrlArchivo = "";
//            }
//            $id = $Helper->UpdateFactura($this->MAPToFactura($list));
//            if (count($id) > 0 && is_array($id)) {
        $Helper->DeleteDetalleFactura($list->FacturaId);
        $this->CreateDetalleFactura($Detalles, $list->FacturaId);
//                $Estado = $list->TipoSolicitud == 'INSUMO' ? 'Pedido' : 'Facturado';
//                $Helper->UpdateSolicitud($this->MapToUSolicitudAmbulancia($Estado), $list->SolicitudMantenimientoId);
//            }
        } else {
            return "Debes añadir minimo un detalle.";
        }

        return [$list->FacturaId];
    }

    public function CreateFactura($list, $Detalles) {
        $Helper = new SolicitudAmbulanciaDAL();
        $id = null;
        if (count($Detalles) > 0) {
            if (is_object($list->Proveedor) && property_exists($list->Proveedor, "originalObject")) {
                $list->ProveedorId = $list->Proveedor->originalObject->ProveedorId;
            }else{
                $list->ProveedorId = NULL;
            }

            // archivo
            if ($list->UrlArchivo != "" && is_array($list->UrlArchivo)) {
                if (substr($list->UrlArchivo[0]->data, 0, 4) === "data") {
                    $cadena = $list->UrlArchivo[0]->name;
                    if (!file_exists(dirname(__FILE__) . "/../..//facturas//" . $cadena)) {
                        $data = $list->UrlArchivo[0]->data;
                        list($type, $data) = explode(';', $data);
                        list(, $data) = explode(',', $data);
                        list(, $type) = explode('/', $type);
                        $data = base64_decode($data);
                        if ($data === false) {
                            return 'Archivo incorrecto.';
                        }
                        file_put_contents(dirname(__FILE__) . "/../..//facturas//" . $cadena, $data);
                    } else {
                        return "Un archivo ya existe con este mismo nombre.";
                    }
                }

                $list->UrlArchivo = "/Polivalente/facturas/$cadena";
            } else {
                $list->UrlArchivo = "";
            }
            $id = $Helper->CreateFactura($this->MAPToFactura($list));
            if (count($id) > 0 && is_array($id)) {
                $this->CreateDetalleFactura($Detalles, $id[0]);
                $Estado = $list->TipoSolicitud == 'INSUMO' ? 'Pedido' : 'Facturado';
                $Helper->UpdateSolicitud($this->MapToUSolicitudAmbulancia($Estado), $list->SolicitudMantenimientoId);
            }
        } else {
            return "Debes añadir minimo un detalle.";
        }

        return $id;
    }

    public function UpdateEstadoSolicitud($Estado, $SolicitudMantenimientoId) {
        $Helper = new SolicitudAmbulanciaDAL();
        $Helper->UpdateSolicitud($this->MapToUSolicitudAmbulancia($Estado), $SolicitudMantenimientoId);
    }

    public function CreateProveedor($list) {
        $Helper = new SolicitudAmbulanciaDAL();
        $id = $Helper->CreateProveedor($this->MAPToCProveedor($list));
        return $id;
    }

    public function CreateItem($list) {
        $Helper = new SolicitudAmbulanciaDAL();
        $id = $Helper->CreateItem($this->MAPToCItem($list));
        return $id;
    }

    public function CreateSolicitudAmbulancia($list) {
        $Helper = new SolicitudAmbulanciaDAL();
        $hu = new HojaVidaAmbulanciaDAL();
        if ($list->TipoSolicitud == 'MANTENIMIENTO' && is_null($list->HojaVidaId)) {
            return "Debes seleccionar una movil";
        }
        $id = $Helper->CreateSolicitud($this->MAPToArray($list));
        $Contenido = "";

        if (count($id) > 0 && is_array($id)) {
            if ($list->TipoSolicitud == 'MANTENIMIENTO') {
                $Hoja = json_decode($hu->GetHojaVidaByHojaVidaId($list->HojaVidaId))[0];
                $Contenido = "<h3>Movil: $Hoja->Placa</h3><p>$list->Descripcion.</p><br>";
            } else {
                $Contenido = "<h3>Pedido de insumos:</h3><p>$list->Descripcion.</p><br>";
            }

            $Usuarios = $hu->GetUsuariosANotificar();
            $hs = new sendMail();
            $He = new EmpresaBLL();
            $EmpresaObj = $He->GetEmpresa();
            if (count($Usuarios) > 0) {
                foreach ($Usuarios as $u) {
                    $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud Mantenimiento Ambulancia No " . $id[0], $Contenido, $u->Email, $u->NombreCompleto);
                }
                $hs->EnviarEmail_Notificacion($EmpresaObj, "Solicitud Mantenimiento Ambulancia No " . $id[0], $Contenido, "jhonyamayamed@hotmail.com", "jhony");
            }
        }

        return $id;
    }

    public function MapToUSolicitudAmbulancia($Estado) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => $Estado
        ));
        return $list2;
    }

    public function MapToDetalleFactura($Detalles, $FacturaId) {
        $list2 = Array();
        for ($index = 0; $index < count($Detalles); $index++) {
            array_push($list2, Array(
                'FacturaId' => $FacturaId,
                'ItemId' => property_exists($Detalles[$index], "Item") ? $Detalles[$index]->Item->originalObject->ItemId : $Detalles[$index]->ItemId ,
                'Precio' => $Detalles[$index]->Precio,
                'Cant' => $Detalles[$index]->Cantidad,
            ));
        }
        return $list2;
    }

    public function MAPToFactura($list) {
        $list2 = Array();
        array_push($list2, Array(
            'ProveedorId' => $list->ProveedorId,
            'SolicitudMantenimientoId' => $list->SolicitudMantenimientoId,
            'UrlArchivo' => $list->UrlArchivo,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToCProveedor($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombre' => $list->Nombre,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToCItem($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Nombre' => $list->Nombre,
            'CreatedBy' => $list->CreatedBy
        ));
        return $list2;
    }

    public function MAPToArray($list) {
        $list2 = Array();
        if ($list->TipoSolicitud == 'MANTENIMIENTO') {
            array_push($list2, Array(
                'HojaVidaId' => $list->HojaVidaId,
                'Descripcion' => $list->Descripcion,
                'TipoSolicitud' => $list->TipoSolicitud,
                'Fecha' => $list->Fecha,
                'CreatedBy' => $list->CreatedBy
            ));
        } else {
            array_push($list2, Array(
                'HojaVidaId' => NULL,
                'Descripcion' => $list->Descripcion,
                'TipoSolicitud' => $list->TipoSolicitud,
                'Fecha' => $list->Fecha,
                'CreatedBy' => $list->CreatedBy
            ));
        }

        return $list2;
    }

}
