<?php

/**  
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";
class ArticuloDAL
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
        return $this->db->objectBuilder()->query("SELECT * from almacen_articulo where Estado <> 'Inactivo' order by Nombre desc;");
    }

    public function GetAllByTipo($Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT * from almacen_articulo where Estado <> 'Inactivo' and ArticuloPara = '$Tipo' order by Nombre desc;");
    }

    public function GetAllByPlantilla($ServicioId, $UserId, $Tipo)
    {
        return $this->db->objectBuilder()->query("SELECT a.*,'' as TotalPedidoMes, rc.Limite, rc.RelacionCostoId , rc.DiasConsumo, rc.Cantidad from almacen_articulo as a 
        STRAIGHT_JOIN almacen_relacioncosto as rc on a.ArticuloId = rc.ArticuloId
        where rc.ServicioId = $ServicioId and rc.UsuarioId = $UserId and rc.Estado <> 'Inactivo' and a.ArticuloPara = '$Tipo' order by Nombre;"); #GetTotalSolicitadoByMes(rc.UsuarioId, a.ArticuloId)
    }

    public function getAllByUserId($UserId)
    {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM almacen_relacioncosto as rc 
        STRAIGHT_JOIN usuario as u on rc.UsuarioId = u.UsuarioId
        STRAIGHT_JOIN servicio as ser on rc.ServicioId = ser.ServicioId
        where u.UsuarioId = $UserId;");
    }

    public function GetHojaVidaPolivalente($HojaVidaId)
    {
        return $this->db->objectBuilder()->rawQuery("select h.Equipo, h.Ubicacion, h.Marca, h.Modelo,h.Serie
        FROM hojavida as h 
        where h.HojaVidaId=$HojaVidaId;");
    }

    public function GetHojaVidaSistemas($HojaVidaId)
    {
        return $this->db->objectBuilder()->rawQuery("select h.Nombre as Equipo, h.Ubicacion, h.Fabricante as Marca, 
        h.Modelo,h.NSerial as Serie
        FROM sistemas_hojavida as h
        where h.HojaVidaId =$HojaVidaId;");
    }

    public function GetHojaVidaBiomedico($HojaVidaId)
    {
        return $this->db->objectBuilder()->rawQuery("select h.Equipo, h.Ubicacion, h.Marca,
        h.Modelo,h.Serie
        FROM biomedico.hojavida as h 
        where h.HojaVidaId =$HojaVidaId;");
    }

    public function CreateArticulo($list)
    {
        $ids = $this->db->insertMulti("Almacen_Articulo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateArticulo($list, $id)
    {
        $this->db->where('ArticuloId', $id);
        if ($this->db->update('Almacen_Articulo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
}
