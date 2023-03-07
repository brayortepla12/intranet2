<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TmMunicipioDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();
        $dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateRondaVerificacion($list) {
        $ids = $this->db->insertMulti("cm_rondaverificacion", $list);
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

    public function GetTmMunicipiosByDepartamentoId($DepartamentoId) {
        return $this->db->objectBuilder()->rawQuery("SELECT * FROM tm_ciudad where DepartamentoId = $DepartamentoId and Estado = 'Activo' order by Ciudad;");
    }

}
