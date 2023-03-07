<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmEPSDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateEPS($list) {
        $ids = $this->db->insertMulti("TM_EPS", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDetalleEPS($list) {
        $ids = $this->db->insertMulti("TM_DetalleEPS", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    

    public function UpdateRondaVerificacion($list, $id) {
        $this->db->where('RondaVerificacionId', $id);
        if ($this->db->update('cm_rondaverificacion', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

    public function GetTmEPSs() {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM tm_eps order by Nombre;");
    }
    
    public function GetTmEPSByEPSId($EPSId) {
        return $this->db->objectBuilder()->rawQuery("SELECT l.*, d.DepartamentoId, l.MunicipioId, c.Ciudad FROM tm_EPS as l
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = l.MunicipioId
        STRAIGHT_JOIN tm_departamento as d on d.DepartamentoId = c.DepartamentoId
        where l.EPSId = $EPSId;");
    }
    
    public function GetDetalleEPSs($EPSId) {
        return $this->db->objectBuilder()->rawQuery("SELECT de.*, t.Nombre FROM polivalente.tm_detalleevento as de
        STRAIGHT_JOIN tm_tarifa as t on de.TarifaId = t.TarifaId
        where EPSId = $EPSId;");
    }
}
