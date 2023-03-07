<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmEventoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateEvento($list) {
        $ids = $this->db->insertMulti("TM_Evento", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDetalleEvento($list) {
        $ids = $this->db->insertMulti("TM_DetalleEvento", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function DeleteTmEvento($EventoId) {
        $this->db->objectBuilder()->rawQuery("Delete de from tm_detalleevento as de 
        STRAIGHT_JOIN tm_evento as e on e.EventoId = de.EventoId
        where de.EventoId = $EventoId and e.Estado = 'Activo';");
        $this->db->objectBuilder()->rawQuery("Delete from tm_evento where Estado = 'Activo' and EventoId = $EventoId;");
        return [$EventoId];
    }

    public function UpdateEvento($list, $id) {
        $this->db->where('EventoId', $id);
        if ($this->db->update('tm_evento', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetTmEventos($TipoEvento) {
        return $this->db->objectBuilder()->rawQuery("select Tabla.* from (SELECT e.*, m.Nombres, (select sum(t.Precio) from tm_detalleevento as de 
        STRAIGHT_JOIN tm_tarifa as t on t.TarifaId = de.TarifaId
        where de.EventoId = e.EventoId) as Total  FROM tm_evento as e
        STRAIGHT_JOIN tm_materna as m on e.MaternaId = m.MaternaId 
        where e.Estado = '$TipoEvento' or (e.Estado = 'Activo' and 'Sin Costo' = '$TipoEvento')) as Tabla "
                . "where (Tabla.Total is not null and Tabla.Total > 0 and 'Sin Costo' <> '$TipoEvento') or "
                . "('Sin Costo' = '$TipoEvento' and Tabla.Total is null or Tabla.Total = 0) 
        order by Tabla.EventoId DESC;");
    }
    
    public function GetTmEventoByMaternaIdMenosEste($MaternaId, $EventoId) {
        return $this->db->objectBuilder()->rawQuery("select e.*, m.Nombres, m.Documento, m.Telefono, m.FechaRegistro as FechaRegistroMaterna, 
            d.DepartamentoId, d.Departamento, c.Ciudad as Municipio, m.MunicipioId, l.LiderId, l.Nombres as Lider, 
            l.Telefono as TelefonoLider, 
            (select sum(de.Precio) from tm_detalleevento as de where de.EventoId = e.EventoId)  as TotalEntregado
            FROM tm_evento as e
        STRAIGHT_JOIN tm_materna as m on e.MaternaId = m.MaternaId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_departamento as d on d.DepartamentoId = c.DepartamentoId
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        where e.MaternaId = $MaternaId and e.EventoId <> $EventoId order by EventoId DESC;");
    }
    
    public function GetTmEventoByMaternaId($MaternaId) {
        return $this->db->objectBuilder()->rawQuery("select e.*, m.Nombres, m.Documento, m.Telefono, m.FechaRegistro as FechaRegistroMaterna, 
            d.DepartamentoId, d.Departamento, c.Ciudad as Municipio, m.MunicipioId, l.LiderId, l.Nombres as Lider, 
            l.Telefono as TelefonoLider, 
            (select sum(de.Precio) from tm_detalleevento as de where de.EventoId = e.EventoId)  as TotalEntregado
            FROM tm_evento as e
        STRAIGHT_JOIN tm_materna as m on e.MaternaId = m.MaternaId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_departamento as d on d.DepartamentoId = c.DepartamentoId
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        where e.MaternaId = $MaternaId order by EventoId DESC;");
    }
    
    public function GetTmEventoByEventoId($EventoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT e.*, m.Nombres, m.Documento, m.Telefono, m.FechaRegistro as FechaRegistroMaterna, 
            d.DepartamentoId, d.Departamento, c.Ciudad as Municipio, m.MunicipioId, l.LiderId, l.Nombres as Lider, 
            l.Telefono as TelefonoLider FROM tm_evento as e
        STRAIGHT_JOIN tm_materna as m on e.MaternaId = m.MaternaId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_departamento as d on d.DepartamentoId = c.DepartamentoId
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        where e.EventoId = $EventoId;");
    }
    
    public function GetDetalleEventos($EventoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT de.*, t.Nombre FROM polivalente.tm_detalleevento as de
        STRAIGHT_JOIN tm_tarifa as t on de.TarifaId = t.TarifaId
        where EventoId = $EventoId;");
    }
}
