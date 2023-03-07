<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";require __DIR__ . "/../../vendor/autoload.php";

class TipoMedicamentoDAL {

    private $db;
    
    public function __construct(){
       $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');$dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);$this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    public function __destruct()
    {
       $this->db->disconnect();
    }

    public function CreateTipoMedicamento($list) {
        $ids = $this->db->insertMulti("cm_tipomedicamento", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
    public function UpdateTipoMedicamento($list, $id) {
        $this->db->where('TipoMedicamentoId', $id);
        if ($this->db->update('cm_tipomedicamento', $list)) {
            return $list;
        } else {
            return 'update failed: ' . $this->db->getLastError();
        }
    }
    public function GetTipoMedicamento() {
        return $this->db->objectBuilder()->rawQuery("select * from cm_tipomedicamento order by Nombre;");
    }
}