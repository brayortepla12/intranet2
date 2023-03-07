<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmMaternaDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateTmMaterna($list) {
        $ids = $this->db->insertMulti("tm_materna", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function UpdateTmMaterna($list, $id) {
        $this->db->where('MaternaId', $id);
        if ($this->db->update('tm_materna', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetTmMaternaByMaternaId($MaternaId) {
        return $this->db->objectBuilder()->rawQuery("SELECT m.*, c.DepartamentoId, eps.Nombre as EPS FROM tm_materna as m 
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_eps as eps on eps.EPSId = m.EPSId 
        WHERE m.MaternaId = $MaternaId ;");
    }

    public function GetTmMaternas() {
        return $this->db->objectBuilder()->rawQuery("SELECT m.*, c.Ciudad, l.Nombres as Lider, l.Telefono as TelefonoLider, HaveParto(m.MaternaId) as HaveParto FROM tm_materna as m
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId order by m.MaternaId DESC;");
    }
    
    public function GetActividadByMes($Year, $Mes, $MunicipioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT m.*,
        if(Month(m.FechaRegistro) = $Mes, 'Registro', if(Month(e.CreatedAt) = $Mes, concat('Evento: ', e.TipoEvento),'...')) as Estado, 
        HaveParto(m.MaternaId) as HaveParto, e.FechaRegistro as FechaEvento, c.Ciudad, l.LiderId, l.Nombres as Lider, l.Telefono as TelefonoLider,
        ifnull((select sum(de.Precio) from tm_detalleevento as de where de.EventoId = e.EventoId),0)  as TotalEntregado
        FROM tm_materna as m 
        STRAIGHT_JOIN tm_evento as e on e.MaternaId = m.MaternaId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        where m.MunicipioId = $MunicipioId and (YEAR(e.CreatedAt) = $Year and Month(e.CreatedAt) = $Mes) group by m.MaternaId;");
    }
    
    public function GetMaternaRegistradasByMes($Year, $Mes, $MunicipioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT m.MaternaId, m.Nombres, m.Documento, m.Telefono, m.FechaProbableParto, m.FechaRegistro, m.CreatedAt,
        if(Month(e.CreatedAt) = $Mes, concat('Evento: ', e.TipoEvento),'...') as Estado, 
        HaveParto(m.MaternaId) as HaveParto, c.Ciudad, l.LiderId, l.Nombres as Lider, l.Telefono as TelefonoLider FROM tm_materna as m 
        STRAIGHT_JOIN tm_evento as e on e.MaternaId = m.MaternaId
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
        STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
        where m.MunicipioId = $MunicipioId and (YEAR(m.FechaRegistro) = $Year and Month(m.FechaRegistro) = $Mes) group by Nombres");
    }

    public function GetTmAgendaMaterna($LiderId, $From, $To) {
        if ($LiderId == "TODOS") {
            return $this->db->objectBuilder()->rawQuery("SELECT m.*, HaveParto(m.MaternaId) as HaveParto, c.Ciudad, l.LiderId, l.Nombres as Lider, l.Telefono as TelefonoLider FROM tm_materna as m 
            STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
            STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
            where m.FechaProbableParto >= '$From' and m.FechaProbableParto < '$To' order by m.FechaProbableParto, c.Ciudad;");
        }else{
            return $this->db->objectBuilder()->rawQuery("SELECT m.*, HaveParto(m.MaternaId) as HaveParto, c.Ciudad, l.LiderId, l.Nombres as Lider, l.Telefono as TelefonoLider FROM tm_materna as m 
            STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId
            STRAIGHT_JOIN tm_lider as l on l.LiderId = m.LiderId
            where m.FechaProbableParto >= '$From' and m.FechaProbableParto <= '$To' and l.LiderId = $LiderId order by m.FechaProbableParto, c.Ciudad;");
        }
    }

    public function GetTmMaternaByDocumento($Documento) {
        return $this->db->objectBuilder()->rawQuery("SELECT m.*, c.DepartamentoId, HaveParto(m.MaternaId) as HaveParto FROM tm_materna as m 
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = m.MunicipioId WHERE m.Documento = $Documento;");
    }

}
