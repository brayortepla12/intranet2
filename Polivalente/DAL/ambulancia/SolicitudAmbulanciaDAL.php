<?php

include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class SolicitudAmbulanciaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function DeleteDetalleFactura($FacturaId) {
        return $this->db->objectBuilder()->rawQuery("Delete FROM polivalente.ambulancia_detallefactura where FacturaId = $FacturaId;");
    }

    public function GetDetallesByFacturaId($FacturaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT i.ItemId, i.Nombre, df.Cant as Cantidad, df.Precio FROM polivalente.ambulancia_detallefactura as df 
        inner join ambulancia_item as i on df.ItemId = i.ItemId
        where df.FacturaId = $FacturaId;");
    }

    public function GetFacturaBySolicitudMantenimientoId($SolicitudMantenimientoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT f.*, p.Nombre as Proveedor FROM polivalente.ambulancia_factura as f 
        left join ambulancia_proveedor as p on f.ProveedorId = p.ProveedorId
        where f.SolicitudMantenimientoId = $SolicitudMantenimientoId 
        order by FacturaId DESC  limit 1;");
    }

    public function GetSolicitudesMantenimiento($Year, $Mes, $Estado, $Placa) {
        return $this->db->objectBuilder()->rawQuery("SELECT sm.Descripcion, sm.SolicitudMantenimientoId, sm.Fecha, sm.TipoSolicitud, h.HojaVidaId, 
        h.SedeId, h.Placa, sm.Estado, DATE_FORMAT(f.CreatedAt,'%Y-%m-%d') as FechaCreacionFactura, DATEDIFF(ifnull( f.CreatedAt,now()), sm.Fecha)  as HaceDias   
        FROM polivalente.ambulancia_solicitudmantenimiento as sm 
        left join ambulancia_hojavida as h on h.HojaVidaId = sm.HojaVidaId
        left join ambulancia_factura as f on f.SolicitudMantenimientoId = sm.SolicitudMantenimientoId
        where YEAR(sm.Fecha) = $Year and ((MONTH(sm.Fecha) = '$Mes' and '$Mes' <> 'TODOS') or '$Mes' = 'TODOS')  
        and ((sm.Estado = '$Estado' and '$Estado' <> 'TODOS') or '$Estado' = 'TODOS') 
        and ((LOWER(h.Placa) LIKE LOWER('%$Placa%') and '$Placa' <> '') or '$Placa' = '')
        order by SolicitudMantenimientoId DESC;");
    }

    public function GetProveedores() {
        return $this->db->objectBuilder()->rawQuery("SELECT ProveedorId, Nombre FROM polivalente.ambulancia_proveedor order by Nombre;");
    }

    public function GetItem() {
        return $this->db->objectBuilder()->rawQuery("SELECT ItemId, Nombre FROM polivalente.ambulancia_item order by Nombre;");
    }

    public function CreateSolicitud($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("ambulancia_SolicitudMantenimiento", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateDetalleFactura($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("ambulancia_detallefactura", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateFactura($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("ambulancia_Factura", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateProveedor($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("ambulancia_Proveedor", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function CreateItem($list) {
//        echo print_r($list);
        $ids = $this->db->insertMulti("ambulancia_Item", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateSolicitud($list, $id) {
        $this->db->where('SolicitudMantenimientoId', $id);
        $id = $this->db->update('ambulancia_solicitudmantenimiento', $list[0]);
        if ($id) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
