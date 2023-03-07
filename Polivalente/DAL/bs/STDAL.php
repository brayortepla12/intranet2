<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . "/../DB.php";
require __DIR__ . "/../../vendor/autoload.php";

class STDAL {

    private $db;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();$dotenv->required(['HOST_MYSQL', 'USER_MYSQL', 'PASS_MYSQL', 'DATABASE_MYSQL', 'PORT_MYSQL']);
        $this->db = DB::getInstance() ? DB::getInstance() : new DB($_ENV['HOST_MYSQL'], $_ENV['USER_MYSQL'], $_ENV['PASS_MYSQL'], $_ENV['DATABASE_MYSQL'], $_ENV['PORT_MYSQL']);
    }
    
    public function __destruct()
    {
       $this->db->disconnect();
    }
    
    public function GetSTById($STId) {
        return $this->db->objectBuilder()->rawQuery("Select STId, ST from ct_cargo where STId = $STId");
    }
    
    public function GetSTs($Actual, $Anterior, $NSensor) {
        return $this->db->objectBuilder()->rawQuery("SELECT SensorTemperaturaId, Fecha, Temperatura 
        FROM bs_sensortemperatura where Fecha <= '$Actual' and Fecha >= '$Anterior' and NSensor='$NSensor';");
    }
    
    public function CreateST($list) {
        $ids = $this->db->insertMulti("bs_sensortemperatura", $list);
        if (!$ids) {
            echo 'insert failed: ' . $this->db->getLastError();
        }
        return $ids;
    }
}


