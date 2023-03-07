<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmLiderDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetTmLiderByDocumento($Documento) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM tm_lider
        where Documento = $Documento;");
    }

    public function CreateLider($list) {
        $ids = $this->db->insertMulti("TM_Lider", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    
    public function CreateDetalleLider($list) {
        $ids = $this->db->insertMulti("TM_DetalleLider", $list);
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

    public function GetTmLiders() {
        return $this->db->objectBuilder()->rawQuery("SELECT l.*, c.Ciudad FROM tm_lider as l
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = l.MunicipioId order by l.Nombres DESC;");
    }
    
    public function GetTmLiderByLiderId($LiderId) {
        return $this->db->objectBuilder()->rawQuery("SELECT l.*, d.DepartamentoId, l.MunicipioId, c.Ciudad FROM tm_lider as l
        STRAIGHT_JOIN tm_ciudad as c on c.CiudadId = l.MunicipioId
        STRAIGHT_JOIN tm_departamento as d on d.DepartamentoId = c.DepartamentoId
        where l.LiderId = $LiderId;");
    }
    
    public function GetDetalleLiders($LiderId) {
        return $this->db->objectBuilder()->rawQuery("SELECT de.*, t.Nombre FROM tm_detalleevento as de
        STRAIGHT_JOIN tm_tarifa as t on de.TarifaId = t.TarifaId
        where LiderId = $LiderId;");
    }
    
    public function GetTmLiderByMunicipioId($MunicipioId) {
        return $this->db->objectBuilder()->rawQuery("SELECT Nombres, LiderId FROM tm_lider
        where MunicipioId = $MunicipioId;");
    }
    
    
}
