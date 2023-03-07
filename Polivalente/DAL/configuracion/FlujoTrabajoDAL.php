<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class FlujoTrabajoDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function getAll() {
        return $this->db->objectBuilder()->get("PC_FlujoTrabajo");
    }

    public function getAllByProtocoloId($ProcotoloId) {
        return $this->db->objectBuilder()->query("SELECT * FROM pc_flujotrabajo where ProtocoloId = $ProcotoloId order by Orden;");
    }

    public function DeleteByProtocoloId($ProcotoloId) {
        $this->db->query("Delete from PC_FlujoTrabajo where ProtocoloId = " . $ProcotoloId);
    }

    public function CreateFlujoTrabajo($list) {
        $ids = $this->db->insertMulti("PC_FlujoTrabajo", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }

    public function GetFlujoTrabajo($Nombre) {
        $this->db->where("Nombre", $Nombre);
        return $this->db->objectBuilder()->get("PC_FlujoTrabajo");
    }

    public function UpdateFlujoTrabajo($list, $id) {
        $this->db->where('FlujoTrabajoId', $id);
        if ($this->db->update('PC_FlujoTrabajo', $list[0])) {
            return $list[0];
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }

}
