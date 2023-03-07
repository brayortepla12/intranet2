<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
include_once dirname(__FILE__) . "/db.php";
class RelacionDAL
{
    private $db;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
        $this->db->disconnect();
    }

    public function DeletePlantillaByUsuarioIdAndServicioId($ServicioId, $UsuarioDestinoId)
    {
        return $this->db->objectBuilder()->query("DELETE rc.* FROM almacen_relacioncosto as rc where rc.UsuarioId = $UsuarioDestinoId  and rc.ServicioId = $ServicioId;");
    }

    public function ClonarPlantilla($UsuarioOrigenId, $ServicioId, $UsuarioDestinoId)
    {
        return $this->db->objectBuilder()->query("INSERT INTO almacen_relacioncosto (
        `ArticuloId`,
        `Cantidad`,
        `SedeId`,
        `DiasConsumo`,
        `ServicioId`,
        `UsuarioId`,
        Limite,
        `Estado`,
        `CreatedBy`)
        SELECT rc.ArticuloId, rc.Cantidad, rc.SedeId, rc.DiasConsumo, rc.ServicioId, $UsuarioDestinoId/* Usuario Destino*/, rc.Limite , rc.Estado, rc.CreatedBy 
        FROM almacen_relacioncosto as rc 
        STRAIGHT_JOIN almacen_articulo as a on rc.ArticuloId = a.ArticuloId
        where rc.UsuarioId=$UsuarioOrigenId/* Usuario Origen*/ and rc.ServicioId = $ServicioId;/* Servicio a clonar*/ #and a.ArticuloPara= 'Almacen' /*Tipo de articulo a clonar*/;");
    }

    public function getAll()
    {
        return $this->db->objectBuilder()->query("SELECT * from almacen_Relacion order by Nombre;");
    }

    public function GetPlantilla($UsuarioId, $Estado)
    {
        return $this->db->objectBuilder()->query("SELECT r.RelacionCostoId, r.ArticuloId, a.Nombre as Articulo, if(r.Limite is null, r.Cantidad, r.Limite) as Limite, 
            r.UsuarioId,u.NombreCompleto as Solicitante, s.Nombre as Servicio
            FROM almacen_relacioncosto as r
        STRAIGHT_JOIN almacen_articulo as a on r.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN usuario as u on u.UsuarioId = r.UsuarioId
        STRAIGHT_JOIN servicio as s on r.ServicioId = s.ServicioId
        where r.UsuarioId=$UsuarioId and r.Estado = '$Estado' order by s.Nombre, a.Nombre;");
    }

    public function getAllData()
    {
        return $this->db->objectBuilder()->query("SELECT a.CodigoKrystalos, a.NombreKrystalos, a.Nombre,
        rc.Cantidad, rc.DiasConsumo, s.Nombre as Sede, ser.Nombre as Servicio
        from almacen_relacioncosto as rc 
        STRAIGHT_JOIN almacen_articulo as a on rc.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN sede as s on rc.SedeId = s.SedeId
        STRAIGHT_JOIN servicio as ser on rc.ServicioId = ser.ServicioId
        where rc.Estado <> 'Inactivo' 
        order by ser.Nombre,  a.Nombre;");
    }

    // GENERAR EL EXEL Consulta de pedidos
    public function getEstadisticasPedidos($From, $To, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo'  and i.isRepuesto = $TipoSolicitud and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY))
        order by ser.Nombre,  a.Nombre;");
    }

    public function getEstadisticasPedidosSede($From, $To, $SedeId, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo'  and i.isRepuesto = $TipoSolicitud and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.SedeId = $SedeId
        order by ser.Nombre,  a.Nombre;");
    }

    public function getEstadisticasPedidosServicio($From, $To, $ServicioId, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo'  and i.isRepuesto = $TipoSolicitud and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.ServicioId = $ServicioId
        order by ser.Nombre,  a.Nombre;");
    }
    // GENERAR EL EXEL

    // GENERAR EL EXEL Consulta de repuestos
    public function getEstadisticasPedidosRepuesto($From, $To, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante, SerDir.Nombre as DirigidoA,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN servicio as serDir on i.DirigidoA = serDir.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo'  and i.isRepuesto = $TipoSolicitud  and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY))
        order by ser.Nombre,  a.Nombre;");
    }

    public function getEstadisticasPedidosSedeRepuesto($From, $To, $SedeId, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante, SerDir.Nombre as DirigidoA, a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN servicio as serDir on i.DirigidoA = serDir.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo'  and i.isRepuesto = $TipoSolicitud and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.SedeId = $SedeId
        order by ser.Nombre,  a.Nombre;");
    }

    public function getEstadisticasPedidosServicioRepuesto($From, $To, $ServicioId, $Tipo, $TipoSolicitud)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%d/%m/%Y') as FechaEntrega, p.NombreSolicitante, SerDir.Nombre as DirigidoA,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN servicio as serDir on i.DirigidoA = serDir.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and i.isRepuesto = $TipoSolicitud and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.ServicioId = $ServicioId
        order by ser.Nombre,  a.Nombre;");
    }
    // GENERAR EL EXEL


    public function getEstadisticasPedidos_data($From, $To, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio,  DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and  i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and  p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY))
        order by ser.Nombre;");
    }

    public function getEstadisticasPedidos_dataSede($From, $To, $SedeId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and   p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.SedeId = $SedeId
        order by ser.Nombre;");
    }

    public function getEstadisticasPedidos_dataServicio($From, $To, $ServicioId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN almacen_pedidoalmacen as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and  p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.ServicioId = $ServicioId
        order by ser.Nombre;");
    }

    public function getEstadisticasPedidos_dataRepuesto($From, $To, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado,serD.Nombre as ServicioDirigido FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
    STRAIGHT_JOIN servicio as serD on i.DirigidoA = serD.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara =  '$Tipo' and i.isRepuesto = 1 and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and  p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY))
        order by ser.Nombre;");
    }

    public function getEstadisticasPedidos_dataSedeRepuesto($From, $To, $SedeId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado,serD.Nombre as ServicioDirigido  FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
        STRAIGHT_JOIN servicio as serD on i.DirigidoA = serD.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and i.isRepuesto = 1 and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and   p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.SedeId = $SedeId
        order by ser.Nombre;");
    }

    public function getEstadisticasPedidos_dataServicioRepuesto($From, $To, $ServicioId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT s.Nombre as Sede, ser.Nombre as Servicio, DATE_FORMAT(p.FechaRecibe, '%Y-%m-%d') as FechaEntrega, p.NombreSolicitante,  a.Nombre, a.CodigoKrystalos, i.CantidadEntregada as TotalEntregado,serD.Nombre as ServicioDirigido  FROM polivalente.almacen_itempedido as i 
        STRAIGHT_JOIN almacen_articulo as a on i.ArticuloId = a.ArticuloId
        STRAIGHT_JOIN pedidoalmacen_repuesto as p on p.PedidoAlmacenId = i.PedidoAlmacenId
        STRAIGHT_JOIN servicio as ser on p.ServicioId = ser.ServicioId
STRAIGHT_JOIN servicio as serD on i.DirigidoA = serD.ServicioId
        STRAIGHT_JOIN sede as s on p.SedeId = s.SedeId
        where p.Estado = 'Recibir' and a.ArticuloPara = '$Tipo' and i.isRepuesto = 1  and i.CantidadEntregada > 0 and (p.FechaRecibe >= '$From' and  p.FechaRecibe <= DATE_ADD('$To', INTERVAL 1 DAY)) and p.ServicioId = $ServicioId
        order by ser.Nombre;");
    }

    public function getTODO($articulo)
    {
        #$db = new SQLSRV_DataBase("sa", "CLD_123456", "KPRADO", "192.168.110.6");
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_SQLSERVER', 'USER_SQLSERVER', 'PASS_SQLSERVER', 'DATABASE_SQLSERVER', 'PORT_SQLSERVER']);
        $db = new SQLSRV_DataBase($_ENV['USER_SQLSERVER'], $_ENV['PASS_SQLSERVER'], $_ENV['DATABASE_SQLSERVER'], $_ENV['HOST_SQLSERVER'], $_ENV['PORT_SQLSERVER']);
        $query = "select convert(float,PCOSTO) as PCOSTO from IART WHERE IDARTICULO = '$articulo';";
        $result = $db->get_row($query);
        return $result;
    }

    public function getAllByUsuarioId($ServicioId, $UsuarioId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT rc.*, a.CodigoKrystalos, a.Nombre,  a.NombreKrystalos from almacen_relacioncosto as rc
        STRAIGHT_JOIN almacen_articulo as a on rc.ArticuloId = a.ArticuloId
         where rc.UsuarioId = $UsuarioId and rc.ServicioId = $ServicioId and a.ArticuloPara = '$Tipo' order by a.Nombre;");
    }

    public function IsInDB($ServicioId, $UsuarioId, $ArticuloId)
    {
        return $this->db->objectBuilder()->query("SELECT * from almacen_relacioncosto where UsuarioId = $UsuarioId and ServicioId = $ServicioId and ArticuloId = $ArticuloId;");
    }

    public function CreateRelacion($list)
    {
        $ids = $this->db->insertMulti("almacen_relacioncosto", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateRelacion($list, $id)
    {
        $this->db->where('RelacionCostoId', $id);
        if ($this->db->update('almacen_relacioncosto', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
