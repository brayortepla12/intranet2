<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class PedidoAlmacenDAL
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

    public function getAll()
    {
        return $this->db->objectBuilder()->query("SELECT * from PedidoAlmacen where Estado <> 'Inactivo' order by PedidoAlmacenId desc;");
    }

    public function getPENDIENTES($SolicitanteId)
    {
        return $this->db->objectBuilder()->query("select * from almacen_pedidoalmacen where Estado = 'Entregar' and SolicitanteId = $SolicitanteId;");
    }

    public function getPENDIENTES_2($SolicitanteId, $ServicioId, $TipoPedido)
    {
        return $this->db->objectBuilder()->query("select * from almacen_pedidoalmacen where Estado = 'Entregar' and SolicitanteId = $SolicitanteId 
            and ServicioId = $ServicioId
            and GetCountArticulosByTipo(PedidoAlmacenId, '$TipoPedido') > 0;");
    }

    public function getAllBySedeId(string $SedeId, string $Estado): array
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.CargoSolicitante, pa.FechaSolicitud, pa.CreatedAt, pa.CreatedBy, pa.Estado, pa.SolicitanteId, pa.NombreSolicitante, pa.PedidoAlmacenId, 
            s.Nombre as Servicio, se.Nombre as Sede FROM PedidoAlmacen as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where s.SedeId = $SedeId and  pa.Estado = '$Estado' order by PedidoAlmacenId desc;");
    }

    public function getAllBySedeIdVer2($SedeId, $Estado, $Tipo)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM almacen_pedidoalmacen as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where pa.SedeId = $SedeId and Estado = '$Estado' and 
            pa.Bodega = '$Tipo'
            order by pa.PedidoAlmacenId desc limit 500;");
    }

    public function getAllBySedeIdVer2Pedido($SedeId, $Estado, $Tipo)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM pedidoalmacen_repuesto as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where pa.SedeId = $SedeId and Estado = '$Estado' and 
            pa.Bodega = '$Tipo'
            order by pa.PedidoAlmacenId desc limit 500;");
    }

    public function getAllByUserId($UserId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM PedidoAlmacen as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where SolicitanteId = $UserId and Estado <> 'Inactivo' order by PedidoAlmacenId desc;");
    }

    public function getAllByUserIdVer2($SolicitanteId, $Tipo)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM almacen_pedidoalmacen as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where SolicitanteId = $SolicitanteId and Estado <> 'Inactivo' order by PedidoAlmacenId desc");
    }

    public function getAllByUserIdVer2Repuesto($SolicitanteId, $Tipo)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM pedidoalmacen_repuesto as pa
            STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
            STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
            where SolicitanteId = $SolicitanteId and Estado <> 'Inactivo' order by PedidoAlmacenId desc");
    }

    public function getAllById($PedidoAlmacenId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM PedidoAlmacen as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where pa.PedidoAlmacenId = $PedidoAlmacenId and Estado <> 'Inactivo' order by PedidoAlmacenId desc;");
    }

    public function GetPedidoById_sm($PedidoAlmacenId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM almacen_pedidoalmacen as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where pa.PedidoAlmacenId = $PedidoAlmacenId and Estado <> 'Inactivo' order by PedidoAlmacenId desc;");
    }

    public function getAllByIdVer2($PedidoAlmacenId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT pa.*,
            s.Nombre as Servicio, se.Nombre as Sede FROM almacen_pedidoalmacen as pa
                STRAIGHT_JOIN servicio as s on pa.ServicioId = s.ServicioId
                STRAIGHT_JOIN sede as se on pa.SedeId = se.SedeId
                where pa.PedidoAlmacenId = $PedidoAlmacenId and Estado <> 'Inactivo' order by PedidoAlmacenId desc;");
    }

     public function GetAllItemsByIdRepuesto($PedidoAlmacenId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT ip.*,'' as TotalPedidoMes, rc.Limite,s.Nombre as DirigidoA, a.CodigoKrystalos, a.Nombre, a.NombreKrystalos, a.GrupoId, rc.DiasConsumo, rc.Cantidad 
        FROM almacen_itempedido as ip
        STRAIGHT_JOIN almacen_relacioncosto as rc on ip.RelacionCostoId = rc.RelacionCostoId
        STRAIGHT_JOIN almacen_articulo as a on ip.ArticuloId = a.ArticuloId
         STRAIGHT_JOIN servicio as s on ip.DirigidoA = s.ServicioId
        where ip.PedidoAlmacenId = $PedidoAlmacenId and ip.CantidadSolicitada > 0 and ip.Estado <> 'Inactivo';");
    }

    public function GetAllItemsById($PedidoAlmacenId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT ip.*,'' as TotalPedidoMes, rc.Limite, a.CodigoKrystalos, a.Nombre, a.NombreKrystalos, a.GrupoId, rc.DiasConsumo, rc.Cantidad 
        FROM almacen_itempedido as ip
        STRAIGHT_JOIN almacen_relacioncosto as rc on ip.RelacionCostoId = rc.RelacionCostoId
        STRAIGHT_JOIN almacen_articulo as a on ip.ArticuloId = a.ArticuloId
        where ip.PedidoAlmacenId = $PedidoAlmacenId and ip.CantidadSolicitada > 0 and ip.Estado <> 'Inactivo';");
    }

    public function CreatePedidoAlmacen($list)
    {
        $ids = $this->db->insertMulti("PedidoAlmacen", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreatePedidoAlmacen2($list)
    {
        $ids = $this->db->insertMulti("almacen_pedidoalmacen", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function CreatePedidoAlmacenRepuesto($list)
    {
        $ids = $this->db->insertMulti("pedidoalmacen_repuesto", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function AsignarPedidoAlmacenUsuario($list)
    {
        $ids = $this->db->insertMulti("UsuarioPedidoAlmacen", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function RemoverPedidoAlmacenUsuario($PedidoAlmacenId, $UsuarioId)
    {
        $this->db->query("DELETE FROM UsuarioPedidoAlmacen WHERE PedidoAlmacenId=$PedidoAlmacenId and UsuarioId=$UsuarioId");
    }

    public function GetPedidoAlmacen($State)
    {
        $this->db->where("State", $State);
        $this->db->orderBy("PedidoAlmacenId", "DESC");
        return $this->db->objectBuilder()->get("PedidoAlmacen");
    }

    public function GetUsuarioPedidoAlmacenById($PedidoAlmacenId, $UsuarioId)
    {
        $this->db->where("PedidoAlmacenId", $PedidoAlmacenId);
        $this->db->where("UsuarioId", $UsuarioId);
        $this->db->orderBy("PedidoAlmacenId", "DESC");
        return $this->db->objectBuilder()->getOne("UsuarioPedidoAlmacen");
    }

    public function UpdatePedidoAlmacen($list, $id)
    {
        $this->db->where('PedidoAlmacenId', $id);
        if ($this->db->update('PedidoAlmacen', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdatePedidoAlmacenVer2($list, $id)
    {
        $this->db->where('PedidoAlmacenId', $id);
        if ($this->db->update('almacen_pedidoalmacen', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function UpdatePedidoAlmacenVer2Repuesto($list, $id)
    {
        $this->db->where('PedidoAlmacenId', $id);
        if ($this->db->update('pedidoalmacen_repuesto', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    public function Search(string $query): array
    {
        try {
            return $this->db->objectBuilder()->query($query);
        } catch (Exception $e) {
            $this->LogFile("{$this->GetFHNow('fh')}: $query \n {$e->getMessage()}\n\n", "log-search.txt"); #Guardamos un log
        }
        return [];
    }
    /**
     * Obtener fechas
     *
     * @param string $F Fecha|fh|h
     * @return string
     */
    private function GetFHNow(string $F = "Fecha"): string
    {
        $tz_object = new DateTimeZone('America/Bogota');
        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        $format = '';
        switch ($F) {
            case 'f':
                $format = 'Y-m-d';
                break;
            case 'fh':
                $format = 'Y-m-d H:i:s';
                break;

            default:
                $format = 'H:i:s';
                break;
        }
        return $datetime->format($format);
    }
    public function LogFile(string $Msg, string $fileName)
    {
        $fp = fopen(__DIR__ . "/$fileName", 'a+');
        fwrite($fp, $Msg);
        fclose($fp);
    }
}
